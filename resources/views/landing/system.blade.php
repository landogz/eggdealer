<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Professional egg inventory and sales system by {{ $businessName ?? 'Egg Supply' }}. Track stock in, stock out, cracked eggs, pricing, and reports in one dashboard.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url('/egg-system') }}">

    <title>Egg Inventory & Sales System | {{ $businessName ?? 'Egg Supply' }}</title>
    @if(!empty($logoUrl) && in_array('favicon', $logoPositions ?? [], true))
        <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif

    <meta property="og:title" content="Egg Inventory & Sales System | {{ $businessName ?? 'Egg Supply' }}">
    <meta property="og:description" content="Track stock in, sales, cracked eggs, pricing, and inventory. One dashboard for your egg business.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/egg-system') }}">

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
                    borderRadius: { '3xl': '1.5rem' },
                    boxShadow: { 'float-lg': '0 20px 45px rgba(15,23,42,0.18)' },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                        display: ['"Baloo 2"', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease; }
        .hover-lift:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(15,23,42,0.12); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-cream via-[#FFF1BF] to-white text-slate-900 antialiased">
    <!-- Nav -->
    <header class="sticky top-0 z-40 border-b border-white/60 bg-white/80 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3 md:px-6" aria-label="Main navigation">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                    <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-9 w-9 flex-shrink-0 rounded-full object-contain bg-white/80 shadow-md">
                @else
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-yolk to-amber-300 shadow-md">
                        <span class="text-lg" aria-hidden="true">📊</span>
                    </div>
                @endif
                <div class="leading-tight">
                    <p class="font-display text-base font-semibold tracking-tight">Egg System</p>
                    <p class="text-[0.7rem] uppercase tracking-[0.22em] text-slate-500">{{ $businessName ?? 'Egg Supply' }}</p>
                </div>
            </a>

            <button id="sysNavToggle" type="button" aria-label="Toggle menu"
                    class="inline-flex items-center rounded-full border border-yellow-200 bg-white/80 px-3 py-1.5 text-xs font-medium text-slate-800 shadow-sm transition hover:border-yolk hover:text-yolk md:hidden">
                <span>Menu</span>
                <svg class="ml-1.5 h-4 w-4" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M3 6h14M3 10h14M3 14h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </button>

            <div id="sysNavMenu"
                 class="absolute right-4 top-16 w-60 rounded-3xl border border-yellow-100 bg-white shadow-xl md:static md:flex md:w-auto md:items-center md:gap-6 md:border-0 md:bg-transparent md:p-0 md:shadow-none hidden">
                <a href="#hero" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Overview</a>
                <a href="#modules" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Modules</a>
                <a href="#who-for" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Who it’s for</a>
                <a href="{{ url('/') }}#genz-contact" class="block px-4 py-2 text-xs font-semibold text-slate-700 hover:text-yolk md:px-0 md:py-0">Contact</a>
                <a href="{{ route('login') }}" class="m-3 block rounded-full bg-gradient-to-r from-yolk to-amber-300 px-4 py-1.5 text-center text-[0.7rem] font-semibold text-slate-900 shadow-sm hover:shadow-md md:m-0 md:px-4 md:text-xs">
                    Admin Login
                </a>
            </div>
        </nav>
    </header>

    <main class="mx-auto max-w-5xl px-4 pb-20 pt-8 md:px-6">
        <!-- Hero -->
        <section id="hero" class="flex flex-col gap-10 pt-4 md:flex-row md:items-center md:gap-12">
            <div class="space-y-4 md:w-1/2">
                <p class="inline-flex items-center gap-2 rounded-full bg-white/70 px-3 py-1 text-[0.7rem] font-medium text-slate-700 shadow-sm">
                    <span class="h-1.5 w-1.5 rounded-full bg-yolk"></span>
                    Backed by your live data
                </p>
                <h1 class="font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">
                    Inventory & Sales
                    <br>
                    <span class="bg-gradient-to-r from-yolk to-amber-400 bg-clip-text text-transparent">in one dashboard</span>
                </h1>
                <p class="max-w-md text-sm leading-relaxed text-slate-600 sm:text-base">
                    The same system we use to run <strong>{{ $businessName ?? 'Egg Supply' }}</strong>: stock in, stock out, cracked eggs, pricing by size, and reports—all in one place. No spreadsheets, no guesswork.
                </p>

                <div class="flex flex-wrap gap-3 pt-2 text-[0.75rem] text-slate-500">
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">📦 Stock in & out</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">💰 Pricing by size</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-white/80 px-3 py-1 shadow-sm">📊 Reports & charts</span>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center rounded-full bg-gradient-to-r from-yolk to-amber-300 px-6 py-2.5 text-sm font-semibold text-slate-900 shadow-float-lg hover:shadow-[0_22px_50px_rgba(148,126,64,0.25)]">
                        Login to dashboard
                    </a>
                    <a href="{{ url('/') }}#genz-contact"
                       class="inline-flex items-center rounded-full border border-yellow-200 bg-white/80 px-6 py-2.5 text-xs font-semibold text-slate-800 shadow-sm hover:border-yolk hover:text-yolk">
                        Get access / Contact us
                    </a>
                </div>
            </div>

            <div class="relative md:w-1/2">
                <div class="absolute -left-4 -top-4 h-20 w-20 rounded-full bg-gradient-to-tr from-yolk/40 to-white/0 blur-2xl"></div>
                <div class="absolute -right-6 bottom-2 h-16 w-16 rounded-full bg-gradient-to-tr from-amber-300/50 to-white/0 blur-2xl"></div>
                <div class="relative rounded-3xl bg-gradient-to-br from-cream via-[#FFEFD3] to-white p-1 shadow-float-lg">
                    <div class="relative overflow-hidden rounded-3xl bg-white/85 p-5">
                        @if(!empty($logoUrl) && in_array('landing', $logoPositions ?? [], true))
                            <div class="absolute -right-6 -top-6 flex h-20 w-20 items-center justify-center overflow-hidden rounded-full bg-white/90 shadow-lg p-1">
                                <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-full w-full object-contain">
                            </div>
                        @else
                            <div class="absolute -right-6 -top-6 flex h-20 w-20 items-center justify-center rounded-full bg-gradient-to-br from-yolk to-amber-300 shadow-lg">
                                <span class="text-3xl" aria-hidden="true">📈</span>
                            </div>
                        @endif
                        <p class="text-[0.75rem] font-semibold uppercase tracking-[0.2em] text-amber-700/90">
                            {{ $businessName ?? 'Egg Supply' }} · Admin
                        </p>
                        <p class="mt-2 font-display text-xl font-semibold text-slate-900">
                            One login. Full control.
                        </p>
                        <p class="mt-3 text-xs leading-relaxed text-slate-600">
                            Dashboard with today’s sales, stock in/out, cracked eggs, and monthly revenue. Inventory by egg size, pricing, and settings—all connected to your data.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- System modules (matches backend) -->
        <section id="modules" class="mt-16">
            <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">What’s inside the system</h2>
            <p class="mt-2 text-sm text-slate-600 sm:text-base">
                Every module in the admin is built for egg businesses. Log in to use them.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">📊</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Dashboard</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Total stock, today’s sales, stock in/out today, cracked eggs, monthly revenue. Sales overview (today, yesterday, this month, last month), inventory by size (pieces & trays), damage %, revenue vs expenses.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">📐</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Egg Sizes</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Define egg sizes (e.g. Regular, Jumbo). Used across inventory, pricing, and the main landing page product slider.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">💰</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Pricing</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Set prices per piece, per tray, bulk, wholesale, reseller by egg size. Effective dates and status. Powers the landing page product prices.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">📥</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Stock In</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Record deliveries: egg size, quantity (pieces/trays), unit cost, total cost, delivery date. Updates inventory automatically.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">🚛</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Stock Out</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Record sales: egg size, quantity, unit price, total amount, profit, transaction date. Deducts from inventory and feeds dashboard sales.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">❌</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Cracked Eggs</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Log cracked/damaged eggs by size and date. Tracked on the dashboard and in damage reports.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">📦</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Inventory</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Live stock by egg size (pieces & trays). Low-stock alerts. View and adjust minimum alert levels.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">⚙️</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Settings</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Business name, address, contact info. Default tray size, currency, tax rate. These drive the main landing page and admin branding.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">📋</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Reports</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Export and analyze sales, stock in/out, and performance over time.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">👥</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Users</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        Manage admin and inventory users. Control who can access the dashboard and record stock.
                    </p>
                </article>
                <article class="hover-lift rounded-3xl bg-white/95 p-4 shadow-md">
                    <div class="text-xl">🔔</div>
                    <h3 class="mt-2 text-sm font-semibold text-slate-900">Notifications</h3>
                    <p class="mt-1 text-xs leading-relaxed text-slate-600">
                        In-app notifications for low stock, new stock in, and other alerts. Mark as read from the dashboard.
                    </p>
                </article>
            </div>
        </section>

        <!-- Who it's for -->
        <section id="who-for" class="mt-16">
            <h2 class="font-display text-2xl font-semibold text-slate-900 sm:text-3xl">Who it’s for</h2>
            <p class="mt-2 text-sm text-slate-600 sm:text-base">
                Egg resellers, wholesalers, and small businesses that want one place for stock, sales, and reports—without a complicated POS.
            </p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-3xl bg-white/95 p-5 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">Resellers & suppliers</h3>
                    <ul class="mt-2 space-y-1.5 text-xs text-slate-600">
                        <li>· Track stock in from farms and stock out to customers</li>
                        <li>· See today’s and monthly sales at a glance</li>
                        <li>· Avoid over- or under-ordering with live inventory</li>
                    </ul>
                </div>
                <div class="rounded-3xl bg-white/95 p-5 shadow-md">
                    <h3 class="text-sm font-semibold text-slate-900">Stores, bakeries & cafés</h3>
                    <ul class="mt-2 space-y-1.5 text-xs text-slate-600">
                        <li>· Record egg usage and sales by size</li>
                        <li>· Monitor cracked/damaged eggs</li>
                        <li>· Use pricing and reports to plan orders</li>
                    </ul>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-yellow-100 bg-white/80 p-4 text-[0.75rem] text-slate-600">
                <p>
                    To get access, contact us from the <a href="{{ url('/') }}#genz-contact" class="font-semibold text-slate-800 underline underline-offset-2 hover:text-yolk">main landing page</a> and mention the <strong>inventory & sales system</strong>. We’ll set you up with login and a quick walkthrough.
                </p>
            </div>
        </section>
    </main>

    <footer class="border-t border-yellow-100 bg-cream/90">
        <div class="mx-auto flex max-w-5xl flex-col items-center justify-between gap-2 px-4 py-4 text-[0.75rem] text-slate-600 md:flex-row md:px-6">
            <p>Egg inventory & sales system by {{ $businessName ?? 'Egg Supply' }}@if(!empty($businessAddress)) · {{ $businessAddress }}@endif.</p>
            <div class="flex items-center gap-4">
                <a href="{{ url('/') }}" class="text-slate-500 hover:text-yolk">Main landing</a>
                <a href="{{ route('login') }}" class="font-semibold text-slate-800 hover:text-yolk">Admin Login</a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var navToggle = document.getElementById('sysNavToggle');
            var navMenu = document.getElementById('sysNavMenu');
            if (navToggle && navMenu) {
                navToggle.addEventListener('click', function () { navMenu.classList.toggle('hidden'); });
                navMenu.querySelectorAll('a[href^="#"]').forEach(function (link) {
                    link.addEventListener('click', function () {
                        if (window.innerWidth < 768) navMenu.classList.add('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>
