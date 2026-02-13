<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FeedAdjustRequest;
use App\Http\Requests\Admin\FeedStoreRequest;
use App\Http\Requests\Admin\FeedUpdateRequest;
use App\Models\AuditLog;
use App\Models\Feed;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeedController extends Controller
{
    /**
     * Display list of feed inventory items.
     */
    public function index(Request $request): View
    {
        $perPage = in_array($request->input('per_page'), [10, 20, 50], true) ? (int) $request->input('per_page') : 20;
        $feeds = Feed::orderBy('name')->paginate($perPage)->withQueryString();
        $setting = Setting::first();
        $currency = $setting?->currency ?? 'PHP';

        return view('admin.feeds.index', ['feeds' => $feeds, 'currency' => $currency, 'perPage' => $perPage]);
    }

    /**
     * Store a new feed type.
     */
    public function store(FeedStoreRequest $request)
    {
        $validated = $request->validated();
        $validated['quantity'] = (float) ($validated['quantity'] ?? 0);
        $validated['unit'] = $validated['unit'] ?? 'bags';
        $validated['cost_per_unit'] = isset($validated['cost_per_unit']) ? (float) $validated['cost_per_unit'] : null;
        $validated['minimum_stock_alert'] = (float) ($validated['minimum_stock_alert'] ?? 0);
        $validated['last_updated'] = now();

        $feed = Feed::create($validated);
        AuditLog::record('feed.created', $feed, [
            'name' => $feed->name,
            'quantity' => $feed->quantity,
            'unit' => $feed->unit,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $feed,
                'message' => 'Feed added successfully.',
            ]);
        }

        return back()->with('status', 'Feed added successfully.');
    }

    /**
     * Update feed details (name, unit, min alert, remarks).
     */
    public function update(FeedUpdateRequest $request, Feed $feed)
    {
        $validated = $request->validated();
        $validated['unit'] = $validated['unit'] ?? 'bags';
        $validated['cost_per_unit'] = isset($validated['cost_per_unit']) ? (float) $validated['cost_per_unit'] : null;
        $validated['minimum_stock_alert'] = (float) ($validated['minimum_stock_alert'] ?? 0);

        $feed->update($validated);
        AuditLog::record('feed.updated', $feed, [
            'name' => $feed->name,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $feed->fresh(),
                'message' => 'Feed updated successfully.',
            ]);
        }

        return back()->with('status', 'Feed updated successfully.');
    }

    /**
     * Adjust feed stock (add or subtract quantity).
     */
    public function adjust(FeedAdjustRequest $request, Feed $feed)
    {
        $validated = $request->validated();
        $delta = (float) $validated['quantity_delta'];
        $newQuantity = $feed->quantity + $delta;

        $feed->update([
            'quantity' => $newQuantity,
            'last_updated' => now(),
        ]);

        AuditLog::record('feed.adjusted', $feed, [
            'name' => $feed->name,
            'quantity_delta' => $delta,
            'new_quantity' => $feed->quantity,
            'note' => $validated['note'] ?? null,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $feed->fresh(),
                'message' => 'Feed stock updated.',
            ]);
        }

        return back()->with('status', 'Feed stock updated.');
    }

    /**
     * Remove a feed.
     */
    public function destroy(Request $request, Feed $feed)
    {
        $name = $feed->name;
        $feed->delete();
        AuditLog::record('feed.deleted', null, ['name' => $name]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feed removed successfully.',
            ]);
        }

        return back()->with('status', 'Feed removed successfully.');
    }
}
