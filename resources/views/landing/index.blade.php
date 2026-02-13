<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Premium farm-fresh egg supplier based in San Jose, Batangas. Luxury quality eggs for households, resellers, and businesses.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Premium Farm-Fresh Eggs | {{ $businessName ?? 'Egg Supply' }}</title>
    @if(!empty($logoUrl) && in_array('favicon', $logoPositions ?? [], true))
        <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif

    <!-- Open Graph -->
    <meta property="og:title" content="Premium Farm-Fresh Eggs | {{ $businessName ?? 'Egg Supply' }}">
    <meta property="og:description" content="Carefully selected, naturally fresh eggs delivered with quality from San Jose, Batangas.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/og-eggdealer.jpg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: '#D4AF37',
                        'gold-soft': '#f5e3b5',
                        cream: '#fdfaf3',
                        'egg-shell': '#f9f3e7',
                    },
                    fontFamily: {
                        serif: ['"Playfair Display"', 'serif'],
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft-xl': '0 24px 60px rgba(15, 23, 42, 0.18)',
                    },
                },
            },
        };
    </script>

    <style>
        body {
            font-family: theme('fontFamily.sans');
        }

        .fade-in-up {
            opacity: 0;
            transform: translateY(16px);
            animation: fadeInUp 0.8s ease-out forwards;
            animation-delay: var(--delay, 0s);
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .glass-card {
            background: radial-gradient(circle at top left, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.6));
            backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-cream via-egg-shell to-white text-slate-900 antialiased">
    <!-- Navbar -->
    <header class="fixed inset-x-0 top-0 z-40 border-b border-white/40 bg-white/70 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3 md:px-6 lg:px-0">
            <div class="flex items-center gap-3">
                @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                    <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-9 w-9 flex-shrink-0 rounded-full object-contain bg-white/80 shadow-md">
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-tr from-gold to-gold-soft shadow-md">
                        <span class="text-lg">🥚</span>
                    </div>
                @endif
                <div>
                    <p class="text-sm font-medium tracking-[0.18em] text-slate-500 uppercase">{{ Str::words($businessName ?? 'Egg Supply', 1) }}</p>
                    <p class="font-serif text-lg font-semibold tracking-tight text-slate-900">{{ $businessName ?? 'Egg Supply' }}</p>
                </div>
            </div>

            <button id="navToggle"
                    class="inline-flex items-center rounded-full border border-slate-200 bg-white/70 px-3 py-1.5 text-xs font-medium text-slate-700 shadow-sm transition hover:border-gold hover:text-gold md:hidden">
                <span>Menu</span>
                <svg class="ml-1.5 h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>

            <div id="navMenu" class="absolute right-4 top-16 w-56 rounded-2xl border border-white/70 bg-white/90 p-3 text-sm shadow-xl md:static md:flex md:w-auto md:items-center md:gap-8 md:border-0 md:bg-transparent md:p-0 md:shadow-none hidden">
                <a href="#hero" class="block rounded-full px-3 py-1.5 text-xs font-medium tracking-wide text-slate-700 hover:text-gold md:px-0 md:py-0">Home</a>
                <a href="#about" class="mt-1 block rounded-full px-3 py-1.5 text-xs font-medium tracking-wide text-slate-700 hover:text-gold md:mt-0 md:px-0 md:py-0">About</a>
                <a href="#products" class="mt-1 block rounded-full px-3 py-1.5 text-xs font-medium tracking-wide text-slate-700 hover:text-gold md:mt-0 md:px-0 md:py-0">Products</a>
                <a href="#why-us" class="mt-1 block rounded-full px-3 py-1.5 text-xs font-medium tracking-wide text-slate-700 hover:text-gold md:mt-0 md:px-0 md:py-0">Why Us</a>
                <a href="{{ route('egg.system') }}" class="mt-1 block rounded-full px-3 py-1.5 text-xs font-medium tracking-wide text-slate-700 hover:text-gold md:mt-0 md:px-0 md:py-0">Inventory System</a>
                <a href="#contact" class="mt-2 block rounded-full bg-gradient-to-r from-gold to-gold-soft px-4 py-1.5 text-xs font-semibold tracking-wide text-slate-900 shadow-sm transition hover:shadow-md md:mt-0">
                    Order Now
                </a>
            </div>
        </nav>
    </header>

    <main class="pt-24 md:pt-32">
        <!-- Hero -->
        <section id="hero" class="mx-auto flex max-w-6xl flex-col gap-12 px-4 pb-16 md:flex-row md:items-center md:gap-16 md:px-6 lg:px-0 lg:pb-24">
            <div class="md:w-1/2 fade-in-up" style="--delay: 0.05s">
                <p class="mb-3 text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">{{ $businessAddress ?? 'San Jose, Batangas' }}</p>
                <h1 class="font-serif text-4xl font-semibold leading-tight text-slate-900 sm:text-5xl lg:text-6xl">
                    Premium Farm-Fresh
                    <span class="bg-gradient-to-r from-gold to-gold-soft bg-clip-text text-transparent">Eggs</span>
                </h1>
                <p class="mt-5 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    Carefully selected, naturally fresh eggs delivered daily from trusted local farms in {{ $businessAddress ?? 'San Jose, Batangas' }}.
                    Curated for discerning households, cafés, bakeries, and resellers who value consistent quality.
                </p>

                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#contact"
                       class="inline-flex items-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-7 py-3 text-sm font-semibold text-slate-900 shadow-soft-xl transition hover:shadow-[0_18px_40px_rgba(148,126,64,0.5)]">
                        Order Premium Eggs
                        <svg class="ml-2 h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                            <path d="M5 10h8M11 6l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="#products"
                       class="inline-flex items-center rounded-full border border-slate-300/80 bg-white/70 px-6 py-3 text-sm font-medium text-slate-800 shadow-sm transition hover:border-gold hover:text-gold">
                        View Product Range
                    </a>
                </div>

                <div class="mt-8 flex flex-wrap items-center gap-6 text-xs text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded-full bg-emerald-100 text-[0.9rem] flex items-center justify-center">✓</span>
                        <span>Hand-selected farm produce</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="h-6 w-6 rounded-full bg-amber-100 text-[0.9rem] flex items-center justify-center">✓</span>
                        <span>Freshness guaranteed daily</span>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 fade-in-up" style="--delay: 0.12s">
                <div class="relative">
                    <div class="absolute -inset-8 -z-10 rounded-[2.75rem] bg-gradient-to-br from-gold/40 via-gold-soft/60 to-white/10 blur-2xl opacity-80"></div>
                    <div class="glass-card relative overflow-hidden rounded-[2.25rem] p-1 shadow-soft-xl">
                        <div class="relative h-80 w-full overflow-hidden rounded-[2rem] bg-[radial-gradient(circle_at_top,_#fef3c7,_#f9fafb)] sm:h-96">
                            <div class="absolute inset-0 bg-[url('https://images.pexels.com/photos/806457/pexels-photo-806457.jpeg?auto=compress&cs=tinysrgb&w=1200')] bg-cover bg-center mix-blend-multiply opacity-80"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/65 via-slate-900/15 to-transparent"></div>
                            <div class="relative flex h-full flex-col justify-between p-6">
                                <div class="flex items-center justify-between text-xs font-medium text-amber-100/90">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-white/10 px-3 py-1 backdrop-blur">
                                        <span class="text-base">💎</span> Luxury Selection
                                    </span>
                                    <span class="rounded-full bg-white/10 px-3 py-1 backdrop-blur">Farm-to-table</span>
                                </div>
                                <div>
                                    <p class="text-[0.75rem] uppercase tracking-[0.25em] text-amber-100/80">{{ $businessName ?? 'Egg Supply' }}</p>
                                    <p class="mt-2 font-serif text-2xl font-semibold text-white sm:text-3xl">
                                        Curated eggs for<br>homes, cafés & bakeries.
                                    </p>
                                    <p class="mt-3 max-w-xs text-xs leading-relaxed text-amber-100/90">
                                        Trusted by local households and resellers for consistent size, shell strength, and yolk richness.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About -->
        <section id="about" class="mx-auto max-w-6xl px-4 py-12 md:px-6 lg:px-0 lg:py-16">
            <div class="grid gap-10 md:grid-cols-2 md:items-center">
                <div class="fade-in-up" style="--delay: 0.05s">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">About</p>
                    <h2 class="mt-3 font-serif text-3xl font-semibold text-slate-900 sm:text-4xl">
                        {{ $businessName ?? 'Egg Supply' }}
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600 sm:text-base">
                        Based in <span class="font-medium text-slate-800">{{ $businessAddress ?? 'San Jose, Batangas' }}</span>, {{ $businessName ?? 'Egg Supply' }} is
                        committed to bringing only the finest farm-fresh eggs to your table. Every tray is carefully chosen, ensuring
                        cleanliness, uniform sizing, and exceptional freshness.
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">
                        We proudly serve local households, sari-sari stores, bakeries, cafés, and resellers who rely on our consistent
                        quality and dependable service. Fresh stocks arrive daily, so your eggs are always collected, packed, and
                        distributed at peak freshness.
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base">
                        Our reputation is built on trust, transparency, and an unwavering dedication to quality—from farm selection to
                        final delivery.
                    </p>
                </div>

                <div class="fade-in-up md:pl-10" style="--delay: 0.12s">
                    <div class="glass-card rounded-3xl p-6 shadow-soft-xl">
                        <h3 class="text-sm font-semibold tracking-wide text-slate-700">At a Glance</h3>
                        <dl class="mt-4 space-y-4 text-sm text-slate-600">
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-800">Location</dt>
                                <dd class="text-right">{{ $businessAddress ?? 'San Jose, Batangas, Philippines' }}</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-800">Specialty</dt>
                                <dd class="text-right">Premium table eggs for retail & wholesale</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-800">Customers</dt>
                                <dd class="text-right">Households, resellers, eateries, bakeries</dd>
                            </div>
                            <div class="flex items-start justify-between gap-4">
                                <dt class="font-medium text-slate-800">Promise</dt>
                                <dd class="text-right">Freshness, consistency, and honest service</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </section>

        <!-- Premium Products -->
        <section id="products" class="mx-auto max-w-6xl px-4 py-12 md:px-6 lg:px-0 lg:py-16">
            <div class="fade-in-up text-center" style="--delay: 0.05s">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Products</p>
                <h2 class="mt-3 font-serif text-3xl font-semibold text-slate-900 sm:text-4xl">
                    Premium Egg Selection
                </h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    Every egg type is curated for specific needs—whether you are serving family breakfasts or running a growing food
                    business, we have the right selection for you.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <article class="glass-card fade-in-up group rounded-3xl p-6 shadow-soft-xl transition hover:-translate-y-1 hover:shadow-[0_28px_80px_rgba(15,23,42,0.25)]" style="--delay: 0.08s">
                    <div class="flex items-center justify-between">
                        <h3 class="font-serif text-xl font-semibold text-slate-900">Regular Eggs</h3>
                        <span class="rounded-full border border-gold/40 bg-amber-50/60 px-3 py-1 text-xs font-medium text-amber-800">
                            Everyday Choice
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        Ideal for daily home cooking, breakfast trays, and sari-sari store retail. Balanced in size, clean shells, and
                        dependable freshness.
                    </p>
                    <ul class="mt-4 space-y-1.5 text-xs text-slate-500">
                        <li>· Carefully graded for consistency</li>
                        <li>· Perfect for household consumption</li>
                        <li>· Available in trays and bulk</li>
                    </ul>
                </article>

                <article class="glass-card fade-in-up group rounded-3xl p-6 shadow-soft-xl ring-1 ring-gold/40 transition hover:-translate-y-1 hover:shadow-[0_30px_90px_rgba(148,126,64,0.55)]" style="--delay: 0.12s">
                    <div class="flex items-center justify-between">
                        <h3 class="font-serif text-xl font-semibold text-slate-900">Jumbo Eggs</h3>
                        <span class="rounded-full bg-gradient-to-r from-gold to-gold-soft px-3 py-1 text-xs font-semibold text-slate-900 shadow-sm">
                            Premium Pick
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        Large, visually impressive eggs with rich yolks—perfect for bakeries, cafés, and premium culinary creations.
                    </p>
                    <ul class="mt-4 space-y-1.5 text-xs text-slate-500">
                        <li>· Bigger size for better yield</li>
                        <li>· Excellent for baking & pastries</li>
                        <li>· Ideal for premium food service</li>
                    </ul>
                </article>

                <article class="glass-card fade-in-up group rounded-3xl p-6 shadow-soft-xl transition hover:-translate-y-1 hover:shadow-[0_28px_80px_rgba(15,23,42,0.25)]" style="--delay: 0.16s">
                    <div class="flex items-center justify-between">
                        <h3 class="font-serif text-xl font-semibold text-slate-900">Bulk & Wholesale</h3>
                        <span class="rounded-full border border-emerald-300/60 bg-emerald-50/80 px-3 py-1 text-xs font-medium text-emerald-800">
                            For Businesses
                        </span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-600">
                        Tailored supply programs for resellers, eateries, and institutional clients who need reliable, steady volume.
                    </p>
                    <ul class="mt-4 space-y-1.5 text-xs text-slate-500">
                        <li>· Flexible bulk ordering options</li>
                        <li>· Reliable weekly or daily dispatch</li>
                        <li>· Best suited for growing operations</li>
                    </ul>
                </article>
            </div>
        </section>

        <!-- Why Choose Us -->
        <section id="why-us" class="mx-auto max-w-6xl px-4 py-12 md:px-6 lg:px-0 lg:py-16">
            <div class="fade-in-up text-center" style="--delay: 0.05s">
                <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Why Choose Us</p>
                <h2 class="mt-3 font-serif text-3xl font-semibold text-slate-900 sm:text-4xl">
                    A Supplier You Can Trust
                </h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-slate-600 sm:text-base">
                    From farm selection to final delivery, every step is managed with care to ensure you receive only the finest eggs.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-4">
                <div class="fade-in-up glass-card rounded-3xl p-5 text-center shadow-soft-xl" style="--delay: 0.08s">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-amber-50 text-xl">
                        🥚
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900">Farm Fresh Daily</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                        Eggs are sourced and rotated daily so you always receive stock at peak freshness.
                    </p>
                </div>

                <div class="fade-in-up glass-card rounded-3xl p-5 text-center shadow-soft-xl" style="--delay: 0.12s">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-sky-50 text-xl">
                        🚚
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900">Reliable Distribution</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                        Timely deliveries within San Jose and nearby areas, tailored to your volume needs.
                    </p>
                </div>

                <div class="fade-in-up glass-card rounded-3xl p-5 text-center shadow-soft-xl" style="--delay: 0.16s">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-indigo-50 text-xl">
                        💎
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900">Premium Quality Assurance</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                        Hand-checked for cracks, cleanliness, and size before packing and dispatch.
                    </p>
                </div>

                <div class="fade-in-up glass-card rounded-3xl p-5 text-center shadow-soft-xl" style="--delay: 0.2s">
                    <div class="mx-auto flex h-11 w-11 items-center justify-center rounded-full bg-emerald-50 text-xl">
                        🤝
                    </div>
                    <h3 class="mt-4 text-sm font-semibold text-slate-900">Trusted Local Supplier</h3>
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                        Built on word-of-mouth, repeat clients, and long-term community relationships.
                    </p>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section id="contact" class="mx-auto max-w-6xl px-4 pb-16 pt-4 md:px-6 lg:px-0 lg:pb-20">
            <div class="grid gap-10 md:grid-cols-[1.1fr_1fr] md:items-start">
                <div class="fade-in-up" style="--delay: 0.05s">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Contact</p>
                    <h2 class="mt-3 font-serif text-3xl font-semibold text-slate-900 sm:text-4xl">
                        Place an order or send an inquiry.
                    </h2>
                    <p class="mt-3 max-w-xl text-sm leading-relaxed text-slate-600 sm:text-base">
                        Share your preferred quantity, egg type, and target delivery schedule. We will respond with availability,
                        pricing, and next steps.
                    </p>

                    <div class="mt-6 rounded-2xl border border-gold/30 bg-amber-50/60 p-4 text-xs text-amber-900 sm:text-sm">
                        <p class="font-semibold text-amber-950">Contact Persons</p>
                        <p class="mt-1">{{ $businessName ?? 'Egg Supply' }}</p>
                        <p>{{ $businessAddress ?? 'San Jose, Batangas' }}</p>
                        @if(!empty($contactInfo))
                            <p class="mt-1 text-slate-600">{{ $contactInfo }}</p>
                        @endif
                        <a href="https://www.facebook.com/michellenicola.matibag/" target="_blank" rel="noopener noreferrer"
                           class="mt-2 inline-flex items-center text-xs font-medium text-amber-900 underline underline-offset-2">
                            Connect via Facebook
                            <svg class="ml-1.5 h-3.5 w-3.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                <path d="M5 11l5-5 5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                      stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="fade-in-up md:pl-6" style="--delay: 0.12s">
                    <div class="glass-card rounded-3xl p-6 shadow-soft-xl">
                        <form id="contactForm" class="space-y-4">
                            <div>
                                <label for="name" class="text-xs font-medium text-slate-700 sm:text-sm">Name</label>
                                <input id="name" name="name" type="text" autocomplete="name"
                                       class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                                       placeholder="Your full name" required>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="phone" class="text-xs font-medium text-slate-700 sm:text-sm">Contact Number</label>
                                    <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel"
                                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                                           placeholder="Your contact number" required>
                                </div>
                                <div>
                                    <label for="email" class="text-xs font-medium text-slate-700 sm:text-sm">Email Address</label>
                                    <input id="email" name="email" type="email" autocomplete="email"
                                           class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                                           placeholder="your@email.com" required>
                                </div>
                            </div>

                            <div>
                                <label for="message" class="text-xs font-medium text-slate-700 sm:text-sm">Message</label>
                                <textarea id="message" name="message" rows="4"
                                          class="mt-1 w-full rounded-2xl border border-slate-200 bg-white/80 px-3 py-2.5 text-sm text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-gold focus:ring-1 focus:ring-gold"
                                          placeholder="Share your egg requirements, quantity, and preferred schedule." required></textarea>
                            </div>

                            <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                                <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-gold to-gold-soft px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-soft-xl transition hover:shadow-[0_18px_40px_rgba(148,126,64,0.55)] sm:w-auto">
                                    Submit Inquiry
                                </button>
                                <a href="https://www.facebook.com/michellenicola.matibag/" target="_blank" rel="noopener noreferrer"
                                   class="inline-flex w-full items-center justify-center rounded-full border border-slate-300/80 bg-white/80 px-6 py-2.5 text-xs font-medium text-slate-800 shadow-sm transition hover:border-gold hover:text-gold sm:w-auto">
                                    Message on Facebook
                                </a>
                            </div>

                            <p class="pt-1 text-[0.7rem] leading-relaxed text-slate-400">
                                By submitting this form, you agree that we may contact you via your provided details regarding egg
                                supply, pricing, and availability.
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gold/20 bg-white/80">
        <div class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-4 py-5 text-[0.75rem] text-slate-500 md:flex-row md:px-6 lg:px-0">
            <div class="flex items-center gap-2 text-center md:text-left">
                <span class="h-px w-6 bg-gold/60"></span>
                <span>© {{ now()->year }} {{ $businessName ?? 'Egg Supply' }} · {{ $businessAddress ?? 'San Jose, Batangas' }}</span>
            </div>
            <p class="text-center md:text-right">
                Premium farm-fresh eggs for households, resellers, and businesses.
            </p>
        </div>
    </footer>

    <!-- Axios & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navToggle = document.getElementById('navToggle');
            const navMenu = document.getElementById('navMenu');

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

            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (tokenMeta) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
            }
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            const contactForm = document.getElementById('contactForm');
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
                            confirmButtonColor: '#D4AF37',
                        });
                        return;
                    }

                    try {
                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                        }

                        await axios.post('{{ route('contact.submit') }}', { name, phone, email, message });

                        contactForm.reset();

                        Swal.fire({
                            icon: 'success',
                            title: 'Inquiry sent',
                            text: 'Thank you for reaching out. We will get back to you as soon as possible.',
                            confirmButtonColor: '#D4AF37',
                        });
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong',
                            text: 'We could not submit your inquiry. Please try again or message us on Facebook.',
                            confirmButtonColor: '#D4AF37',
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
</body>
</html>

