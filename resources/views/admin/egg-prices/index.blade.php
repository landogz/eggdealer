@extends('admin.layouts.app')

@section('title', 'Egg Pricing')
@section('header_title', 'Egg Pricing')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Egg Pricing Management</h1>
                <p class="text-xs text-slate-500">
                    Manage per‑size pricing for piece, tray, bulk, wholesale, and reseller with full history.
                </p>
            </div>
            <button id="openPriceModal"
                    class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add price entry
            </button>
        </div>

        <div class="mt-5 overflow-x-auto overflow-hidden rounded-2xl border border-slate-100 bg-white">
            <table class="min-w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2">Piece</th>
                        <th class="px-4 py-2">Tray (30)</th>
                        <th class="px-4 py-2">Bulk</th>
                        <th class="px-4 py-2">Wholesale</th>
                        <th class="px-4 py-2">Reseller</th>
                        <th class="px-4 py-2">Effective</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($prices as $price)
                        <tr class="border-t border-slate-100 text-xs edit-price-row"
                            data-id="{{ $price->id }}"
                            data-egg-size-id="{{ $price->egg_size_id }}"
                            data-price-per-piece="{{ $price->price_per_piece ?? '' }}"
                            data-price-per-tray="{{ $price->price_per_tray ?? '' }}"
                            data-price-bulk="{{ $price->price_bulk ?? '' }}"
                            data-wholesale-price="{{ $price->wholesale_price ?? '' }}"
                            data-reseller-price="{{ $price->reseller_price ?? '' }}"
                            data-effective-date="{{ optional($price->effective_date)->format('Y-m-d') }}"
                            data-status="{{ $price->status ?? 'active' }}">
                            <td class="px-4 py-2 font-semibold text-slate-900">{{ $price->eggSize->size_name ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $price->price_per_piece !== null ? number_format($price->price_per_piece, 2) : '—' }}</td>
                            <td class="px-4 py-2">{{ $price->price_per_tray !== null ? number_format($price->price_per_tray, 2) : '—' }}</td>
                            <td class="px-4 py-2">{{ $price->price_bulk !== null ? number_format($price->price_bulk, 2) : '—' }}</td>
                            <td class="px-4 py-2">{{ $price->wholesale_price !== null ? number_format($price->wholesale_price, 2) : '—' }}</td>
                            <td class="px-4 py-2">{{ $price->reseller_price !== null ? number_format($price->reseller_price, 2) : '—' }}</td>
                            <td class="px-4 py-2">{{ optional($price->effective_date)->format('Y-m-d') }}</td>
                            <td class="px-4 py-2">
                                @php $status = $price->status; @endphp
                                @if ($status === 'active')
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[0.7rem] font-semibold text-emerald-700">Active</span>
                                @elseif ($status === 'scheduled')
                                    <span class="inline-flex rounded-full bg-yellow-50 px-2.5 py-0.5 text-[0.7rem] font-semibold text-yellow-700">Scheduled</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-[0.7rem] font-semibold text-slate-500">{{ ucfirst($status ?? '') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right">
                                <button type="button" class="editPriceBtn inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-gold hover:text-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                    Edit
                                </button>
                                <button type="button" class="deletePriceBtn ml-1.5 inline-flex items-center rounded-full border border-red-100 bg-white px-3 py-1 text-[0.7rem] font-medium text-red-600 shadow-sm hover:border-red-300 dark:border-slate-600 dark:bg-slate-800 dark:text-red-400"
                                        data-id="{{ $price->id }}"
                                        data-label="{{ $price->eggSize->size_name ?? '—' }} ({{ optional($price->effective_date)->format('Y-m-d') }})">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-center text-xs text-slate-500">No pricing entries yet. Add one to get started.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Create / Edit Price Modal -->
    <div id="priceModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl dark:bg-slate-900 dark:text-slate-50">
            <div class="flex items-center justify-between">
                <h2 id="priceModalTitle" class="text-sm font-semibold text-slate-900 dark:text-slate-50">Add price entry</h2>
                <button id="closePriceModal" type="button" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 text-lg leading-none">&times;</button>
            </div>
            <form id="priceForm" class="mt-4 space-y-3">
                <input type="hidden" id="priceId" name="price_id" value="">
                <div>
                    <label for="priceSize" class="text-xs font-medium text-slate-700">Egg size</label>
                    <select id="priceSize"
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold"
                            required>
                        <option value="">Select size</option>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->size_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium text-slate-700">Price per piece</label>
                        <input id="pricePerPiece" type="number" step="0.01" min="0"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="e.g. 7.00">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-700">Price per tray (30)</label>
                        <input id="pricePerTray" type="number" step="0.01" min="0"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="e.g. 210.00">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium text-slate-700">Bulk price</label>
                        <input id="priceBulk" type="number" step="0.01" min="0"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="Optional">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-700">Wholesale price</label>
                        <input id="wholesalePrice" type="number" step="0.01" min="0"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="Optional">
                    </div>
                </div>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium text-slate-700">Reseller price</label>
                        <input id="resellerPrice" type="number" step="0.01" min="0"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                               placeholder="Optional">
                    </div>
                    <div>
                        <label class="text-xs font-medium text-slate-700">Effective date</label>
                        <input id="effectiveDate" type="date"
                               class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold"
                               required>
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium text-slate-700">Status</label>
                    <select id="priceStatus"
                            class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold"
                            required>
                        <option value="active">Active</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelPrice"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">
                        Cancel
                    </button>
                    <button type="submit" id="savePrice"
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
    var modal = document.getElementById('priceModal');
    var modalTitle = document.getElementById('priceModalTitle');
    var openBtn = document.getElementById('openPriceModal');
    var closeBtn = document.getElementById('closePriceModal');
    var cancelBtn = document.getElementById('cancelPrice');
    var form = document.getElementById('priceForm');
    var priceIdInput = document.getElementById('priceId');

    function showModal(mode, row) {
        if (mode === 'create') {
            modalTitle.textContent = 'Add price entry';
            priceIdInput.value = '';
            document.getElementById('priceSize').value = '';
            document.getElementById('pricePerPiece').value = '';
            document.getElementById('pricePerTray').value = '';
            document.getElementById('priceBulk').value = '';
            document.getElementById('wholesalePrice').value = '';
            document.getElementById('resellerPrice').value = '';
            document.getElementById('effectiveDate').value = '';
            document.getElementById('priceStatus').value = 'active';
        } else if (mode === 'edit' && row) {
            modalTitle.textContent = 'Edit price entry';
            priceIdInput.value = row.dataset.id || '';
            document.getElementById('priceSize').value = row.dataset.eggSizeId || '';
            document.getElementById('pricePerPiece').value = row.dataset.pricePerPiece || '';
            document.getElementById('pricePerTray').value = row.dataset.pricePerTray || '';
            document.getElementById('priceBulk').value = row.dataset.priceBulk || '';
            document.getElementById('wholesalePrice').value = row.dataset.wholesalePrice || '';
            document.getElementById('resellerPrice').value = row.dataset.resellerPrice || '';
            document.getElementById('effectiveDate').value = row.dataset.effectiveDate || '';
            document.getElementById('priceStatus').value = row.dataset.status || 'active';
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function hideModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    if (openBtn) openBtn.addEventListener('click', function () { showModal('create'); });
    if (closeBtn) closeBtn.addEventListener('click', hideModal);
    if (cancelBtn) cancelBtn.addEventListener('click', hideModal);

    document.querySelectorAll('.editPriceBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var row = this.closest('.edit-price-row');
            if (row) showModal('edit', row);
        });
    });

    document.querySelectorAll('.deletePriceBtn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.dataset.id;
            var label = this.dataset.label || 'this entry';
            Swal.fire({
                icon: 'warning',
                title: 'Delete price entry?',
                text: 'Are you sure you want to delete ' + label + '?',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Yes, delete it',
            }).then(function (result) {
                if (!result.isConfirmed) return;
                axios.delete('{{ url('admin/egg-prices') }}/' + id)
                    .then(function () {
                        Swal.fire({ icon: 'success', title: 'Deleted', text: 'Price entry deleted successfully.', timer: 1500, showConfirmButton: false });
                        setTimeout(function () { window.location.reload(); }, 1500);
                    })
                    .catch(function (error) {
                        var msg = (error.response && error.response.data && error.response.data.message) ? error.response.data.message : 'Unable to delete. Please try again.';
                        Swal.fire({ icon: 'error', title: 'Delete failed', text: msg, confirmButtonColor: '#D4AF37' });
                    });
            });
        });
    });

    if (form) {
        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            var id = priceIdInput.value;
            var egg_size_id = document.getElementById('priceSize').value;
            var price_per_piece = document.getElementById('pricePerPiece').value || null;
            var price_per_tray = document.getElementById('pricePerTray').value || null;
            var price_bulk = document.getElementById('priceBulk').value || null;
            var wholesale_price = document.getElementById('wholesalePrice').value || null;
            var reseller_price = document.getElementById('resellerPrice').value || null;
            var effective_date = document.getElementById('effectiveDate').value;
            var status = document.getElementById('priceStatus').value;

            if (!egg_size_id || !effective_date) {
                Swal.fire({ icon: 'warning', title: 'Missing information', text: 'Please select a size and set an effective date.', confirmButtonColor: '#D4AF37' });
                return;
            }

            var payload = {
                egg_size_id: egg_size_id,
                price_per_piece: price_per_piece,
                price_per_tray: price_per_tray,
                price_bulk: price_bulk,
                wholesale_price: wholesale_price,
                reseller_price: reseller_price,
                effective_date: effective_date,
                status: status,
            };

            var saveButton = document.getElementById('savePrice');
            var url = '{{ route('admin.egg-prices.store') }}';
            var method = 'post';
            if (id) {
                url = '{{ url('admin/egg-prices') }}/' + id;
                method = 'put';
            }

            try {
                if (saveButton) { saveButton.disabled = true; saveButton.classList.add('opacity-70', 'cursor-not-allowed'); }
                if (method === 'put') {
                    await axios.put(url, payload);
                } else {
                    await axios.post(url, payload);
                }
                Swal.fire({ icon: 'success', title: 'Saved', text: id ? 'Price entry updated successfully.' : 'Price entry created successfully.', timer: 1500, showConfirmButton: false });
                hideModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (error) {
                var message = (error.response && error.response.data && error.response.data.message) ? error.response.data.message : 'Unable to save this price. Please check your inputs.';
                Swal.fire({ icon: 'error', title: 'Save failed', text: message, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveButton) { saveButton.disabled = false; saveButton.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
