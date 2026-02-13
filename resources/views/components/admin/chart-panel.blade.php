@props([
    'title',
    'subtitle' => null,
    'emoji' => null,
    'chartId',
])

<div
    class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
    <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full bg-gradient-to-br from-amber-200/60 to-orange-400/40 blur-xl opacity-70 group-hover:scale-125 group-hover:opacity-100 transition-all duration-500"></div>
    <div class="relative flex items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 flex items-center gap-1.5">
                @if($emoji)
                    <span class="text-base">{{ $emoji }}</span>
                @endif
                <span>{{ $title }}</span>
            </p>
            @if($subtitle)
                <p class="mt-0.5 text-[0.7rem] text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
    <div class="relative mt-3">
        <div class="pointer-events-none absolute inset-0 animate-pulse rounded-2xl bg-slate-100/60 dark:bg-slate-800/60" x-show="!chartsReady"></div>
        <canvas id="{{ $chartId }}" class="h-52 w-full"></canvas>
    </div>
</div>

