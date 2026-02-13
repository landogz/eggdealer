<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EggSizeStoreRequest;
use App\Http\Requests\Admin\EggSizeUpdateRequest;
use App\Models\AuditLog;
use App\Models\CrackedEgg;
use App\Models\EggPrice;
use App\Models\EggSize;
use App\Models\Inventory;
use App\Models\StockIn;
use App\Models\StockOut;
use Illuminate\Http\Request;

class EggSizeController extends Controller
{
    /**
     * Display a listing of egg sizes.
     */
    public function index()
    {
        $sizes = EggSize::orderBy('size_name')->get();

        return view('admin.egg-sizes.index', compact('sizes'));
    }

    /**
     * Store a newly created egg size.
     */
    public function store(EggSizeStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $size = EggSize::create($validated);
        AuditLog::record('egg_size.created', $size, ['size_name' => $size->size_name]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $size,
                'message' => 'Egg size created successfully.',
            ]);
        }

        return back()->with('status', 'Egg size created successfully.');
    }

    /**
     * Update the specified egg size.
     */
    public function update(EggSizeUpdateRequest $request, EggSize $eggSize)
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        $eggSize->update($validated);
        AuditLog::record('egg_size.updated', $eggSize, ['size_name' => $eggSize->size_name]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $eggSize->fresh(),
                'message' => 'Egg size updated successfully.',
            ]);
        }

        return back()->with('status', 'Egg size updated successfully.');
    }

    /**
     * Remove the specified egg size. Blocked if size has prices, inventory, or transactions.
     */
    public function destroy(Request $request, EggSize $eggSize)
    {
        if ($eggSize->eggPrices()->exists()) {
            $message = 'Cannot delete this egg size because it has price records. Remove or reassign prices first.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['egg_size' => $message]);
        }
        if (Inventory::where('egg_size_id', $eggSize->id)->exists()) {
            $message = 'Cannot delete this egg size because it has inventory records. Clear or adjust inventory first.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['egg_size' => $message]);
        }
        if (StockIn::where('egg_size_id', $eggSize->id)->exists() || StockOut::where('egg_size_id', $eggSize->id)->exists()) {
            $message = 'Cannot delete this egg size because it has stock in or stock out records.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['egg_size' => $message]);
        }
        if (CrackedEgg::where('egg_size_id', $eggSize->id)->exists()) {
            $message = 'Cannot delete this egg size because it has cracked egg records.';
            return $request->wantsJson()
                ? response()->json(['success' => false, 'message' => $message], 422)
                : back()->withErrors(['egg_size' => $message]);
        }

        $name = $eggSize->size_name;
        $eggSize->delete();
        AuditLog::record('egg_size.deleted', null, ['size_name' => $name]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Egg size deleted successfully.',
            ]);
        }

        return back()->with('status', 'Egg size deleted successfully.');
    }
}
