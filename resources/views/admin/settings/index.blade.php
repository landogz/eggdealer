@extends('admin.layouts.app')

@section('title', 'Settings')
@section('header_title', 'Settings')

@section('content')
    <section class="rounded-3xl bg-white/95 p-6 shadow-xl">
        <div class="mb-6">
            <h1 class="text-lg font-semibold text-slate-900 dark:text-slate-50">System settings</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Business info, logo, and default tray size used for inventory and stock-in.</p>
        </div>

        <form id="settingsForm" class="space-y-6 max-w-xl" enctype="multipart/form-data">
            {{-- Logo --}}
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-800/30">
                <h2 class="text-sm font-semibold text-slate-800 dark:text-slate-200 mb-3">Logo</h2>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <div class="flex-shrink-0">
                        <div id="logoPreview" class="h-20 w-20 rounded-2xl border-2 border-dashed border-slate-300 bg-white flex items-center justify-center overflow-hidden dark:border-slate-600 dark:bg-slate-800">
                            @if($setting->logo_url ?? null)
                                <img src="{{ $setting->logo_url }}" alt="Logo" class="h-full w-full object-contain">
                            @else
                                <span class="text-2xl text-slate-400">🥚</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-1 space-y-2">
                        <input type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/gif,image/webp"
                               class="block w-full text-xs text-slate-600 file:mr-2 file:rounded-xl file:border-0 file:bg-gold file:px-3 file:py-1.5 file:text-xs file:font-semibold file:text-slate-900 hover:file:bg-gold-soft dark:text-slate-300">
                        <label class="inline-flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400">
                            <input type="checkbox" id="remove_logo" name="remove_logo" value="1" class="rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                            <span>Remove current logo</span>
                        </label>
                        <p class="text-[0.65rem] text-slate-500 dark:text-slate-400">JPEG, PNG, GIF or WebP. Max 2 MB.</p>
                    </div>
                </div>
                <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700">
                    <p class="text-[0.7rem] font-medium text-slate-600 dark:text-slate-300 mb-2">Show logo in:</p>
                    <div class="flex flex-wrap gap-x-4 gap-y-2">
                        @foreach(\App\Models\Setting::availableLogoPositions() as $value => $label)
                            <label class="inline-flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300">
                                <input type="checkbox" name="logo_positions[]" value="{{ $value }}"
                                       {{ in_array($value, old('logo_positions', $setting->logo_positions ?? []), true) ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-amber-500 focus:ring-amber-400">
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-4">
            <div>
                <label for="business_name" class="text-xs font-medium text-slate-700 dark:text-slate-300">Business name</label>
                <input id="business_name" name="business_name" type="text" value="{{ old('business_name', $setting->business_name) }}"
                       class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div>
                <label for="address" class="text-xs font-medium text-slate-700 dark:text-slate-300">Address</label>
                <textarea id="address" name="address" rows="2" class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">{{ old('address', $setting->address) }}</textarea>
            </div>
            <div>
                <label for="contact_info" class="text-xs font-medium text-slate-700 dark:text-slate-300">Contact info</label>
                <input id="contact_info" name="contact_info" type="text" value="{{ old('contact_info', $setting->contact_info) }}"
                       class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                       placeholder="Phone, email">
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="default_tray_size" class="text-xs font-medium text-slate-700 dark:text-slate-300">Default pieces per tray</label>
                    <input id="default_tray_size" name="default_tray_size" type="number" min="1" max="999" value="{{ old('default_tray_size', $setting->default_tray_size ?? 30) }}"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           required>
                </div>
                <div>
                    <label for="currency" class="text-xs font-medium text-slate-700 dark:text-slate-300">Currency</label>
                    <input id="currency" name="currency" type="text" value="{{ old('currency', $setting->currency ?? 'PHP') }}"
                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100"
                           maxlength="10">
                </div>
            </div>
            <div>
                <label for="tax_rate" class="text-xs font-medium text-slate-700 dark:text-slate-300">Tax rate (%)</label>
                <input id="tax_rate" name="tax_rate" type="number" min="0" max="100" step="0.01" value="{{ old('tax_rate', $setting->tax_rate ?? 0) }}"
                       class="mt-1 w-full max-w-[120px] rounded-2xl border border-slate-200 bg-white/80 px-3 py-2 text-sm text-slate-900 outline-none focus:border-gold focus:ring-1 focus:ring-gold dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4 dark:border-slate-700 dark:bg-slate-800/30">
                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-2">Report: Other expenses &amp; income</p>
                <p class="text-[0.65rem] text-slate-500 dark:text-slate-400 mb-2">One line per item: <code class="bg-white dark:bg-slate-800 px-1 rounded">Label, Amount</code> (e.g. Utilities, 500)</p>
                <div class="grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="report_other_expenses_text" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">Other expenses</label>
                        <textarea id="report_other_expenses_text" name="report_other_expenses_text" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="Utilities, 500&#10;Labor, 1200">@php
$oe = $setting->report_other_expenses ?? [];
echo is_array($oe) ? implode("\n", array_map(fn($e) => ($e['label'] ?? '') . ', ' . ($e['amount'] ?? ''), $oe)) : '';
@endphp</textarea>
                    </div>
                    <div>
                        <label for="report_other_income_text" class="block text-[0.7rem] font-medium text-slate-600 dark:text-slate-400">Other income</label>
                        <textarea id="report_other_income_text" name="report_other_income_text" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 bg-white px-2.5 py-1.5 text-xs dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100" placeholder="Byproducts, 200">@php
$oi = $setting->report_other_income ?? [];
echo is_array($oi) ? implode("\n", array_map(fn($i) => ($i['label'] ?? '') . ', ' . ($i['amount'] ?? ''), $oi)) : '';
@endphp</textarea>
                    </div>
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" id="saveSettings" class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-5 py-2 text-xs font-semibold text-slate-900 shadow-md hover:shadow-lg">
                    Save settings
                </button>
            </div>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('settingsForm');
    var logoInput = document.getElementById('logo');
    var logoPreview = document.getElementById('logoPreview');
    var removeLogo = document.getElementById('remove_logo');

    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function () {
            var file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    logoPreview.innerHTML = '<img src="' + e.target.result + '" alt="Logo" class="h-full w-full object-contain">';
                };
                reader.readAsDataURL(file);
            }
        });
    }
    if (removeLogo && logoPreview) {
        removeLogo.addEventListener('change', function () {
            if (this.checked) {
                logoPreview.innerHTML = '<span class="text-2xl text-slate-400">🥚</span>';
            }
        });
    }

    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            var saveBtn = document.getElementById('saveSettings');
            var fd = new FormData(form);
            fd.append('_method', 'PUT');
            fd.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            if (!logoInput || !logoInput.files.length) {
                fd.delete('logo');
            }
            var positions = [];
            form.querySelectorAll('input[name="logo_positions[]"]:checked').forEach(function (cb) {
                positions.push(cb.value);
            });
            fd.delete('logo_positions[]');
            positions.forEach(function (p) { fd.append('logo_positions[]', p); });
            if (removeLogo && !removeLogo.checked) {
                fd.delete('remove_logo');
            }
            try {
                if (saveBtn) { saveBtn.disabled = true; saveBtn.classList.add('opacity-70', 'cursor-not-allowed'); }
                var res = await axios.post('{{ route('admin.settings.update') }}', fd, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                Swal.fire({ icon: 'success', title: 'Saved', text: 'Settings saved.', timer: 1500, showConfirmButton: false });
                if (res.data && res.data.data && res.data.data.logo_path && logoPreview) {
                    var url = '{{ url('') }}/storage/' + res.data.data.logo_path;
                    logoPreview.innerHTML = '<img src="' + url + '" alt="Logo" class="h-full w-full object-contain">';
                }
            } catch (err) {
                var msg = (err.response && err.response.data && err.response.data.message) ? err.response.data.message : 'Unable to save settings.';
                if (err.response && err.response.data && err.response.data.errors) {
                    var first = Object.values(err.response.data.errors)[0];
                    if (Array.isArray(first)) msg = first[0]; else if (typeof first === 'string') msg = first;
                }
                Swal.fire({ icon: 'error', title: 'Error', text: msg, confirmButtonColor: '#D4AF37' });
            } finally {
                if (saveBtn) { saveBtn.disabled = false; saveBtn.classList.remove('opacity-70', 'cursor-not-allowed'); }
            }
        });
    }
});
</script>
@endpush
