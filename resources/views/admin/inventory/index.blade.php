@extends('admin.layouts.app')

@section('title', 'Inventory')
@section('header_title', 'Inventory')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Inventory</h1>
                <p class="text-xs text-slate-500">Current stock by size and low-stock alerts.</p>
            </div>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-100 bg-white">
            <table class="min-w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2 text-right">Pieces</th>
                        <th class="px-4 py-2 text-right">Trays</th>
                        <th class="px-4 py-2 text-right">Min alert</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Last updated</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $inv)
                        @php
                            $isLow = $inv->minimum_stock_alert > 0 && $inv->current_stock_pieces <= $inv->minimum_stock_alert;
                        @endphp
                        <tr class="border-t border-slate-100 {{ $isLow ? 'bg-amber-50/70' : '' }}">
                            <td class="px-4 py-2 font-medium text-slate-900">{{ $inv->eggSize->size_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($inv->current_stock_pieces) }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($inv->current_stock_trays) }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($inv->minimum_stock_alert) }}</td>
                            <td class="px-4 py-2">
                                @if($isLow)
                                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-[0.7rem] font-semibold text-amber-800">Low stock</span>
                                @else
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[0.7rem] font-semibold text-emerald-700">OK</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right text-slate-500">{{ $inv->last_updated?->format('M d, Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" class="setAlertBtn inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-gold hover:text-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200"
                                        data-id="{{ $inv->id }}"
                                        data-size="{{ $inv->eggSize->size_name ?? '—' }}"
                                        data-current="{{ $inv->minimum_stock_alert }}">
                                    Set alert
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-slate-500">No inventory records yet. Stock is updated when you add Stock In / Stock Out.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Set Min Alert Modal -->
    <div id="alertModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-sm rounded-3xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 id="alertModalTitle" class="text-sm font-semibold text-slate-900">Set minimum stock alert</h2>
                <button type="button" id="closeAlertModal" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="alertForm" class="mt-4 space-y-3">
                <input type="hidden" id="alertInventoryId" name="inventory_id" value="">
                <div>
                    <label for="alertMin" class="text-xs font-medium text-slate-700">Minimum pieces (alert when stock is at or below)</label>
                    <input id="alertMin" type="number" min="0" step="1" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelAlert" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">Cancel</button>
                    <button type="submit" id="saveAlert" class="ml-2 inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('alertModal');
    var openBtns = document.querySelectorAll('.setAlertBtn');
    var closeBtn = document.getElementById('closeAlertModal');
    var cancelBtn = document.getElementById('cancelAlert');
    var form = document.getElementById('alertForm');
    var idInput = document.getElementById('alertInventoryId');
    var titleEl = document.getElementById('alertModalTitle');
    var minInput = document.getElementById('alertMin');

    function openModal(id, sizeName, current) {
        if (!modal) return;
        idInput.value = id;
        titleEl.textContent = 'Set minimum stock alert — ' + sizeName;
        minInput.value = current || 0;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeModal() {
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    openBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            openModal(btn.dataset.id, btn.dataset.size, btn.dataset.current);
        });
    });
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var id = idInput.value;
            var payload = { minimum_stock_alert: parseInt(minInput.value, 10) || 0 };
            if (payload.minimum_stock_alert < 0) payload.minimum_stock_alert = 0;
            var saveBtn = document.getElementById('saveAlert');
            try {
                if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
                await axios.put('{{ url('admin/inventory') }}/' + id, payload);
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Minimum stock alert updated.', timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Unable to save.';
                Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
