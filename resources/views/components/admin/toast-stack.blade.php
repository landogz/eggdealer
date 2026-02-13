<div
    x-data="{ items: [] }"
    x-on:toast.window="
        const id = Date.now();
        items.push({ id, type: $event.detail.type || 'success', message: $event.detail.message || '' });
        setTimeout(() => { items = items.filter(i => i.id !== id) }, 2600);
    "
    class="pointer-events-none fixed right-3 top-16 z-50 flex w-full max-w-xs flex-col gap-2 sm:right-6 sm:top-20"
>
    <template x-for="item in items" :key="item.id">
        <div
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-y-2 opacity-0 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transform transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="pointer-events-auto flex items-center gap-3 rounded-2xl border border-white/30 bg-white/90 px-3 py-2 text-xs shadow-xl backdrop-blur-xl dark:border-slate-700/70 dark:bg-slate-900/90"
        >
            <div class="flex h-7 w-7 items-center justify-center rounded-full"
                 :class="item.type === 'error' ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-200' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200'">
                <span x-text="item.type === 'error' ? '⚠️' : '✅'"></span>
            </div>
            <p class="flex-1 text-slate-800 dark:text-slate-100" x-text="item.message"></p>
        </div>
    </template>
</div>

