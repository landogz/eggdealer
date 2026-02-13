@props([
    'title',
    'value' => 0,
    'icon' => null,
    'subtitle' => null,
    'href' => null,
])

@php
    $target = is_numeric($value) ? (float) $value : 0;
@endphp

@if ($href)
    <a href="{{ $href }}"
       class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 text-left shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
@else
    <div
        class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 text-left shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
@endif
    <div class="absolute -right-8 -top-8 h-20 w-20 rounded-full bg-gradient-to-tr from-amber-300/60 to-orange-400/40 blur-xl opacity-60 group-hover:scale-125 group-hover:opacity-90 transition-all duration-500"></div>
    <div class="relative flex items-start justify-between gap-3">
        <div>
            <p class="text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-300">
                {{ $title }}
            </p>
            <div
                x-data="{ count: 0, target: {{ $target }}, duration: 900 }"
                x-init="
                    let start = 0;
                    let startTime = performance.now();
                    function animate(now) {
                        let elapsed = now - startTime;
                        if (elapsed >= duration) {
                            count = target;
                            return;
                        }
                        let progress = elapsed / duration;
                        count = Math.round(target * progress);
                        requestAnimationFrame(animate);
                    }
                    requestAnimationFrame(animate);
                "
                class="mt-1 flex items-baseline gap-1"
            >
                <span class="text-2xl font-bold text-slate-900 dark:text-slate-50" x-text="count.toLocaleString()"></span>
                @if($subtitle)
                    <span class="text-[0.7rem] font-medium text-slate-500 dark:text-slate-200">{{ $subtitle }}</span>
                @endif
            </div>
        </div>
        @if($icon)
            <div
                class="inline-flex h-9 w-9 items-center justify-center rounded-2xl bg-gradient-to-tr from-amber-300 to-orange-400 text-base shadow-md ring-2 ring-amber-100/60 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                <span>{{ $icon }}</span>
            </div>
        @endif
    </div>
@if ($href)
    </a>
@else
    </div>
@endif

