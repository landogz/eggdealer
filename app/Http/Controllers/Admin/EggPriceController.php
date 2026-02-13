<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EggPriceStoreRequest;
use App\Http\Requests\Admin\EggPriceUpdateRequest;
use App\Models\AuditLog;
use App\Models\EggPrice;
use App\Models\EggSize;
use Illuminate\Http\Request;

class EggPriceController extends Controller
{
    public function index()
    {
        $sizes = EggSize::orderBy('size_name')->get();

        $prices = EggPrice::with('eggSize')
            ->orderByDesc('effective_date')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.egg-prices.index', compact('sizes', 'prices'));
    }

    public function store(EggPriceStoreRequest $request)
    {
        $validated = $request->validated();
        $price = EggPrice::create($validated);
        $price->load('eggSize');
        AuditLog::record('egg_price.created', $price, [
            'egg_size_id' => $price->egg_size_id,
            'effective_date' => $price->effective_date?->toDateString(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $price->load('eggSize'),
                'message' => 'Price entry created successfully.',
            ]);
        }

        return back()->with('status', 'Price entry created successfully.');
    }

    /**
     * Update the specified price entry.
     */
    public function update(EggPriceUpdateRequest $request, EggPrice $eggPrice)
    {
        $validated = $request->validated();
        $eggPrice->update($validated);
        AuditLog::record('egg_price.updated', $eggPrice, [
            'egg_size_id' => $eggPrice->egg_size_id,
            'effective_date' => $eggPrice->effective_date?->toDateString(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $eggPrice->fresh()->load('eggSize'),
                'message' => 'Price entry updated successfully.',
            ]);
        }

        return back()->with('status', 'Price entry updated successfully.');
    }

    /**
     * Remove the specified price entry.
     */
    public function destroy(Request $request, EggPrice $eggPrice)
    {
        $eggPrice->load('eggSize');
        $info = ['egg_size' => $eggPrice->eggSize?->size_name, 'effective_date' => $eggPrice->effective_date?->toDateString()];
        $eggPrice->delete();
        AuditLog::record('egg_price.deleted', null, $info);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Price entry deleted successfully.',
            ]);
        }

        return back()->with('status', 'Price entry deleted successfully.');
    }
}
