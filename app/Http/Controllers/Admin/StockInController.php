<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\EggSize;
use App\Models\Setting;
use App\Models\StockIn;
use App\Http\Requests\Admin\StockInStoreRequest;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StockInController extends Controller
{
    /**
     * Display stock-in (purchases) list.
     */
    public function index(Request $request): View
    {
        $query = StockIn::with('eggSize')->orderByDesc('delivery_date')->orderByDesc('created_at');

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        if ($dateFrom) {
            $query->whereDate('delivery_date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('delivery_date', '<=', $dateTo);
        }

        $perPage = in_array($request->input('per_page'), [10, 20, 50], true) ? (int) $request->input('per_page') : 20;
        $stockIns = $query->paginate($perPage)->withQueryString();
        $piecesPerTray = (int) (Setting::query()->value('default_tray_size') ?: 30);
        $piecesPerTray = $piecesPerTray > 0 ? $piecesPerTray : 30;

        return view('admin.stock-in.index', [
            'stockIns' => $stockIns,
            'eggSizes' => EggSize::where('is_active', true)->orderBy('size_name')->get(),
            'piecesPerTray' => $piecesPerTray,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Store a new stock-in purchase and update inventory.
     */
    public function store(StockInStoreRequest $request)
    {
        $piecesPerTray = (int) (Setting::query()->value('default_tray_size') ?: 30);
        $piecesPerTray = $piecesPerTray > 0 ? $piecesPerTray : 30;

        $validated = $request->validated();

        $trays = isset($validated['quantity_trays']) && $validated['quantity_trays'] > 0
            ? (int) round((float) $validated['quantity_trays'])
            : 0;
        $pieces = isset($validated['quantity_pieces']) && $validated['quantity_pieces'] > 0
            ? (int) $validated['quantity_pieces']
            : 0;

        if ($trays > 0 && $pieces <= 0) {
            $validated['quantity_pieces'] = $trays * $piecesPerTray;
        } elseif ($pieces > 0) {
            $validated['quantity_pieces'] = $pieces;
        } else {
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => 'Please enter quantity in pieces or trays.'], 422)
                : back()->withErrors(['quantity_pieces' => 'Please enter quantity in pieces or trays.']);
        }

        unset($validated['quantity_trays']);

        if (! isset($validated['total_cost']) || $validated['total_cost'] === null) {
            if (! empty($validated['cost_per_piece'])) {
                $validated['total_cost'] = $validated['cost_per_piece'] * $validated['quantity_pieces'];
            }
        }

        $stockIn = StockIn::create($validated);

        $inventory = InventoryService::adjust(
            $stockIn->egg_size_id,
            (int) $stockIn->quantity_pieces
        );

        AuditLog::record('stock_in.created', $stockIn, [
            'quantity_pieces' => $stockIn->quantity_pieces,
            'total_cost' => $stockIn->total_cost,
            'inventory_id' => $inventory->id,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'stock_in' => $stockIn->load('eggSize'),
                    'inventory' => $inventory,
                ],
                'message' => 'Stock in recorded and inventory updated.',
            ]);
        }

        return back()->with('status', 'Stock in recorded and inventory updated.');
    }
}
