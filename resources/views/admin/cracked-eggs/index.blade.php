@extends('admin.layouts.app')

@section('title', 'Cracked Eggs')
@section('header_title', 'Cracked Eggs')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl dark:bg-slate-900/50 dark:border dark:border-slate-700">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Cracked Eggs</h1>
                <p class="text-xs text-slate-500 dark:text-slate-400">Log damaged or cracked eggs by size and reason.</p>
            </div>
            <button type="button" id="openCrackedModal" class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Record cracked
            </button>
        </div>

        <form method="get" action="{{ route('admin.cracked-eggs.index') }}" class="mt-4 flex flex-wrap items-end gap-3 rounded-2xl border border-slate-200 bg-slate-50/80 p-3 dark:border-slate-700 dark:bg-slate-800/50">
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
                    <a href="{{ route('admin.cracked-eggs.index', ['per_page' => $perPage ?? 20]) }}" class="rounded-xl border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">Clear</a>
                @endif
            </div>
            <div class="ml-auto flex items-center gap-2 text-[0.7rem] text-slate-500 dark:text-slate-400">
                <span>Per page:</span>
                @foreach([10, 20, 50] as $n)
                    <a href="{{ route('admin.cracked-eggs.index', array_merge(request()->query(), ['per_page' => $n])) }}"
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
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2 text-right">Qty cracked</th>
                        <th class="px-4 py-2">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $row)
                        <tr class="border-t border-slate-100 dark:border-slate-700">
                            <td class="px-4 py-2">{{ $row->date_recorded?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->created_at?->format('g:i A') ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                            <td class="px-4 py-2 text-right">{{ number_format($row->quantity_cracked) }}</td>
                            <td class="px-4 py-2">{{ $row->reason ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-slate-500 dark:text-slate-400">No cracked egg records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
            <div class="mt-4 flex justify-center">{{ $records->links() }}</div>
        @endif
    </section>

    <!-- Cracked Egg Modal -->
    <div id="crackedModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-semibold text-slate-900">Record cracked eggs</h2>
                <button type="button" id="closeCrackedModal" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="crackedForm" class="mt-4 space-y-3">
                <div>
                    <label for="crackedDate" class="text-xs font-medium text-slate-700">Date recorded</label>
                    <input id="crackedDate" type="date" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                </div>
                <div>
                    <label for="crackedSize" class="text-xs font-medium text-slate-700">Egg size</label>
                    <select id="crackedSize" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                        <option value="">Select size</option>
                        @foreach ($eggSizes as $size)
                            <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="crackedQty" class="text-xs font-medium text-slate-700">Quantity cracked (pieces)</label>
                    <input id="crackedQty" type="number" min="1" step="1" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold" required>
                </div>
                <div>
                    <label for="crackedReason" class="text-xs font-medium text-slate-700">Reason</label>
                    <input id="crackedReason" type="text" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold" placeholder="e.g. Transport damage, Quality">
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelCracked" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">Cancel</button>
                    <button type="submit" id="saveCracked" class="ml-2 inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-1.5 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('crackedModal');
    var openBtn = document.getElementById('openCrackedModal');
    var closeBtn = document.getElementById('closeCrackedModal');
    var cancelBtn = document.getElementById('cancelCracked');
    var form = document.getElementById('crackedForm');

    function openModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        var dateEl = document.getElementById('crackedDate');
        if (dateEl && !dateEl.value) {
            dateEl.value = new Date().toISOString().slice(0, 10);
        }
    }
    function closeModal() {
        if (modal) { modal.classList.add('hidden'); modal.classList.remove('flex'); }
    }

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var payload = {
                egg_size_id: document.getElementById('crackedSize').value,
                quantity_cracked: parseInt(document.getElementById('crackedQty').value, 10) || 0,
                reason: document.getElementById('crackedReason').value || null,
                date_recorded: document.getElementById('crackedDate').value,
            };
            if (!payload.egg_size_id || !payload.date_recorded || payload.quantity_cracked < 1) {
                Swal.fire({ icon: 'warning', title: 'Missing information', text: 'Please fill date, size, and quantity.', confirmButtonColor: '#D4AF37' });
                return;
            }
            var saveBtn = document.getElementById('saveCracked');
            try {
                if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
                await axios.post('{{ route('admin.cracked-eggs.store') }}', payload);
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Cracked eggs recorded and inventory updated.', timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Unable to save. Check quantity does not exceed available stock.';
                Swal.fire({ icon: 'error', title: 'Save failed', text: msg, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
