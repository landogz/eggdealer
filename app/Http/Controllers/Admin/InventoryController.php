<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryUpdateRequest;
use App\Models\AuditLog;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display inventory list with stock levels and low-stock alerts.
     */
    public function index(): View
    {
        $inventories = Inventory::with('eggSize')->orderBy('egg_size_id')->get();

        return view('admin.inventory.index', [
            'inventories' => $inventories,
        ]);
    }

    /**
     * Update minimum stock alert for an inventory row.
     */
    public function update(InventoryUpdateRequest $request, Inventory $inventory)
    {
        $validated = $request->validated();
        $inventory->update(['minimum_stock_alert' => (int) $validated['minimum_stock_alert']]);
        $inventory->load('eggSize');
        AuditLog::record('inventory.updated', $inventory, [
            'egg_size_id' => $inventory->egg_size_id,
            'minimum_stock_alert' => (int) $validated['minimum_stock_alert'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $inventory->fresh()->load('eggSize'),
                'message' => 'Minimum stock alert updated.',
            ]);
        }

        return back()->with('status', 'Minimum stock alert updated.');
    }
}
