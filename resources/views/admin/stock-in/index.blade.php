@extends('admin.layouts.app')

@section('title', 'Stock In')
@section('header_title', 'Stock In (Purchases)')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl dark:bg-slate-900/50 dark:border dark:border-slate-700">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Stock In</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Record incoming egg purchases and deliveries.</p>
            </div>
            <button type="button" id="openStockInModal" class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add purchase
            </button>
        </div>

        <form method="get" action="{{ route('admin.stock-in.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-slate-700 dark:bg-slate-800/50">
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
                    <a href="{{ route('admin.stock-in.index', ['per_page' => $perPage ?? 20]) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Clear</a>
                @endif
            </div>
            <div class="ml-auto flex items-center gap-2 text-[0.7rem] text-slate-500 dark:text-slate-400">
                <span>Per page:</span>
                @foreach([10, 20, 50] as $n)
                    <a href="{{ route('admin.stock-in.index', array_merge(request()->query(), ['per_page' => $n])) }}"
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
                        <th class="px-4 py-2">Supplier</th>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2 text-right">Pieces</th>
                        <th class="px-4 py-2 text-right">Trays</th>
                        <th class="px-4 py-2 text-right">Total cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockIns as $row)
                        @php $trays = $piecesPerTray > 0 ? (int) floor($row->quantity_pieces / $piecesPerTray) : 0; @endphp
                        <tr class="border-t border-slate-100 dark:border-slate-700">
                            <td class="px-4 py-2">{{ $row->delivery_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->created_at?->format('g:i A') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->supplier_name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($row->quantity_pieces) }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($trays) }}</td>
                            <td class="px-4 py-2 text-right">₱{{ number_format($row->total_cost ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No stock-in records yet. Use the button above to add a purchase.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($stockIns->hasPages())
            <div class="mt-4 flex justify-center">{{ $stockIns->links() }}</div>
        @endif
    </section>

    <!-- Stock In Modal -->
    <div id="stockInModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Add purchase</h2>
                <button id="closeStockInModal" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="stockInForm" class="mt-4 space-y-3">
                <div>
                    <label for="stockInDate" class="text-xs font-medium text-slate-700">Delivery date</label>
                    <input id="stockInDate" type="date"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold"
                           required>
                </div>
                <div>
                    <label for="stockInSupplier" class="text-xs font-medium text-slate-700">Supplier name</label>
                    <input id="stockInSupplier" type="text"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                           placeholder="Optional">
                </div>
                <div>
                    <label for="stockInSize" class="text-xs font-medium text-slate-700">Egg size</label>
                    <select id="stockInSize"
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold"
                            required>
                        <option value="">Select size</option>
                        @foreach ($eggSizes as $size)
                            <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>
                <p class="text-[0.7rem] text-slate-500">Pieces per tray: <strong>{{ $piecesPerTray }}</strong> (from settings)</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="stockInQty" class="text-xs font-medium text-slate-700">Quantity (pieces)</label>
                        <input id="stockInQty" type="number" min="0" step="1"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="0">
                    </div>
                    <div>
                        <label for="stockInTrays" class="text-xs font-medium text-slate-700">Quantity (trays)</label>
                        <input id="stockInTrays" type="number" min="0" step="1"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="0">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="stockInCostPerPiece" class="text-xs font-medium text-slate-700">Cost per piece (₱)</label>
                        <input id="stockInCostPerPiece" type="number" min="0" step="0.01"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="Optional">
                    </div>
                </div>
                <div>
                    <label for="stockInTotalCost" class="text-xs font-medium text-slate-700">Total cost (₱)</label>
                    <input id="stockInTotalCost" type="number" min="0" step="0.01"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                           placeholder="Auto-calculated when cost per piece is set">
                </div>
                <div>
                    <label for="stockInRemarks" class="text-xs font-medium text-slate-700">Remarks</label>
                    <input id="stockInRemarks" type="text"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                           placeholder="Optional notes">
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelStockIn"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">
                        Cancel
                    </button>
                    <button type="submit" id="saveStockIn"
                            class="ml-2 inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('stockInModal');
    const openBtn = document.getElementById('openStockInModal');
    const closeBtn = document.getElementById('closeStockInModal');
    const cancelBtn = document.getElementById('cancelStockIn');
    const form = document.getElementById('stockInForm');
    const qtyInput = document.getElementById('stockInQty');
    const traysInput = document.getElementById('stockInTrays');
    const costPerPieceInput = document.getElementById('stockInCostPerPiece');
    const totalCostInput = document.getElementById('stockInTotalCost');
    const piecesPerTray = {{ $piecesPerTray }};

    function openModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        var dateEl = document.getElementById('stockInDate');
        if (dateEl && !dateEl.value) {
            var today = new Date().toISOString().slice(0, 10);
            dateEl.value = today;
        }
    }

    function closeModal() {
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    function syncPiecesFromTrays() {
        var trays = parseInt(traysInput.value, 10) || 0;
        if (trays > 0 && piecesPerTray > 0) {
            qtyInput.value = trays * piecesPerTray;
        }
        recalcTotal();
    }
    function syncTraysFromPieces() {
        var pieces = parseInt(qtyInput.value, 10) || 0;
        if (pieces > 0 && piecesPerTray > 0) {
            traysInput.value = Math.floor(pieces / piecesPerTray);
        }
        recalcTotal();
    }
    function recalcTotal() {
        var qty = parseFloat(qtyInput.value) || 0;
        var cpp = parseFloat(costPerPieceInput.value) || 0;
        if (qty > 0 && cpp > 0) {
            totalCostInput.value = (qty * cpp).toFixed(2);
        }
    }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (qtyInput) qtyInput.addEventListener('input', syncTraysFromPieces);
    if (traysInput) traysInput.addEventListener('input', syncPiecesFromTrays);
    if (costPerPieceInput) costPerPieceInput.addEventListener('input', recalcTotal);

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var piecesVal = parseInt(qtyInput.value, 10) || 0;
            var traysVal = parseInt(traysInput.value, 10) || 0;
            if (traysVal > 0 && piecesVal <= 0) {
                piecesVal = traysVal * piecesPerTray;
            }

            const payload = {
                supplier_name: document.getElementById('stockInSupplier').value || null,
                delivery_date: document.getElementById('stockInDate').value,
                egg_size_id: document.getElementById('stockInSize').value,
                quantity_pieces: piecesVal > 0 ? piecesVal : null,
                quantity_trays: traysVal > 0 && piecesVal <= 0 ? traysVal : null,
                cost_per_piece: costPerPieceInput.value || null,
                total_cost: totalCostInput.value || null,
                remarks: document.getElementById('stockInRemarks').value || null,
            };

            if (!payload.delivery_date || !payload.egg_size_id) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing information',
                    text: 'Please fill delivery date and egg size.',
                    confirmButtonColor: '#D4AF37',
                });
                return;
            }
            if (!payload.quantity_pieces && !payload.quantity_trays) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Missing quantity',
                    text: 'Please enter quantity in pieces or trays.',
                    confirmButtonColor: '#D4AF37',
                });
                return;
            }

            const saveButton = document.getElementById('saveStockIn');

            try {
                if (saveButton) {
                    saveButton.disabled = true;
                    saveButton.classList.add('opacity-70', 'cursor-not-allowed');
                }

                await axios.post('{{ route('admin.stock-in.store') }}', payload);

                Swal.fire({
                    icon: 'success',
                    title: 'Saved',
                    text: 'Stock in recorded and inventory updated.',
                    timer: 1500,
                    showConfirmButton: false,
                });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (error) {
                let message = 'Unable to save this purchase. Please check your input.';
                if (error.response && error.response.data && error.response.data.message) {
                    message = error.response.data.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Save failed',
                    text: message,
                    confirmButtonColor: '#D4AF37',
                });
            } finally {
                if (saveButton) {
                    saveButton.disabled = false;
                    saveButton.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            }
        });
    }
});
</script>
@endpush
