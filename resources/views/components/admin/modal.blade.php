@props([
    'modalId',
    'title',
])

<div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/40 px-4" x-data>
    <div class="w-full max-w-md rounded-3xl bg-white/95 p-5 shadow-2xl dark:bg-slate-900/95 dark:text-slate-50">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $title }}</h2>
            <button type="button"
                    class="text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 text-lg leading-none"
                    @click="$root.classList.add('hidden'); $root.classList.remove('flex');">
                &times;
            </button>
        </div>
        <div class="mt-4">
            {{ $slot }}
        </div>
    </div>
</div>

