@extends('admin.layouts.app')

@section('title', 'Egg Sizes')
@section('header_title', 'Egg Sizes')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Egg Size Categories</h1>
                <p class="text-xs text-slate-500">
                    Manage your XS, S, M, L, XL, and XXL size definitions for consistent pricing and reporting.
                </p>
            </div>
            <button id="openCreateModal"
                    class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                + Add size
            </button>
        </div>

        <div class="mt-5 overflow-hidden rounded-2xl border border-slate-100 bg-white">
            <table class="min-w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-50 text-[0.7rem] uppercase tracking-[0.18em] text-slate-500">
                    <tr>
                        <th class="px-4 py-2">Size</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sizes as $size)
                        <tr class="border-t border-slate-100 text-xs">
                            <td class="px-4 py-2 font-semibold text-slate-900">{{ $size->size_name }}</td>
                            <td class="px-4 py-2 text-slate-600">{{ $size->description ?? '—' }}</td>
                            <td class="px-4 py-2">
                                @if ($size->is_active)
                                    <span class="inline-flex rounded-full bg-emerald-50 px-2.5 py-0.5 text-[0.7rem] font-semibold text-emerald-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-[0.7rem] font-semibold text-slate-500">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-right">
                                <button class="editSize inline-flex items-center rounded-full border border-slate-200 bg-white px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm hover:border-gold hover:text-gold"
                                        data-id="{{ $size->id }}"
                                        data-name="{{ $size->size_name }}"
                                        data-description="{{ $size->description }}"
                                        data-active="{{ $size->is_active ? '1' : '0' }}">
                                    Edit
                                </button>
                                <button class="deleteSize ml-2 inline-flex items-center rounded-full border border-red-100 bg-white px-3 py-1 text-[0.7rem] font-medium text-red-600 shadow-sm hover:border-red-300"
                                        data-id="{{ $size->id }}"
                                        data-name="{{ $size->size_name }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-xs text-slate-500">No egg sizes defined yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Create / Edit Modal -->
    <div id="sizeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4">
        <div class="w-full max-w-md rounded-3xl bg-white p-5 shadow-2xl">
            <div class="flex items-center justify-between">
                <h2 id="sizeModalTitle" class="text-sm font-semibold text-slate-900">Add size</h2>
                <button id="closeSizeModal" class="text-slate-400 hover:text-slate-600 text-lg leading-none">&times;</button>
            </div>
            <form id="sizeForm" class="mt-4 space-y-3">
                <input type="hidden" id="sizeId">
                <div>
                    <label for="sizeName" class="text-xs font-medium text-slate-700">Size name (e.g. XS, S, M)</label>
                    <input id="sizeName" type="text"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                           maxlength="10" required>
                </div>
                <div>
                    <label for="sizeDescription" class="text-xs font-medium text-slate-700">Description</label>
                    <input id="sizeDescription" type="text"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                           placeholder="Optional short note">
                </div>
                <label class="inline-flex items-center gap-2 text-xs text-slate-700">
                    <input type="checkbox" id="sizeActive"
                           class="h-3.5 w-3.5 rounded border-slate-300 text-gold focus:ring-gold" checked>
                    <span>Active</span>
                </label>
                <div class="pt-2 text-right">
                    <button type="button" id="cancelSize"
                            class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-1.5 text-xs font-medium text-slate-700 shadow-sm hover:border-slate-300">
                        Cancel
                    </button>
                    <button type="submit" id="saveSize"
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
    const sizeModal = document.getElementById('sizeModal');
    const sizeModalTitle = document.getElementById('sizeModalTitle');
    const openCreateModal = document.getElementById('openCreateModal');
    const closeSizeModal = document.getElementById('closeSizeModal');
    const cancelSize = document.getElementById('cancelSize');
    const sizeForm = document.getElementById('sizeForm');
    const sizeId = document.getElementById('sizeId');
    const sizeName = document.getElementById('sizeName');
    const sizeDescription = document.getElementById('sizeDescription');
    const sizeActive = document.getElementById('sizeActive');

    function openModal(mode, data) {
        if (mode === 'create') {
            sizeModalTitle.textContent = 'Add size';
            sizeId.value = '';
            sizeName.value = '';
            sizeDescription.value = '';
            sizeActive.checked = true;
        } else if (mode === 'edit' && data) {
            sizeModalTitle.textContent = 'Edit size';
            sizeId.value = data.id;
            sizeName.value = data.name || '';
            sizeDescription.value = data.description || '';
            sizeActive.checked = data.active === '1';
        }
        sizeModal.classList.remove('hidden');
        sizeModal.classList.add('flex');
    }

    function closeModal() {
        sizeModal.classList.add('hidden');
        sizeModal.classList.remove('flex');
    }

    if (openCreateModal) openCreateModal.addEventListener('click', function () { openModal('create'); });
    if (closeSizeModal) closeSizeModal.addEventListener('click', closeModal);
    if (cancelSize) cancelSize.addEventListener('click', closeModal);

    document.querySelectorAll('.editSize').forEach(function (button) {
        button.addEventListener('click', function () {
            openModal('edit', {
                id: button.dataset.id,
                name: button.dataset.name,
                description: button.dataset.description,
                active: button.dataset.active,
            });
        });
    });

    document.querySelectorAll('.deleteSize').forEach(function (button) {
        button.addEventListener('click', function () {
            var id = button.dataset.id;
            var name = button.dataset.name;
            Swal.fire({
                icon: 'warning',
                title: 'Delete size?',
                text: 'Are you sure you want to delete size "' + name + '"?',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Yes, delete it',
            }).then(async function (result) {
                if (!result.isConfirmed) return;
                try {
                    await axios.delete('{{ route('admin.egg-sizes.destroy', ['eggSize' => '__ID__']) }}'.replace('__ID__', id));
                    Swal.fire({ icon: 'success', title: 'Deleted', text: 'Egg size deleted successfully.', timer: 1500, showConfirmButton: false });
                    setTimeout(function () { window.location.reload(); }, 1500);
                } catch (error) {
                    var msg = (error.response && error.response.data && error.response.data.message) ? error.response.data.message : 'Unable to delete this size. Please try again.';
                    Swal.fire({ icon: 'error', title: 'Delete failed', text: msg, confirmButtonColor: '#D4AF37' });
                }
            });
        });
    });

    if (sizeForm) {
        sizeForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            var id = sizeId.value;
            var payload = {
                size_name: sizeName.value.trim(),
                description: sizeDescription.value.trim() || null,
                is_active: sizeActive.checked ? 1 : 0,
            };
            if (!payload.size_name) {
                Swal.fire({ icon: 'warning', title: 'Missing size name', text: 'Please enter a size name (e.g. XS, S, M).', confirmButtonColor: '#D4AF37' });
                return;
            }
            var saveButton = document.getElementById('saveSize');
            try {
                if (saveButton) { saveButton.disabled = true; saveButton.classList.add('opacity-70', 'cursor-not-allowed'); }
                if (id) {
                    await axios.put('{{ url('admin/egg-sizes') }}/' + id, payload);
                } else {
                    await axios.post('{{ route('admin.egg-sizes.store') }}', payload);
                }
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Egg size saved successfully.', timer: 1500, showConfirmButton: false });
                closeModal();
                setTimeout(function () { window.location.reload(); }, 1500);
            } catch (error) {
                var message = error.response && error.response.data && error.response.data.message ? error.response.data.message : 'Unable to save this size. Please check your input.';
                Swal.fire({ icon: 'error', title: 'Save failed', text: message, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveButton) { saveButton.disabled = false; saveButton.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
