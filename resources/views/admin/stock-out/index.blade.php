@extends('admin.layouts.app')

@section('title', 'Stock Out')
@section('header_title', 'Stock Out (Sales)')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl dark:bg-slate-900/50 dark:border dark:border-slate-700">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Stock Out</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Record sales and product movement.</p>
            </div>
            <button type="button" id="openStockOutModal" class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add sale
            </button>
        </div>

        <form method="get" action="{{ route('admin.stock-out.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-slate-700 dark:bg-slate-800/50">
            <div class="flex flex-wrap items-end gap-3">
                <input type="hidden" name="per_page" value="{{ $perPage ?? 20 }}">
                <div>
                    <label for="date_from" class="block text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">From date</label>
                    <input type="date" id="date_from" name="date_from" value="{{ request('date_from', $dateFrom ?? '') }}"
                           class="mt-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <div>
                    <label for="date_to" class="block text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">To date</label>
                    <input type="date" id="date_to" name="date_to" value="{{ request('date_to', $dateTo ?? '') }}"
                           class="mt-1 rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-xs text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                </div>
                <button type="submit" class="rounded-xl bg-gradient-to-r from-gold to-gold-soft px-4 py-1.5 text-xs font-semibold text-slate-900 shadow-sm hover:shadow">Search</button>
                @if(request('date_from') || request('date_to'))
                    <a href="{{ route('admin.stock-out.index', ['per_page' => $perPage ?? 20]) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Clear</a>
                @endif
            </div>
            <div class="ml-auto flex items-center gap-2 text-[0.7rem] text-slate-500 dark:text-slate-400">
                <span>Per page:</span>
                @foreach([10, 20, 50] as $n)
                    <a href="{{ route('admin.stock-out.index', array_merge(request()->query(), ['per_page' => $n])) }}"
                       class="rounded px-2 py-0.5 {{ ($perPage ?? 20) == $n ? 'bg-amber-100 font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200' : 'hover:bg-slate-200 dark:hover:bg-slate-700' }}">{{ $n }}</a>
                @endforeach
            </div>
        </form>

        <div class="mt-5 overflow-x-auto overflow-hidden rounded-2xl border border-slate-100 bg-white dark:border-slate-700 dark:bg-slate-900/50">
            <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                    <tr>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Time</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2 text-right">Qty</th>
                        <th class="px-4 py-2 text-right">Amount</th>
                        <th class="px-4 py-2">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOuts as $row)
                        <tr class="border-t border-slate-100 dark:border-slate-700">
                            <td class="px-4 py-2">{{ $row->transaction_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->transaction_date?->format('g:i A') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->customer_name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->order_type ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($row->quantity) }}</td>
                            <td class="px-4 py-2 text-right">₱{{ number_format($row->total_amount ?? 0, 2) }}</td>
                            <td class="px-4 py-2">
                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 text-[0.7rem] font-medium text-slate-600 dark:bg-slate-700 dark:text-slate-300">{{ $row->payment_status ?? '—' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No sales records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockOuts->hasPages())
            <div class="mt-4 flex justify-center">{{ $stockOuts->links() }}</div>
        @endif
    </section>

    <!-- Stock Out Modal -->
    <div id="stockOutModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="max-h-[90vh] w-full max-w-md overflow-y-auto rounded-3xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Add sale</h2>
                <button type="button" id="closeStockOutModal" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="stockOutForm" class="mt-4 space-y-3">
                <div>
                    <label for="stockOutDate" class="text-xs font-medium text-slate-700">Transaction date</label>
                    <input id="stockOutDate" type="date" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                </div>
                <div>
                    <label for="stockOutCustomer" class="text-xs font-medium text-slate-700">Customer name</label>
                    <input id="stockOutCustomer" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold" placeholder="Optional">
                </div>
                <div>
                    <label for="stockOutSize" class="text-xs font-medium text-slate-700">Egg size</label>
                    <select id="stockOutSize" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                        <option value="">Select size</option>
                        @foreach ($eggSizes as $size)
                            <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="stockOutOrderType" class="text-xs font-medium text-slate-700">Order type</label>
                    <select id="stockOutOrderType" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                        <option value="piece">Piece</option>
                        <option value="tray">Tray</option>
                        <option value="bulk">Bulk</option>
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="stockOutQty" class="text-xs font-medium text-slate-700">Quantity</label>
                        <input id="stockOutQty" type="number" min="1" step="1" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                    </div>
                    <div>
                        <label for="stockOutPrice" class="text-xs font-medium text-slate-700">Price used (₱)</label>
                        <input id="stockOutPrice" type="number" min="0" step="0.01" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="stockOutDiscount" class="text-xs font-medium text-slate-700">Discount (₱)</label>
                        <input id="stockOutDiscount" type="number" min="0" step="0.01" value="0" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold">
                    </div>
                    <div>
                        <label for="stockOutPaymentStatus" class="text-xs font-medium text-slate-700">Payment status</label>
                        <select id="stockOutPaymentStatus" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold">
                            <option value="unpaid">Unpaid</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="stockOutPaymentMethod" class="text-xs font-medium text-slate-700">Payment method</label>
                    <input id="stockOutPaymentMethod" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold" placeholder="e.g. Cash, GCash">
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelStockOut" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">Cancel</button>
                    <button type="submit" id="saveStockOut" class="ml-2 inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('stockOutModal');
    var openBtn = document.getElementById('openStockOutModal');
    var closeBtn = document.getElementById('closeStockOutModal');
    var cancelBtn = document.getElementById('cancelStockOut');
    var form = document.getElementById('stockOutForm');
    var sizeSelect = document.getElementById('stockOutSize');
    var orderTypeSelect = document.getElementById('stockOutOrderType');
    var priceInput = document.getElementById('stockOutPrice');

    var activePrices = @json($activePricesJson);

    function openModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        var dateEl = document.getElementById('stockOutDate');
        if (dateEl && !dateEl.value) {
            dateEl.value = new Date().toISOString().slice(0, 10);
        }
    }
    function closeModal() {
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    function applyDefaultPrice() {
        var sizeId = sizeSelect.value;
        var orderType = orderTypeSelect.value;
        if (!sizeId || !activePrices[sizeId]) return;
        var p = activePrices[sizeId];
        if (orderType === 'piece' && p.price_per_piece != null) priceInput.value = p.price_per_piece;
        else if (orderType === 'tray' && p.price_per_tray != null) priceInput.value = p.price_per_tray;
        else if (orderType === 'bulk' && p.price_bulk != null) priceInput.value = p.price_bulk;
        else if (p.price_per_piece != null) priceInput.value = p.price_per_piece;
    }

    if (sizeSelect) sizeSelect.addEventListener('change', applyDefaultPrice);
    if (orderTypeSelect) orderTypeSelect.addEventListener('change', applyDefaultPrice);

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var payload = {
                customer_name: document.getElementById('stockOutCustomer').value || null,
                order_type: document.getElementById('stockOutOrderType').value,
                egg_size_id: document.getElementById('stockOutSize').value,
                quantity: parseInt(document.getElementById('stockOutQty').value, 10) || 0,
                price_used: parseFloat(document.getElementById('stockOutPrice').value) || 0,
                discount: parseFloat(document.getElementById('stockOutDiscount').value) || 0,
                payment_status: document.getElementById('stockOutPaymentStatus').value,
                payment_method: document.getElementById('stockOutPaymentMethod').value || null,
                transaction_date: document.getElementById('stockOutDate').value,
            };
            if (!payload.egg_size_id || !payload.transaction_date || payload.quantity < 1 || payload.price_used < 0) {
                Swal.fire({ icon: 'warning', title: 'Missing information', text: 'Please fill date, size, quantity, and price.', confirmButtonColor: '#D4AF37' });
                return;
            }
            var saveBtn = document.getElementById('saveStockOut');
            try {
                if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
                await axios.post('{{ route('admin.stock-out.store') }}', payload);
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Sale recorded and inventory updated.', timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Unable to save. Please check your input.';
                Swal.fire({ icon: 'error', title: 'Save failed', text: msg, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
