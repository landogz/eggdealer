<!DOCTYPE html>
<html lang="en" x-data="adminShell()" x-init="init()" :class="{'scroll-smooth': true, 'dark': darkMode}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') · {{ $businessName ?? 'Egg Supply' }}</title>
    @if(!empty($logoUrl) && in_array('favicon', $logoPositions ?? [], true))
        <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        yolk: '#FFD84D',
                        'soft-cream': '#FFF8E7',
                        'light-brown': '#C89B3C',
                        charcoal: '#1E1E2F',
                        gold: '#D4AF37',
                        'gold-soft': '#f5e3b5',
                        cream: '#fdfaf3',
                    },
                    fontFamily: { sans: ['Inter', 'system-ui', 'sans-serif'] },
                    boxShadow: {
                        'soft-xl': '0 18px 45px rgba(15, 23, 42, 0.12)',
                    },
                },
            },
        };
    </script>
    <script>
        (function () {
            var key = 'egg-admin-dark';
            try {
                if (localStorage.getItem(key) === 'true') {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
            } catch (e) {}
        })();
    </script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-b from-soft-cream via-white to-soft-cream/70 text-slate-900 antialiased transition-colors duration-300 ease-out dark:bg-charcoal dark:text-slate-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform border-r border-slate-200/80 bg-white/90 shadow-soft-xl backdrop-blur-xl transition-transform duration-300 ease-out dark:border-slate-800 dark:bg-slate-900/95 lg:translate-x-0 lg:static"
               :class="{'-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen}">
            <div class="flex h-full flex-col">
                <div class="flex h-14 items-center gap-3 border-b border-slate-100/80 px-4 dark:border-slate-800/80">
                    @if(!empty($logoUrl) && in_array('sidebar', $logoPositions ?? [], true))
                        <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-9 w-9 flex-shrink-0 rounded-full object-contain bg-white/80 ring-2 ring-amber-100/70 dark:bg-slate-800/80 dark:ring-slate-700">
                    @else
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-tr from-yolk to-orange-400 shadow-md ring-2 ring-amber-100/70">
                            <span class="text-xl">🥚</span>
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.25em] text-slate-500 dark:text-slate-400">Egg System</p>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 truncate">Admin</p>
                    </div>
                </div>
                <nav class="flex-1 overflow-y-auto px-3 py-4">
                    <ul class="space-y-0.5 text-sm">
                        <li>
                            <a href="{{ route('admin.dashboard') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📊</span>
                                <span>Dashboard</span>
                                @if (request()->routeIs('admin.dashboard'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li x-data="{ open: {{ request()->routeIs('admin.egg-sizes.*') || request()->routeIs('admin.egg-prices.*') ? 'true' : 'false' }} }">
                            <button type="button"
                                    @click="open = !open"
                                    class="flex w-full items-center gap-3 rounded-2xl px-3 py-2.5 text-left text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.egg-sizes.*') || request()->routeIs('admin.egg-prices.*') ? 'bg-slate-900/5 font-semibold text-slate-900 dark:bg-slate-800/70 dark:text-slate-50' : '' }}">
                                <span aria-hidden="true">📐</span>
                                <span>Sizes &amp; Pricing</span>
                                <span class="ml-auto transition-transform duration-200" :class="open ? 'rotate-180' : ''">
                                    <svg class="h-4 w-4 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </span>
                            </button>
                            <ul x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="mt-0.5 ml-4 space-y-0.5 border-l-2 border-slate-200/80 pl-3 dark:border-slate-700">
                                <li>
                                    <a href="{{ route('admin.egg-sizes.index') }}"
                                       class="flex items-center gap-2 rounded-xl px-2.5 py-2 text-[0.8rem] text-slate-600 transition hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-50 {{ request()->routeIs('admin.egg-sizes.*') ? 'font-medium text-amber-700 dark:text-amber-300' : '' }}">
                                        <span aria-hidden="true">📐</span> Egg Sizes
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.egg-prices.index') }}"
                                       class="flex items-center gap-2 rounded-xl px-2.5 py-2 text-[0.8rem] text-slate-600 transition hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-50 {{ request()->routeIs('admin.egg-prices.*') ? 'font-medium text-amber-700 dark:text-amber-300' : '' }}">
                                        <span aria-hidden="true">💰</span> Pricing
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('admin.stock-in.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.stock-in.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📥</span>
                                Stock In
                                @if (request()->routeIs('admin.stock-in.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.stock-out.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.stock-out.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📤</span>
                                Stock Out
                                @if (request()->routeIs('admin.stock-out.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.cracked-eggs.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.cracked-eggs.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">❌</span>
                                <span>Cracked Eggs</span>
                                @if (request()->routeIs('admin.cracked-eggs.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.inventory.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.inventory.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📦</span>
                                <span>Inventory</span>
                                @if (request()->routeIs('admin.inventory.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.feeds.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.feeds.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">🌾</span>
                                <span>Feeds</span>
                                @if (request()->routeIs('admin.feeds.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li class="mt-2 pt-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-500">Analytics</li>
                        <li>
                            <a href="{{ route('admin.reports.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.reports.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📈</span>
                                <span>Reports</span>
                                @if (request()->routeIs('admin.reports.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li class="mt-2 pt-2 text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-slate-500 dark:text-slate-500">System</li>
                        <li>
                            <a href="{{ route('admin.users.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.users.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">👥</span>
                                <span>Users</span>
                                @if (request()->routeIs('admin.users.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.settings.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.settings.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">⚙️</span>
                                <span>Settings</span>
                                @if (request()->routeIs('admin.settings.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.activity-log.index') }}"
                               class="flex items-center gap-3 rounded-2xl px-3 py-2.5 text-slate-700 transition-all duration-300 hover:bg-yolk/10 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-50 {{ request()->routeIs('admin.activity-log.*') ? 'bg-gradient-to-r from-yolk/80 to-orange-400/80 text-slate-900 shadow-soft-xl dark:text-slate-900' : '' }}">
                                <span aria-hidden="true">📜</span>
                                <span>Activity log</span>
                                @if (request()->routeIs('admin.activity-log.*'))
                                    <span class="ml-auto h-1 w-6 rounded-full bg-white/80"></span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="border-t border-slate-100 p-3 space-y-2">
                    @if(!empty($settingsAddress) || !empty($settingsContactInfo))
                        <div class="rounded-2xl bg-slate-50/80 px-3 py-2 text-[0.65rem] text-slate-500 dark:bg-slate-800/50 dark:text-slate-400">
                            @if(!empty($settingsAddress))
                                <p class="font-medium text-slate-600 dark:text-slate-300">📍 {{ $businessAddress }}</p>
                            @endif
                            @if(!empty($settingsContactInfo))
                                <p class="mt-1">{{ $contactInfo }}</p>
                            @endif
                        </div>
                    @endif
                    <a href="{{ url('/') }}" class="flex items-center gap-3 rounded-2xl px-3 py-2 text-xs text-slate-500 hover:bg-slate-50 hover:text-slate-700 dark:text-slate-400 dark:hover:bg-slate-800/70 dark:hover:text-slate-100">
                        ← Back to site
                    </a>
                </div>
            </div>
        </aside>

        <!-- Mobile sidebar backdrop -->
        <div id="sidebarBackdrop" class="fixed inset-0 z-30 bg-slate-900/40 lg:hidden" x-show="sidebarOpen" x-transition.opacity aria-hidden="true" @click="sidebarOpen = false"></div>

        <div class="flex flex-1 flex-col min-w-0">
            <!-- Header -->
            <header
                class="sticky top-0 z-20 flex h-14 items-center justify-between border-b border-slate-200/80 bg-white/90 px-4 backdrop-blur-xl transition-colors duration-300 lg:px-6 dark:border-slate-800 dark:bg-slate-900/90">
                <div class="flex items-center gap-3">
                    <button type="button"
                            class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-600 hover:bg-slate-100 lg:hidden dark:text-slate-200 dark:hover:bg-slate-800"
                            aria-label="Toggle menu"
                            @click="sidebarOpen = !sidebarOpen">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2 min-w-0">
                        @if(!empty($logoUrl) && in_array('header', $logoPositions ?? [], true))
                            <img src="{{ $logoUrl }}" alt="" class="h-8 w-8 flex-shrink-0 object-contain rounded-lg">
                        @endif
                        <div class="flex flex-col min-w-0">
                            <span class="text-xs font-medium text-slate-500 dark:text-slate-400">{{ $businessName ?? 'Egg Supply' }}</span>
                            <h1 class="text-sm font-semibold text-slate-900 dark:text-slate-50 truncate">
                                @yield('header_title', 'Dashboard')
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <!-- Dark mode toggle -->
                    <button type="button"
                            class="relative flex h-8 w-14 items-center rounded-full bg-slate-100 px-1 text-[0.65rem] font-semibold text-slate-500 shadow-sm transition-all hover:bg-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                            @click="toggleDarkMode()">
                        <span class="flex-1 text-center">☀️</span>
                        <span class="flex-1 text-center">🌙</span>
                        <span class="absolute inset-y-0 my-1 h-6 w-6 rounded-full bg-white shadow-sm transition-transform duration-300"
                              :class="darkMode ? 'translate-x-6' : 'translate-x-0'"></span>
                    </button>
                    <!-- Notifications (hidden for now) -->
                    <div class="relative hidden" x-data="adminNotifications()" x-init="load()">
                        <button type="button"
                                class="relative flex h-9 w-9 items-center justify-center rounded-xl bg-white/80 text-slate-600 shadow-sm ring-1 ring-slate-200/80 transition hover:-translate-y-0.5 hover:shadow-md dark:bg-slate-800 dark:text-slate-200 dark:ring-slate-700"
                                @click="open = !open; if (open) load();">
                            <span class="text-base">🔔</span>
                            <span x-show="unreadCount > 0"
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="absolute -right-0.5 -top-0.5 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[0.6rem] font-semibold text-white shadow"></span>
                        </button>
                        <div x-show="open" @click.away="open = false"
                             x-transition.origin.top.right
                             class="absolute right-0 mt-2 w-72 rounded-3xl border border-slate-100 bg-white/95 p-3 text-xs shadow-soft-xl backdrop-blur-xl dark:border-slate-700 dark:bg-slate-900/95">
                            <p class="mb-2 flex items-center justify-between text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                                Notifications
                                <button type="button" class="text-[0.65rem] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-300"
                                        @click="markAllRead()" x-show="unreadCount > 0">
                                    Mark all read
                                </button>
                            </p>
                            <ul class="space-y-1.5" x-show="notifications.length">
                                <template x-for="n in notifications" :key="n.id">
                                    <li class="flex items-start gap-2 rounded-2xl px-2.5 py-2"
                                        :class="{
                                            'bg-amber-50/80 dark:bg-amber-900/25': n.type === 'low_stock',
                                            'bg-emerald-50/80 dark:bg-emerald-900/20': n.type === 'sale',
                                            'bg-rose-50/80 dark:bg-rose-900/25': n.type === 'cracked',
                                            'bg-slate-50/80 dark:bg-slate-800/50': n.type !== 'low_stock' && n.type !== 'sale' && n.type !== 'cracked'
                                        }">
                                        <span class="mt-0.5 text-sm" x-text="n.type === 'low_stock' ? '📉' : (n.type === 'sale' ? '💰' : (n.type === 'cracked' ? '❌' : '📌'))"></span>
                                        <div class="min-w-0 flex-1">
                                            <p class="font-medium text-slate-800 dark:text-slate-100" x-text="n.title"></p>
                                            <p class="text-[0.68rem] text-slate-500 dark:text-slate-400" x-text="n.message"></p>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                            <p class="py-3 text-center text-[0.68rem] text-slate-500 dark:text-slate-400" x-show="!loading && !notifications.length">
                                No notifications yet.
                            </p>
                            <p class="py-2 text-center text-[0.68rem] text-slate-500 dark:text-slate-400" x-show="loading">
                                Loading…
                            </p>
                        </div>
                    </div>
                    <!-- User -->
                    <div class="flex items-center gap-2">
                        @php
                            $email = auth()->user()->email ?? 'user@example.com';
                            $initial = strtoupper(mb_substr($email, 0, 1));
                        @endphp
                        <div class="hidden text-xs text-slate-500 sm:block dark:text-slate-300 max-w-[120px] truncate md:max-w-[160px]">
                            {{ $email }}
                        </div>
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-tr from-yolk to-orange-400 text-xs font-semibold text-slate-900 shadow-sm">
                            {{ $initial }}
                        </div>
                    </div>
                    <form id="logoutForm" class="hidden sm:inline">
                        <button type="submit"
                                class="relative overflow-hidden rounded-full border border-slate-200 bg-white/90 px-3 py-1.5 text-[0.7rem] font-medium text-slate-800 shadow-sm transition-all hover:border-amber-400 hover:text-amber-700 hover:-translate-y-0.5 dark:border-slate-700 dark:bg-slate-800/90 dark:text-slate-100 dark:hover:border-amber-400">
                            <span class="relative z-10">Logout</span>
                            <span class="absolute inset-0 -z-0 translate-y-full bg-gradient-to-r from-yolk to-orange-400 opacity-0 transition-all duration-300 ease-out hover:translate-y-0 hover:opacity-100"></span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main -->
            <main class="flex-1 px-4 py-6 lg:px-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toast stack -->
    <x-admin.toast-stack />

    <!-- Mobile bottom navigation -->
    <nav class="fixed inset-x-0 bottom-0 z-30 flex h-14 items-center justify-around border-t border-slate-200/80 bg-white/95 px-3 text-[0.7rem] shadow-soft-xl lg:hidden dark:border-slate-800 dark:bg-slate-900/95">
        <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.dashboard') ? 'text-amber-600' : 'text-slate-500 dark:text-slate-400' }}">
            <span>📊</span>
            <span>Home</span>
        </a>
        <a href="{{ route('admin.inventory.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.inventory.*') ? 'text-amber-600' : 'text-slate-500 dark:text-slate-400' }}">
            <span>📦</span>
            <span>Inventory</span>
        </a>
        <a href="{{ route('admin.stock-in.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.stock-in.*') ? 'text-amber-600' : 'text-slate-500 dark:text-slate-400' }}">
            <span>➕</span>
            <span>In</span>
        </a>
        <a href="{{ route('admin.stock-out.index') }}" class="flex flex-col items-center gap-0.5 {{ request()->routeIs('admin.stock-out.*') ? 'text-amber-600' : 'text-slate-500 dark:text-slate-400' }}">
            <span>➖</span>
            <span>Out</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        if (tokenMeta) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
        }
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        function adminNotifications() {
            return {
                open: false,
                unreadCount: 0,
                notifications: [],
                loading: false,
                load() {
                    const self = this;
                    self.loading = true;
                    axios.get('{{ route('admin.notifications.index') }}')
                        .then(function (r) {
                            self.unreadCount = r.data.unread_count || 0;
                            self.notifications = r.data.notifications || [];
                        })
                        .catch(function () {
                            self.notifications = [];
                            self.unreadCount = 0;
                        })
                        .finally(function () {
                            self.loading = false;
                        });
                },
                markAllRead() {
                    const self = this;
                    axios.post('{{ route('admin.notifications.read-all') }}')
                        .then(function () {
                            self.unreadCount = 0;
                            self.load();
                        })
                        .catch(function () {
                            if (window.Swal) Swal.fire({ icon: 'error', title: 'Error', text: 'Could not mark as read.', confirmButtonColor: '#D4AF37' });
                        });
                },
            };
        }
        function adminShell() {
            return {
                sidebarOpen: false,
                darkMode: false,
                init() {
                    const stored = window.localStorage.getItem('egg-admin-dark');
                    this.darkMode = stored === 'true';
                    document.documentElement.classList.toggle('dark', this.darkMode);
                },
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    window.localStorage.setItem('egg-admin-dark', this.darkMode ? 'true' : 'false');
                    document.documentElement.classList.toggle('dark', this.darkMode);
                    try {
                        window.dispatchEvent(new CustomEvent('admin-dark-mode-change', { detail: { dark: this.darkMode } }));
                    } catch (e) {}
                },
            };
        }

        document.addEventListener('DOMContentLoaded', function () {
            const logoutForm = document.getElementById('logoutForm');
            if (logoutForm) {
                logoutForm.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    try {
                        await axios.post('{{ route('logout') }}');
                        Swal.fire({
                            icon: 'success',
                            title: 'Logged out',
                            text: 'You have been logged out successfully.',
                            timer: 1400,
                            showConfirmButton: false,
                        });
                        setTimeout(function () {
                            window.location.href = '{{ url('/') }}';
                        }, 1400);
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Logout failed',
                            text: 'Please try again.',
                            confirmButtonColor: '#D4AF37',
                        });
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
