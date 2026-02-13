<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CrackedEggStoreRequest;
use App\Models\AuditLog;
use App\Models\CrackedEgg;
use App\Models\EggSize;
use App\Models\Inventory;
use App\Models\User;
use App\Notifications\AdminNotification;
use App\Services\Inventory\InventoryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CrackedEggController extends Controller
{
    /**
     * Display cracked eggs records.
     */
    public function index(Request $request): View
    {
        $query = CrackedEgg::with('eggSize')->orderByDesc('date_recorded')->orderByDesc('created_at');

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        if ($dateFrom) {
            $query->whereDate('date_recorded', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date_recorded', '<=', $dateTo);
        }

        $perPage = in_array($request->input('per_page'), [10, 20, 50], true) ? (int) $request->input('per_page') : 20;
        $records = $query->paginate($perPage)->withQueryString();

        return view('admin.cracked-eggs.index', [
            'records' => $records,
            'eggSizes' => EggSize::where('is_active', true)->orderBy('size_name')->get(),
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'perPage' => $perPage,
        ]);
    }

    /**
     * Store a cracked egg record and decrease inventory.
     */
    public function store(Request $request)
    {
        $validated = $request->validated();
        $validated['recorded_by'] = auth()->id();

        $inventory = Inventory::firstWhere('egg_size_id', $validated['egg_size_id']);
        $currentPieces = $inventory ? (int) $inventory->current_stock_pieces : 0;
        if ($currentPieces < $validated['quantity_cracked']) {
            $message = 'Insufficient stock to deduct. Available: ' . $currentPieces . ' pieces.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['quantity_cracked' => $message]);
        }

        $crackedEgg = CrackedEgg::create($validated);
        $crackedEgg->load('eggSize');

        InventoryService::adjust($validated['egg_size_id'], -$validated['quantity_cracked']);

        $sizeName = $crackedEgg->eggSize->size_name ?? 'Eggs';
        User::whereIn('role', ['admin', 'inventory_manager'])->each(function ($user) use ($crackedEgg, $sizeName) {
            $user->notify(new AdminNotification(
                'cracked',
                'Cracked eggs recorded',
                number_format($crackedEgg->quantity_cracked) . ' ' . $sizeName . ' recorded as cracked.' . ($crackedEgg->reason ? ' ' . $crackedEgg->reason : ''),
                url('/admin/cracked-eggs')
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

        AuditLog::record('cracked_egg.created', $crackedEgg, [
            'quantity_cracked' => $crackedEgg->quantity_cracked,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => ['cracked_egg' => $crackedEgg->load('eggSize')],
                'message' => 'Cracked eggs recorded and inventory updated.',
            ]);
        }

        return back()->with('status', 'Cracked eggs recorded and inventory updated.');
    }
}
