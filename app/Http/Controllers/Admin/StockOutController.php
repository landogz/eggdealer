<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StockOutStoreRequest;
use App\Models\AuditLog;
use App\Models\EggPrice;
use App\Models\EggSize;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\Setting;
use App\Models\StockOut;
use App\Models\User;
use App\Notifications\AdminNotification;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockOutController extends Controller
{
    /**
     * Display stock-out (sales) list.
     */
    public function index(Request $request): View
    {
        $query = StockOut::with('eggSize')->orderByDesc('transaction_date');

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        if ($dateFrom) {
            $query->whereDate('transaction_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('transaction_date', '<=', $dateTo);
        }

        $perPage = in_array($request->input('per_page'), [10, 20, 50], true) ? (int) $request->input('per_page') : 20;
        $stockOuts = $query->paginate($perPage)->withQueryString();
        $eggSizes = EggSize::where('is_active', true)->orderBy('size_name')->get();
        $activePrices = EggPrice::where('status', 'active')
            ->whereIn('egg_size_id', $eggSizes->pluck('id'))
            ->orderByDesc('effective_date')
            ->get()
            ->unique('egg_size_id')
            ->keyBy('egg_size_id');
        $activePricesJson = $activePrices->map(function ($p) {
            return [
                'price_per_piece' => $p->price_per_piece,
                'price_per_tray' => $p->price_per_tray,
                'price_bulk' => $p->price_bulk,
            ];
        })->toArray();

        return view('admin.stock-out.index', [
            'stockOuts' => $stockOuts,
            'eggSizes' => $eggSizes,
            'activePrices' => $activePrices,
            'activePricesJson' => $activePricesJson,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Store a new sale and decrease inventory.
     */
    public function store(StockOutStoreRequest $request)
    {
        $validated = $request->validated();

        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['payment_status'] = $validated['payment_status'] ?? 'unpaid';
        $validated['transaction_date'] = $validated['transaction_date'] . ' ' . now()->format('H:i:s');

        $validated['total_amount'] = ($validated['quantity'] * $validated['price_used']) - (float) $validated['discount'];
        $validated['total_amount'] = max(0, round($validated['total_amount'], 2));

        $traySize = (int) (Setting::query()->value('default_tray_size') ?: 30) ?: 30;
        $piecesToDeduct = $validated['order_type'] === 'tray'
            ? $validated['quantity'] * $traySize
            : $validated['quantity'];

        $inventory = Inventory::firstWhere('egg_size_id', $validated['egg_size_id']);
        $currentPieces = $inventory ? (int) $inventory->current_stock_pieces : 0;
        if ($currentPieces < $piecesToDeduct) {
            $message = 'Insufficient stock. Available: ' . number_format($currentPieces) . ' pieces. You tried to sell ' . ($validated['order_type'] === 'tray' ? $validated['quantity'] . ' tray(s) (' . $piecesToDeduct . ' pieces)' : $validated['quantity'] . ' piece(s)') . '.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['quantity' => $message]);
        }

        $avgCost = StockIn::where('egg_size_id', $validated['egg_size_id'])
            ->whereNotNull('cost_per_piece')
            ->orderByDesc('delivery_date')
            ->limit(20)
            ->avg('cost_per_piece');
        $validated['profit'] = $avgCost !== null
            ? round($validated['total_amount'] - ($avgCost * $piecesToDeduct), 2)
            : null;

        $stockOut = StockOut::create($validated);
        $stockOut->load('eggSize');

        InventoryService::adjust($validated['egg_size_id'], -$piecesToDeduct);

        $sizeName = $stockOut->eggSize->size_name ?? 'Eggs';
        User::whereIn('role', ['admin', 'inventory_manager'])->each(function ($user) use ($stockOut, $sizeName) {
            $user->notify(new AdminNotification(
                'sale',
                'New sale recorded',
                'Sale: ' . number_format($stockOut->quantity) . ' ' . $sizeName . ' · ₱' . number_format($stockOut->total_amount, 2),
                url('/admin/stock-out')
            ));
        });

        $inventory = Inventory::firstWhere('egg_size_id', $validated['egg_size_id']);
        if ($inventory && $inventory->minimum_stock_alert > 0 && $inventory->current_stock_pieces <= $inventory->minimum_stock_alert) {
            User::whereIn('role', ['admin', 'inventory_manager'])->each(function ($user) use ($sizeName) {
                $user->notify(new AdminNotification(
                    'low_stock',
                    'Low stock alert',
                    $sizeName . ' is at or below minimum stock level.',
                    url('/admin/inventory')
                ));
            });
        }

        AuditLog::record('stock_out.created', $stockOut, [
            'quantity' => $stockOut->quantity,
            'total_amount' => $stockOut->total_amount,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => ['stock_out' => $stockOut->load('eggSize')],
                'message' => 'Sale recorded and inventory updated.',
            ]);
        }

        return back()->with('status', 'Sale recorded and inventory updated.');
    }
}
