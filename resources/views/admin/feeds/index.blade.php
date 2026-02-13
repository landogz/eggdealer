@extends('admin.layouts.app')

@section('title', 'Feeds')
@section('header_title', 'Feeds')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl dark:bg-slate-900/50 dark:border dark:border-slate-700">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Feed Inventory</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Track poultry feed stock, pricing (cost per unit), and expenses. Set low-stock alerts.</p>
            </div>
            <button type="button" id="openCreateModal"
                    class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add feed
            </button>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-900/50">
            <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-2">Name</th>
                        <th class="px-4 py-2 text-right">Quantity</th>
                        <th class="px-4 py-2">Unit</th>
                        <th class="px-4 py-2 text-right">Cost/unit</th>
                        <th class="px-4 py-2 text-right">Stock value</th>
                        <th class="px-4 py-2 text-right">Min. alert</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Remarks</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($feeds as $feed)
                        <tr class="border-t border-slate-100 dark:border-slate-700">
                            <td class="px-4 py-2 font-semibold text-slate-900 dark:text-slate-50">{{ $feed->name }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($feed->quantity, 2) }}</td>
                            <td class="px-4 py-2">{{ $feed->unit }}</td>
                            <td class="px-4 py-2 text-right">{{ $feed->cost_per_unit !== null ? $currency . ' ' . number_format($feed->cost_per_unit, 2) : '—' }}</td>
                            <td class="px-4 py-2 text-right font-medium text-slate-800 dark:text-slate-100">{{ $feed->stock_value !== null ? $currency . ' ' . number_format($feed->stock_value, 2) : '—' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($feed->minimum_stock_alert, 2) }}</td>
                            <td class="px-4 py-2">
                                @if($feed->isLowStock())
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[0.7rem] font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">Low stock</span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[0.7rem] font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-200">OK</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-slate-500 dark:text-slate-400">{{ Str::limit($feed->remarks, 30) ?: '—' }}</td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" class="adjustFeed rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-amber-400 hover:text-amber-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:border-amber-400 dark:hover:text-amber-300"
                                        data-id="{{ $feed->id }}" data-name="{{ $feed->name }}" data-quantity="{{ $feed->quantity }}" data-unit="{{ $feed->unit }}">
                                    Adjust
                                </button>
                                <button type="button" class="editFeed rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-gold hover:text-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                        data-id="{{ $feed->id }}" data-name="{{ $feed->name }}" data-unit="{{ $feed->unit }}" data-cost="{{ $feed->cost_per_unit ?? '' }}" data-min="{{ $feed->minimum_stock_alert }}" data-remarks="{{ $feed->remarks ?? '' }}">
                                    Edit
                                </button>
                                <button type="button" class="deleteFeed ml-1 rounded-full border border-red-100 bg-white px-3 py-1 text-[0.7rem] font-medium text-red-600 shadow-sm hover:border-red-300 dark:border-slate-600 dark:bg-slate-800 dark:text-red-400"
                                        data-id="{{ $feed->id }}" data-name="{{ $feed->name }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No feeds added yet. Use the button above to add a feed type.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($feeds->hasPages())
            <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-[0.7rem] text-slate-500 dark:text-slate-400">
                    <span>Per page:</span>
                    @foreach([10, 20, 50] as $n)
                        <a href="{{ route('admin.feeds.index', array_merge(request()->query(), ['per_page' => $n])) }}"
                           class="rounded px-2 py-0.5 {{ ($perPage ?? 20) == $n ? 'bg-amber-100 font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : 'hover:bg-slate-200 dark:hover:bg-slate-700' }}">{{ $n }}</a>
                    @endforeach
                </div>
                <div>{{ $feeds->links() }}</div>
            </div>
        @endif
    </section>

    <!-- Add / Edit Feed Modal -->
    <div id="feedModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl dark:bg-slate-900 dark:text-slate-100">
            <div class="flex items-center justify-between">
                <h2 id="feedModalTitle" class="text-sm font-semibold text-slate-900 dark:text-slate-50">Add feed</h2>
                <button type="button" id="closeFeedModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-lg leading-none">&times;</button>
            </div>
            <form id="feedForm" class="mt-4 space-y-3">
                <input type="hidden" id="feedId">
                <div>
                    <label for="feedName" class="text-xs font-medium text-slate-700 dark:text-slate-300">Name</label>
                    <input id="feedName" type="text" required maxlength="100"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           placeholder="e.g. Layer feed, Starter mash">
                </div>
                <div id="initialQtyWrap">
                    <label for="feedQuantity" class="text-xs font-medium text-slate-700 dark:text-slate-300">Initial quantity</label>
                    <input id="feedQuantity" type="number" min="0" step="0.01" value="0"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label for="feedUnit" class="text-xs font-medium text-slate-700 dark:text-slate-300">Unit</label>
                    <select id="feedUnit" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                        <option value="bags">Bags</option>
                        <option value="sacks">Sacks</option>
                        <option value="kg">kg</option>
                        <option value="tons">Tons</option>
                    </select>
                </div>
                <div>
                    <label for="feedCostPerUnit" class="text-xs font-medium text-slate-700 dark:text-slate-300">Cost per unit (for expenses)</label>
                    <input id="feedCostPerUnit" type="number" min="0" step="0.01"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           placeholder="e.g. 850.00">
                    <p class="mt-0.5 text-[0.65rem] text-slate-500 dark:text-slate-400">Leave blank if not tracked. Used for stock value &amp; expenses.</p>
                </div>
                <div>
                    <label for="feedMinAlert" class="text-xs font-medium text-slate-700 dark:text-slate-300">Minimum stock alert</label>
                    <input id="feedMinAlert" type="number" min="0" step="0.01" value="0"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label for="feedRemarks" class="text-xs font-medium text-slate-700 dark:text-slate-300">Remarks</label>
                    <input id="feedRemarks" type="text" maxlength="255"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           placeholder="Optional">
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelFeed" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800">Cancel</button>
                    <button type="submit" id="saveFeed" class="ml-2 rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Adjust Stock Modal -->
    <div id="adjustModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl dark:bg-slate-900 dark:text-slate-100">
            <div class="flex items-center justify-between">
                <h2 id="adjustModalTitle" class="text-sm font-semibold text-slate-900 dark:text-slate-50">Adjust stock</h2>
                <button type="button" id="closeAdjustModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-lg leading-none">&times;</button>
            </div>
            <p id="adjustCurrentQty" class="mt-2 text-xs text-slate-500 dark:text-slate-400"></p>
            <form id="adjustForm" class="mt-4 space-y-3">
                <input type="hidden" id="adjustFeedId">
                <div>
                    <label for="quantityDelta" class="text-xs font-medium text-slate-700 dark:text-slate-300">Change amount</label>
                    <input id="quantityDelta" type="number" step="0.01" required
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           placeholder="e.g. 10 to add, -5 to subtract">
                    <p class="mt-1 text-[0.65rem] text-slate-500 dark:text-slate-400">Use positive to add stock, negative to subtract.</p>
                </div>
                <div>
                    <label for="adjustNote" class="text-xs font-medium text-slate-700 dark:text-slate-300">Note (optional)</label>
                    <input id="adjustNote" type="text" maxlength="255"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           placeholder="e.g. New delivery, Used for flock">
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelAdjust" class="rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300 dark:border-slate-600 dark:bg-slate-800">Cancel</button>
                    <button type="submit" id="saveAdjust" class="ml-2 rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Update stock</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var feedModal = document.getElementById('feedModal');
    var feedModalTitle = document.getElementById('feedModalTitle');
    var feedId = document.getElementById('feedId');
    var feedForm = document.getElementById('feedForm');
    var initialQtyWrap = document.getElementById('initialQtyWrap');
    var adjustModal = document.getElementById('adjustModal');
    var adjustModalTitle = document.getElementById('adjustModalTitle');
    var adjustCurrentQty = document.getElementById('adjustCurrentQty');
    var adjustForm = document.getElementById('adjustForm');
    var adjustFeedId = document.getElementById('adjustFeedId');

    function openFeedModal(mode, data) {
        feedModalTitle.textContent = mode === 'create' ? 'Add feed' : 'Edit feed';
        feedId.value = data && data.id ? data.id : '';
        document.getElementById('feedName').value = (data && data.name) ? data.name : '';
        document.getElementById('feedQuantity').value = (data && data.quantity != null) ? data.quantity : '0';
        document.getElementById('feedUnit').value = (data && data.unit) ? data.unit : 'bags';
        var costEl = document.getElementById('feedCostPerUnit');
        if (costEl) costEl.value = (data && data.cost !== undefined && data.cost !== '') ? data.cost : '';
        document.getElementById('feedMinAlert').value = (data && data.min != null) ? data.min : '0';
        document.getElementById('feedRemarks').value = (data && data.remarks) ? data.remarks : '';
        initialQtyWrap.style.display = mode === 'create' ? 'block' : 'none';
        feedModal.classList.remove('hidden');
        feedModal.classList.add('flex');
    }
    function closeFeedModal() {
        feedModal.classList.add('hidden');
        feedModal.classList.remove('flex');
    }

    function openAdjustModal(data) {
        adjustModalTitle.textContent = 'Adjust stock: ' + (data.name || '');
        adjustCurrentQty.textContent = 'Current: ' + (data.quantity ?? 0) + ' ' + (data.unit || '');
        adjustFeedId.value = data.id || '';
        document.getElementById('quantityDelta').value = '';
        document.getElementById('adjustNote').value = '';
        adjustModal.classList.remove('hidden');
        adjustModal.classList.add('flex');
    }
    function closeAdjustModal() {
        adjustModal.classList.add('hidden');
        adjustModal.classList.remove('flex');
    }

    document.getElementById('openCreateModal').addEventListener('click', function () { openFeedModal('create', null); });
    document.getElementById('closeFeedModal').addEventListener('click', closeFeedModal);
    document.getElementById('cancelFeed').addEventListener('click', closeFeedModal);
    document.getElementById('closeAdjustModal').addEventListener('click', closeAdjustModal);
    document.getElementById('cancelAdjust').addEventListener('click', closeAdjustModal);

    document.querySelectorAll('.editFeed').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openFeedModal('edit', {
                id: btn.dataset.id,
                name: btn.dataset.name,
                unit: btn.dataset.unit,
                cost: btn.dataset.cost !== undefined ? btn.dataset.cost : '',
                min: btn.dataset.min,
                remarks: btn.dataset.remarks || '',
            });
        });
    });

    document.querySelectorAll('.adjustFeed').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openAdjustModal({
                id: btn.dataset.id,
                name: btn.dataset.name,
                quantity: btn.dataset.quantity,
                unit: btn.dataset.unit,
            });
        });
    });

    document.querySelectorAll('.deleteFeed').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = btn.dataset.id;
            var name = btn.dataset.name;
            Swal.fire({
                icon: 'warning',
                title: 'Delete feed?',
                text: 'Are you sure you want to remove "' + name + '"?',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Yes, delete',
            }).then(function (result) {
                if (!result.isConfirmed) return;
                axios.delete('{{ url('admin/feeds') }}/' + id)
                    .then(function () {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'Feed removed.', timer: 1500, showConfirmButton: false });
                        setTimeout(function () { window.location.reload(); }, 1500);
                    })
                    .catch(function (err) {
                        var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Could not delete.';
                        Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
                    });
            });
        });
    });

    feedForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        var id = feedId.value;
        var costVal = document.getElementById('feedCostPerUnit').value.trim();
        var payload = {
            name: document.getElementById('feedName').value.trim(),
            unit: document.getElementById('feedUnit').value,
            cost_per_unit: costVal === '' ? null : parseFloat(costVal),
            minimum_stock_alert: parseFloat(document.getElementById('feedMinAlert').value) || 0,
            remarks: document.getElementById('feedRemarks').value.trim() || null,
        };
        if (!payload.name) {
            Swal.fire({ icon: 'warning', title: 'Missing name', text: 'Please enter a feed name.', confirmButtonColor: '#D4AF37' });
            return;
        }
        if (!id) {
            payload.quantity = parseFloat(document.getElementById('feedQuantity').value) || 0;
        }
        var saveBtn = document.getElementById('saveFeed');
        try {
            if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
            if (id) {
                await axios.put('{{ url('admin/feeds') }}/' + id, payload);
            } else {
                await axios.post('{{ route('admin.feeds.store') }}', payload);
            }
            Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'Feed updated.' : 'Feed added.', timer: 1500, showConfirmButton: false });
            closeFeedModal();
            setTimeout(function () { window.location.reload(); }, 1500);
        } catch (err) {
            var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Please check the form.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
        } finally {
            if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
        }
    });

    adjustForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        var id = adjustFeedId.value;
        var delta = parseFloat(document.getElementById('quantityDelta').value);
        if (isNaN(delta)) {
            Swal.fire({ icon: 'warning', title: 'Invalid amount', text: 'Enter a number (e.g. 10 or -5).', confirmButtonColor: '#D4AF37' });
            return;
        }
        var payload = { quantity_delta: delta, note: document.getElementById('adjustNote').value.trim() || null };
        var saveBtn = document.getElementById('saveAdjust');
        try {
            if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
            await axios.post('{{ url('admin/feeds') }}/' + id + '/adjust', payload);
            Swal.fire({ icon: 'success', title: 'Updated', text: 'Feed stock updated.', timer: 1500, showConfirmButton: false });
            closeAdjustModal();
            setTimeout(function () { window.location.reload(); }, 1500);
        } catch (err) {
            var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Could not update stock.';
            Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
        } finally {
            if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
        }
    });
});
</script>
@endpush
