<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#FFF8E7">
    <meta name="description" content="Professional fresh egg supplier in San Jose, Batangas providing quality table eggs for households, retailers, and small businesses.">
    <meta name="robots" content="index, follow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ url('/genz') }}">

    <title>Fresh Egg Supplier San Jose Batangas | {{ $businessName ?? 'Egg Supply' }}</title>
    @if(!empty($logoUrl) && in_array('favicon', $logoPositions ?? [], true))
        <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif

    <!-- Open Graph -->
    <meta property="og:title" content="Fresh Egg Supplier San Jose, Batangas">
    <meta property="og:description" content="Consistent supply of quality fresh eggs for homes and small businesses in San Jose, Batangas.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/genz') }}">
    <meta property="og:site_name" content="{{ $businessName ?? 'Egg Supply' }}">
    <meta property="og:image" content="{{ asset('images/og/egg-supplier-genz.jpg') }}">
    <meta property="og:image:alt" content="{{ $businessName ?? 'Egg Supply' }} - Fresh eggs in San Jose, Batangas">
    <meta property="og:locale" content="en_PH">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Fresh Egg Supplier San Jose, Batangas">
    <meta name="twitter:description" content="Consistent supply of quality fresh eggs for homes and small businesses in San Jose, Batangas.">
    <meta name="twitter:image" content="{{ asset('images/og/egg-supplier-genz.jpg') }}">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        yolk: '#FFD84D',
                        cream: '#FFF8E7',
                        'soft-brown': '#d1a679',
                        'bubble-pink': '#FFE5F1',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                    },
                    boxShadow: {
                        'float-lg': '0 20px 45px rgba(15,23,42,0.18)',
                    },
                    fontFamily: {
                        sans: ['system-ui', 'ui-sans-serif', 'Inter', 'sans-serif'],
                        display: ['"Baloo 2"', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>

    <!-- Fun Display Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        .float-egg {
            animation: floatEgg 3.5s ease-in-out infinite;
        }

        @keyframes floatEgg {
            0%, 100% {
                transform: translateY(0) rotate(-4deg);
            }
            50% {
                transform: translateY(-12px) rotate(4deg);
            }
        }

        .hover-bounce {
            transition: transform 200ms cubic-bezier(0.22, 1, 0.36, 1), box-shadow 200ms;
        }

        .hover-bounce:hover {
            transform: translateY(-4px) scale(1.02);
        }

        /* Products slider: hide scrollbar but keep scroll */
        .genz-products-slider {
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .genz-products-slider::-webkit-scrollbar {
            display: none;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-cream via-[#FFF1BF] to-white text-slate-900 antialiased">
    <!-- Sticky Navbar -->
    <header class="sticky top-0 z-40 border-b border-white/60 bg-white/80 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 md:px-6">
            <div class="flex items-center gap-2">
                @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                    <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-9 w-9 flex-shrink-0 rounded-full object-contain bg-white/80 shadow-md">
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-yolk to-amber-300 shadow-md">
                        <span class="text-lg">🐣</span>
                    </div>
                @endif
                <div class="leading-tight">
                    <p class="font-display text-base font-semibold tracking-tight">{{ $businessName ?? 'Fresh Egg Vibes' }}</p>
                    <p class="text-[0.7rem] uppercase tracking-[0.22em] text-slate-500">{{ $businessAddress ?? 'San Jose, Batangas' }}</p>
                </div>
            </div>

            <button id="genzNavToggle" aria-label="Toggle navigation menu"
                    class="inline-flex items-center rounded-full border border-yellow-200 bg-white/80 px-3 py-1.5 text-xs font-medium text-slate-800 shadow-sm transition hover:border-yolk hover:text-yolk md:hidden">
                <span>Menu</span>
                <svg class="ml-1.5 h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>

            <div id="genzNavMenu"
                 class="absolute right-4 top-16 w-60 rounded-3xl border border-yellow-100 bg-white shadow-xl md:static md:flex md:w-auto md:items-center md:gap-6 md:border-0 md:bg-transparent md:p-0 md:shadow-none hidden">
                <a href="#genz-hero" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Home</a>
                <a href="#genz-about" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">About</a>
                <a href="#genz-products" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Products</a>
                <a href="#genz-why" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Why Us</a>
                <a href="#genz-faq" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">FAQ</a>
                <a href="{{ route('egg.system') }}" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Inventory System</a>
                <a href="#genz-contact"
                   class="m-3 block rounded-full bg-gradient-to-r from-yolk to-amber-300 px-4 py-1.5 text-center text-[0.7rem] font-semibold text-slate-900 shadow-sm hover:shadow-md md:m-0 md:px-4 md:text-xs">
                    Order Now
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 pb-16 pt-8 md:px-6">
        <!-- Hero -->
        <section id="genz-hero" class="flex flex-col gap-10 pt-4 md:flex-row md:items-center md:gap-12">
            <div class="space-y-4 md:w-1/2">
                @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                    <div class="mb-2 flex items-center gap-3">
                        <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-12 w-12 rounded-2xl object-contain bg-white/80 shadow-md sm:h-14 sm:w-14">
                        <span class="text-sm font-semibold text-slate-700">{{ $businessName ?? 'Egg Supply' }}</span>
                    </div>
                @endif
                <p class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-yolk"></span>
                    Fresh eggs · Reliable supply
                </p>
                <h1 class="font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">
                    Fresh Eggs.
                    <br>
                    <span class="bg-gradient-to-r from-yolk to-amber-400 bg-clip-text text-transparent">
                        Reliable Daily Supply.
                    </span>
                </h1>
                <p class="max-w-md text-sm leading-relaxed text-slate-600 sm:text-base">
                    Consistent, farm-fresh table eggs from <span class="font-semibold text-slate-900">{{ $businessAddress ?? 'San Jose, Batangas' }}</span> for homes,
                    retailers, and food businesses. Clean shells, quality yolks, and dependable supply for every menu.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <a href="#genz-contact"
                       class="hover-bounce inline-flex items-center rounded-full bg-gradient-to-r from-yolk to-amber-300 px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-float-lg">
                        Order Now
                    </a>
                    <a href="https://www.facebook.com/michellenicola.matibag/" target="_blank" rel="noopener noreferrer"
                       class="hover-bounce inline-flex items-center rounded-full border border-yellow-200 bg-white/80 px-5 py-2.5 text-xs font-semibold text-slate-800 shadow-sm">
                        Message on Facebook
                        <span class="ml-1.5 text-base">📲</span>
                    </a>
                </div>

                <div class="flex flex-wrap gap-3 pt-3 text-[0.7rem] text-slate-500">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">
                        🌞 Fresh daily eggs
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">
                        🚛 Local Batangas supplier
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">
                        💬 Easy Facebook orders
                    </span>
                </div>
            </div>

            <div class="relative md:w-1/2">
                <div class="absolute -left-4 -top-4 h-20 w-20 rounded-full bg-gradient-to-tr from-yolk/40 to-white/0 blur-2xl"></div>
                <div class="absolute -right-6 bottom-2 h-16 w-16 rounded-full bg-gradient-to-tr from-amber-300/50 to-white/0 blur-2xl"></div>

                <div class="relative rounded-3xl bg-gradient-to-br from-cream via-[#FFEFD3] to-white p-1 shadow-float-lg">
                    <div class="relative overflow-hidden rounded-3xl bg-white/80 p-5">
                        @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                            <div class="absolute -right-4 -top-4 h-16 w-16 float-egg rounded-full bg-white/90 shadow-lg flex items-center justify-center overflow-hidden p-1">
                                <img src="{{ $logoUrl }}" alt="" class="h-full w-full object-contain">
                            </div>
                        @else
                            <div class="absolute -right-4 -top-4 h-16 w-16 float-egg rounded-full bg-gradient-to-br from-yolk to-amber-300 shadow-lg flex items-center justify-center">
                                <span class="text-3xl">🥚</span>
                            </div>
                        @endif
                        <p class="text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-amber-700/90">
                            {{ $businessName ?? 'Egg Supply' }}
                        </p>
                        <p class="mt-2 font-display text-xl font-semibold text-slate-900">
                            Quality eggs for<br>your everyday cooking.
                        </p>
                        <p class="mt-3 text-xs leading-relaxed text-slate-600">
                            Local, friendly, and always fresh. Ideal for breakfast, baking, catering, and daily kitchen needs.
                        </p>
                        <div class="mt-4 flex flex-wrap gap-2 text-[0.7rem]">
                            <span class="rounded-full bg-bubble-pink px-3 py-1 font-medium text-pink-700">Quality assured</span>
                            <span class="rounded-full bg-yellow-100 px-3 py-1 font-medium text-amber-800">Budget friendly</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="genz-about" class="mt-14 space-y-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                <div class="max-w-xl space-y-3">
                    <p class="text-[0.75rem] font-semibold uppercase tracking-[0.3em] text-amber-700">About</p>
                    <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">
                        {{ $businessName ?? 'Egg Supply' }}
                    </h2>
                    <p class="text-sm leading-relaxed text-slate-600 sm:text-base">
                        Based in <span class="font-semibold text-slate-900">{{ $businessAddress ?? 'San Jose, Batangas' }}</span>, {{ $businessName ?? 'Egg Supply' }}
                        provides fresh, affordable table eggs for households, sari‑sari stores, eateries, and small food businesses.
                    </p>
                    <p class="text-sm leading-relaxed text-slate-600 sm:text-base">
                        We focus on <span class="font-semibold">reliable supply and consistent quality</span>. Fresh trays arrive daily
                        so your kitchen, store, or commissary is always ready for breakfast, snacks, and baking orders.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/80 p-4 shadow-md md:w-72">
                    <dl class="space-y-2 text-[0.8rem] text-slate-600">
                        <div class="flex justify-between gap-4">
                            <dt class="font-semibold text-slate-800">Location</dt>
                            <dd class="text-right">{{ $businessAddress ?? 'San Jose, Batangas' }}</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-semibold text-slate-800">Service</dt>
                            <dd class="text-right">Local · Friendly · Reliable</dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="font-semibold text-slate-800">Supply</dt>
                            <dd class="text-right">Fresh daily stock</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </section>

        <!-- Products (Egg sizes & pricing from backend) – Slider -->
        <section id="genz-products" class="mt-14">
            <div class="flex items-baseline justify-between gap-3">
                <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">Our Egg Products</h2>
                <p class="hidden text-xs text-slate-500 sm:block">Choose the egg size and quantity that matches your kitchen or business needs.</p>
            </div>

            <div class="relative mt-6">
                @if(count($eggSizes) > 1)
                    <button type="button" id="genzProductsPrev" aria-label="Previous product"
                            class="absolute left-0 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-yellow-200 bg-white/95 shadow-md transition hover:border-yolk hover:bg-yolk/20 focus:outline-none focus:ring-2 focus:ring-yolk focus:ring-offset-2 md:-left-2">
                        <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <button type="button" id="genzProductsNext" aria-label="Next product"
                            class="absolute right-0 top-1/2 z-10 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full border border-yellow-200 bg-white/95 shadow-md transition hover:border-yolk hover:bg-yolk/20 focus:outline-none focus:ring-2 focus:ring-yolk focus:ring-offset-2 md:-right-2">
                        <svg class="h-5 w-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @endif
                <div id="genzProductsSlider" class="genz-products-slider flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth px-1 py-2 pb-4 md:px-2"
                     role="region" aria-label="Egg products carousel">
                    @forelse($eggSizes as $size)
                        <article class="hover-bounce w-[min(280px,85vw)] flex-shrink-0 snap-center rounded-3xl bg-white/90 p-4 shadow-md sm:w-72">
                            <div class="flex items-center gap-2">
                                <span class="text-xl" aria-hidden="true">🥚</span>
                                <h3 class="text-sm font-semibold text-slate-900">{{ $size->size_name }}</h3>
                            </div>
                            <p class="mt-2 text-xs leading-relaxed text-slate-600">
                                {{ $size->description ?: 'Quality eggs for everyday cooking, baking, and food service.' }}
                            </p>
                            @if($size->relationLoaded('latestActivePrice') && $size->latestActivePrice)
                                @php
                                    $p = $size->latestActivePrice;
                                    $prices = array_filter([
                                        'Per piece' => $p->price_per_piece,
                                        'Per tray'  => $p->price_per_tray,
                                        'Bulk'      => $p->price_bulk,
                                        'Wholesale' => $p->wholesale_price,
                                        'Reseller'  => $p->reseller_price,
                                    ], fn($v) => $v !== null && (float) $v > 0);
                                @endphp
                                @if(count($prices) > 0)
                                    <dl class="mt-3 space-y-1 border-t border-slate-100 pt-3 text-[0.7rem]">
                                        @foreach($prices as $label => $value)
                                            <div class="flex justify-between gap-2 text-slate-600">
                                                <dt>{{ $label }}</dt>
                                                <dd class="font-semibold text-slate-900">₱{{ number_format((float) $value, 2) }}</dd>
                                            </div>
                                        @endforeach
                                    </dl>
                                @endif
                            @else
                                <p class="mt-2 text-[0.7rem] text-slate-400">Price on inquiry.</p>
                            @endif
                        </article>
                    @empty
                        <article class="w-full flex-shrink-0 snap-center rounded-3xl bg-white/90 p-4 shadow-md sm:max-w-md">
                            <p class="text-sm text-slate-600">Egg sizes and prices are being updated. Please <a href="#genz-contact" class="font-semibold text-yolk underline underline-offset-2">contact us</a> for current offerings.</p>
                        </article>
                    @endforelse
                </div>
                @if(count($eggSizes) > 1)
                    <div class="flex justify-center gap-1.5 pt-2" id="genzProductsDots" aria-hidden="true">
                        @foreach($eggSizes as $i => $size)
                            <button type="button" class="genz-dot h-2 w-2 rounded-full bg-slate-300 transition hover:bg-slate-400 focus:outline-none focus:ring-2 focus:ring-yolk focus:ring-offset-1 {{ $i === 0 ? 'bg-yolk ring-2 ring-yolk ring-offset-1' : '' }}"
                                    data-index="{{ $i }}" aria-label="Go to slide {{ $i + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <!-- Why Choose Us -->
        <section id="genz-why" class="mt-14">
            <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">Why Choose Our Egg Supply</h2>
            <p class="mt-2 text-sm text-slate-600 sm:text-base">
                Key reasons households and small businesses choose us as their trusted egg supplier.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="hover-bounce rounded-3xl bg-white/90 p-4 shadow-md">
                    <div class="text-xl">🌞</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Always Fresh</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Daily egg rotation to keep your trays fresh and ready for cooking and serving.
                    </p>
                </div>
                <div class="hover-bounce rounded-3xl bg-white/90 p-4 shadow-md">
                    <div class="text-xl">🚛</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Fast Supply</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Quick coordination for pick‑ups and local deliveries in and around {{ $businessAddress ?? 'San Jose, Batangas' }}.
                    </p>
                </div>
                <div class="hover-bounce rounded-3xl bg-white/90 p-4 shadow-md">
                    <div class="text-xl">💸</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Budget Friendly</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Competitive pricing designed to support households, resellers, and food businesses while maintaining quality.
                    </p>
                </div>
                <div class="hover-bounce rounded-3xl bg-white/90 p-4 shadow-md">
                    <div class="text-xl">💬</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Easy Facebook Orders</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Place orders and inquiries through a simple Facebook message—no complicated forms required.
                    </p>
                </div>
            </div>
        </section>

        <!-- FAQ -->
        <section id="genz-faq" class="mt-14">
            <div class="max-w-3xl space-y-4">
                <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">Frequently Asked Questions</h2>
                <p class="text-sm text-slate-600 sm:text-base">
                    Find answers to common questions about our egg supply, delivery options, and order process.
                </p>
            </div>
            <div class="mt-6 grid gap-4 md:grid-cols-2">
                <article class="rounded-3xl bg-white/90 p-4 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">Do you deliver within {{ $businessAddress ?? 'San Jose, Batangas' }}?</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Yes. We coordinate pick‑ups and local deliveries within {{ $businessAddress ?? 'San Jose, Batangas' }}. For nearby areas, delivery options can be discussed when you inquire.
                    </p>
                </article>
                <article class="rounded-3xl bg-white/90 p-4 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">What egg sizes and quantities are available?</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        We offer regular and jumbo eggs, available per tray or in bulk quantities to support households, resellers, and food businesses.
                    </p>
                </article>
                <article class="rounded-3xl bg-white/90 p-4 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">How do I place an order?</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        You can send an inquiry using the contact form below or message us directly on Facebook. We will confirm availability, pricing, and schedule with you.
                    </p>
                </article>
                <article class="rounded-3xl bg-white/90 p-4 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">Can you supply eggs for small restaurants or events?</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Yes. We regularly supply eggs for eateries, bakeries, and events. Share your estimated volume and schedule so we can recommend the best arrangement.
                    </p>
                </article>
            </div>
        </section>

        <!-- Contact -->
        <section id="genz-contact" class="mt-14">
            <div class="grid gap-8 md:grid-cols-[1.1fr_1fr] md:items-start">
                <div class="space-y-3">
                    <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">Contact Our Egg Supply Team</h2>
                    <p class="text-sm leading-relaxed text-slate-600 sm:text-base">
                        Share your egg requirements for your household, store, or food business. We will reply with availability, recommended options, and transparent pricing.
                    </p>

                    <div class="rounded-3xl bg-white/90 p-4 shadow-md text-sm text-slate-700">
                        <p class="font-semibold text-slate-900">Contact</p>
                        <p class="mt-1">{{ $businessName ?? 'Egg Supply' }}</p>
                        <p>{{ $businessAddress ?? 'San Jose, Batangas' }}</p>
                        @if(!empty($contactInfo))
                            <p class="mt-1 text-slate-600">{{ $contactInfo }}</p>
                        @endif
                        <a href="https://www.facebook.com/michellenicola.matibag/" target="_blank" rel="noopener noreferrer"
                           class="mt-3 inline-flex items-center rounded-full bg-gradient-to-r from-[#1877F2] to-[#42A5F5] px-4 py-2 text-xs font-semibold text-white shadow-md hover:shadow-lg">
                            Message on Facebook
                            <span class="ml-1.5 text-base">💬</span>
                        </a>
                    </div>
                </div>

                <div class="rounded-3xl bg-white/95 p-5 shadow-float-lg">
                    <form id="genzContactForm" class="space-y-3">
                        <div>
                            <label for="genz-name" class="text-xs font-semibold text-slate-800">Name</label>
                            <input id="genz-name" name="name" type="text" autocomplete="name"
                                   class="mt-1 w-full rounded-2xl border border-yellow-100 bg-cream px-3 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-yolk focus:ring-1 focus:ring-yolk"
                                   placeholder="Your full name" required>
                        </div>
                        <div class="grid gap-3 sm:grid-cols-2">
                            <div>
                                <label for="genz-phone" class="text-xs font-semibold text-slate-800">Contact Number</label>
                                <input id="genz-phone" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                                       class="mt-1 w-full rounded-2xl border border-yellow-100 bg-cream px-3 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-yolk focus:ring-1 focus:ring-yolk"
                                       placeholder="09xx xxx xxxx" required>
                            </div>
                            <div>
                                <label for="genz-email" class="text-xs font-semibold text-slate-800">Email Address</label>
                                <input id="genz-email" name="email" type="email" autocomplete="email"
                                       class="mt-1 w-full rounded-2xl border border-yellow-100 bg-cream px-3 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-yolk focus:ring-1 focus:ring-yolk"
                                       placeholder="you@email.com" required>
                            </div>
                        </div>
                        <div>
                            <label for="genz-message" class="text-xs font-semibold text-slate-800">Message</label>
                            <textarea id="genz-message" name="message" rows="4"
                                      class="mt-1 w-full rounded-2xl border border-yellow-100 bg-cream px-3 py-2.5 text-sm text-slate-900 outline-none placeholder:text-slate-400 focus:border-yolk focus:ring-1 focus:ring-yolk"
                                      placeholder="Share your egg requirements, quantity, and preferred schedule." required></textarea>
                        </div>
                        <button type="submit"
                                class="hover-bounce inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-yolk to-amber-300 px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-md">
                            Send Inquiry
                        </button>
                        <p class="text-[0.7rem] leading-relaxed text-slate-400">
                            We will reach out using the contact details you provided. For urgent requirements, you may also message us directly on Facebook.
                        </p>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-yellow-100 bg-cream/90">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-2 px-4 py-4 text-[0.75rem] text-slate-600 md:flex-row md:px-6">
            <p>Supplying {{ $businessAddress ?? 'San Jose, Batangas' }} with fresh table eggs daily.</p>
            <p class="text-slate-400">
                Develop by
                <a href="https://www.facebook.com/landogz" target="_blank" rel="noopener noreferrer"
                   class="font-semibold text-slate-700 underline underline-offset-2 hover:text-slate-900">
                    Landogz Web Solutions
                </a>
            </p>
        </div>
    </footer>

    <!-- Floating Contact Button (Mobile) -->
    <a href="#genz-contact"
       class="fixed bottom-4 right-4 inline-flex items-center rounded-full bg-gradient-to-r from-yolk to-amber-300 px-4 py-2 text-xs font-semibold text-slate-900 shadow-lg md:hidden">
        Order Eggs
        <span class="ml-1.5 text-base">🥚</span>
    </a>

    <!-- Axios & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.getElementById('genzNavToggle');
            const navMenu = document.getElementById('genzNavMenu');

            if (navToggle && navMenu) {
                navToggle.addEventListener('click', () => {
                    navMenu.classList.toggle('hidden');
                });

                navMenu.querySelectorAll('a[href^="#"]').forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 768) {
                            navMenu.classList.add('hidden');
                        }
                    });
                });
            }

            // Products slider: prev/next and dots
            const slider = document.getElementById('genzProductsSlider');
            const prevBtn = document.getElementById('genzProductsPrev');
            const nextBtn = document.getElementById('genzProductsNext');
            const dots = document.querySelectorAll('.genz-dot');
            if (slider && (prevBtn || nextBtn)) {
                const cards = slider.querySelectorAll('article');
                const cardWidth = () => (cards[0] ? cards[0].offsetWidth + 16 : 296 + 16);
                const updateDots = (index) => {
                    const idx = Math.max(0, Math.min(index, dots.length - 1));
                    dots.forEach((d, i) => {
                        d.classList.toggle('bg-yolk', i === idx);
                        d.classList.toggle('ring-2', i === idx);
                        d.classList.toggle('ring-yolk', i === idx);
                        d.classList.toggle('ring-offset-1', i === idx);
                        d.classList.toggle('bg-slate-300', i !== idx);
                    });
                };
                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: -cardWidth(), behavior: 'smooth' });
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: cardWidth(), behavior: 'smooth' });
                    });
                }
                slider.addEventListener('scroll', () => {
                    const index = Math.round(slider.scrollLeft / cardWidth());
                    const clamped = Math.max(0, Math.min(index, cards.length - 1));
                    updateDots(clamped);
                });
                dots.forEach((dot, i) => {
                    dot.addEventListener('click', () => {
                        slider.scrollTo({ left: i * cardWidth(), behavior: 'smooth' });
                        updateDots(i);
                    });
                });
            }

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
            }
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            const contactForm = document.getElementById('genzContactForm');
            if (contactForm) {
                contactForm.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const submitButton = contactForm.querySelector('button[type="submit"]');
                    const name = contactForm.name.value.trim();
                    const phone = contactForm.phone.value.trim();
                    const email = contactForm.email.value.trim();
                    const message = contactForm.message.value.trim();

                    if (!name || !phone || !email || !message) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Missing details',
                            text: 'Please provide your name, contact number, email address, and message.',
                            confirmButtonColor: '#FFD84D',
                        });
                        return;
                    }

                    try {
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                        }

                        await axios.post('{{ route('contact.submit') }}', {
                            name,
                            phone,
                            email,
                            message,
                        });

                        contactForm.reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Inquiry sent',
                            text: 'Thanks for reaching out! We’ll reply as soon as we can.',
                            confirmButtonColor: '#FFD84D',
                        });
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: 'We could not send your inquiry. Please try again or message us on Facebook.',
                            confirmButtonColor: '#FFD84D',
                        });
                    } finally {
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
                        }
                    }
                });
            }
        });
    </script>
    <!-- Structured data: LocalBusiness -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "{{ $businessName ?? 'Egg Supply' }}",
        "description": "Professional fresh egg supplier in San Jose, Batangas providing quality table eggs for households, retailers, eateries, and small food businesses.",
        "url": "{{ url('/genz') }}",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "{{ $businessAddress ?? 'San Jose, Batangas' }}",
            "addressCountry": "PH"
        },
        "areaServed": {
            "@type": "City",
            "name": "{{ $businessAddress ?? 'San Jose, Batangas' }}"
        },
        "sameAs": [
            "https://www.facebook.com/michellenicola.matibag/"
        ]
    }
    </script>
    <!-- Structured data: FAQPage -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "FAQPage",
        "mainEntity": [
            {
                "@type": "Question",
                "name": "Do you deliver within {{ $businessAddress ?? 'San Jose, Batangas' }}?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. We coordinate pick-ups and local deliveries within {{ $businessAddress ?? 'San Jose, Batangas' }}. For nearby areas, delivery options can be discussed when customers inquire."
                }
            },
            {
                "@type": "Question",
                "name": "What egg sizes and quantities are available?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "We offer regular and jumbo eggs, available per tray or in bulk quantities to support households, resellers, and food businesses."
                }
            },
            {
                "@type": "Question",
                "name": "How do I place an order?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Customers can place an order by sending an inquiry through the website contact form or by messaging us directly on Facebook. We then confirm availability, pricing, and schedule."
                }
            },
            {
                "@type": "Question",
                "name": "Can you supply eggs for small restaurants or events?",
                "acceptedAnswer": {
                    "@type": "Answer",
                    "text": "Yes. We regularly supply eggs for eateries, bakeries, and events. Customers can share their estimated volume and schedule so we can recommend the best arrangement."
                }
            }
        ]
    }
    </script>
</body>
</html>

