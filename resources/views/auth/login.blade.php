<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login · {{ $businessName ?? 'Egg Supply System' }}</title>
    @if(!empty($logoUrl) && in_array('favicon', $logoPositions ?? [], true))
        <link rel="icon" href="{{ $logoUrl }}" type="image/png">
    @endif

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        yolk: '#FFD84D',
                        gold: '#D4AF37',
                        'gold-soft': '#f5e3b5',
                        cream: '#fdfaf3',
                        charcoal: '#020617',
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft-xl': '0 22px 55px rgba(15,23,42,0.18)',
                    },
                },
            },
        };
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-charcoal via-slate-900 to-slate-950 text-slate-100 antialiased font-sans">
    <div class="relative flex min-h-screen items-center justify-center px-4 py-10">
        <!-- Background accent -->
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -left-24 top-10 h-72 w-72 rounded-full bg-amber-400/20 blur-3xl"></div>
            <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-amber-200/10 blur-3xl"></div>
        </div>

        <div class="relative grid w-full max-w-5xl gap-8 rounded-3xl bg-white/5 p-5 shadow-soft-xl ring-1 ring-white/10 backdrop-blur-2xl lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)] lg:p-6">
            <!-- Left: brand + copy -->
            <section class="flex flex-col justify-between border-b border-slate-800/60 pb-6 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-6">
                <div>
                    <div class="flex items-center gap-3">
                        @if(!empty($logoUrl) && in_array('login', $logoPositions ?? [], true))
                            <img src="{{ $logoUrl }}" alt="{{ $businessName ?? 'Logo' }}" class="h-10 w-10 flex-shrink-0 rounded-2xl object-contain bg-white/10 ring-2 ring-amber-200/70">
                        @else
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-tr from-yolk to-amber-400 shadow-md ring-2 ring-amber-200/70">
                                <span class="text-xl">🥚</span>
                            </div>
                        @endif
                        <div>
                            <p class="text-[0.65rem] font-semibold uppercase tracking-[0.25em] text-amber-200/80">Egg System</p>
                            <p class="text-sm font-semibold text-slate-50">{{ $businessName ?? 'Egg Supply System' }}</p>
                        </div>
                    </div>

                    <h1 class="mt-6 text-2xl font-semibold text-slate-50 sm:text-3xl">
                        Inventory &amp; sales control, in one clean dashboard.
                    </h1>
                    <p class="mt-3 text-sm text-slate-300">
                        Sign in to manage stock-in, stock-out, cracked eggs, and real-time inventory for your egg business.
                    </p>

                    <dl class="mt-5 grid gap-3 text-xs text-slate-300 sm:grid-cols-3">
                        <div class="rounded-2xl bg-white/5 px-3 py-2 ring-1 ring-white/10">
                            <dt class="font-semibold text-amber-200">Roles</dt>
                            <dd class="mt-1">Admin &amp; Inventory Manager</dd>
                        </div>
                        <div class="rounded-2xl bg-white/5 px-3 py-2 ring-1 ring-white/10">
                            <dt class="font-semibold text-amber-200">Features</dt>
                            <dd class="mt-1">Inventory, pricing, reports, users</dd>
                        </div>
                        <div class="rounded-2xl bg-white/5 px-3 py-2 ring-1 ring-white/10">
                            <dt class="font-semibold text-amber-200">Security</dt>
                            <dd class="mt-1">Secure, role-based access</dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-6 hidden items-center justify-between text-[0.7rem] text-slate-400 sm:flex">
                    <span>Need help? Contact the system administrator.</span>
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-1 text-amber-300 hover:text-amber-200">
                        <span aria-hidden="true">←</span> Back to site
                    </a>
                </div>
            </section>

            <!-- Right: login card -->
            <section class="flex items-center">
                <div class="w-full rounded-2xl bg-slate-950/60 p-5 shadow-lg ring-1 ring-slate-800/80">
                    <h2 class="text-lg font-semibold text-slate-50">Sign in</h2>
                    <p class="mt-1 text-xs text-slate-400">
                        Use your registered email and password to access the admin panel.
                    </p>

                    <form id="loginForm" class="mt-5 space-y-4">
                        <div>
                            <label for="email" class="text-xs font-medium text-slate-200">Email address</label>
                            <input id="email" name="email" type="email" autocomplete="email"
                                   class="mt-1 w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-50 outline-none placeholder:text-slate-500 focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                   placeholder="you@example.com" required>
                        </div>
                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="text-xs font-medium text-slate-200">Password</label>
                            </div>
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                   class="mt-1 w-full rounded-2xl border border-slate-700 bg-slate-900/70 px-3 py-2.5 text-sm text-slate-50 outline-none placeholder:text-slate-500 focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                                   placeholder="••••••••" required>
                        </div>
                        <div class="flex items-center justify-between text-[0.75rem] text-slate-400">
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" id="remember" name="remember"
                                       class="h-3.5 w-3.5 rounded border-slate-500 bg-slate-900/60 text-amber-400 focus:ring-amber-400">
                                <span>Remember me on this device</span>
                            </label>
                            <a href="{{ url('/') }}" class="text-slate-400 hover:text-amber-300 sm:hidden">
                                ← Back to site
                            </a>
                        </div>

                        <button type="submit"
                                class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-amber-400 to-amber-300 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-soft-xl transition hover:shadow-[0_18px_40px_rgba(250,204,21,0.45)] focus:outline-none focus:ring-2 focus:ring-amber-400 focus:ring-offset-2 focus:ring-offset-slate-900">
                            <span class="inline-flex items-center gap-2">
                                <span>Sign in</span>
                            </span>
                        </button>
                    </form>

                    <p class="mt-4 text-[0.7rem] text-slate-500">
                        By signing in, you agree to keep all inventory and sales data confidential.
                    </p>
                </div>
            </section>
        </div>
    </div>

    <!-- Axios & SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/axios@1.7.7/dist/axios.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tokenMeta = document.querySelector('meta[name=\"csrf-token\"]');
            if (tokenMeta) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = tokenMeta.getAttribute('content');
            }
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

            const form = document.getElementById('loginForm');
            if (!form) return;

            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const email = form.email.value.trim();
                const password = form.password.value.trim();
                const remember = form.remember.checked;

                if (!email || !password) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing details',
                        text: 'Please enter both your email and password.',
                        confirmButtonColor: '#D4AF37',
                    });
                    return;
                }

                const submitButton = form.querySelector('button[type=\"submit\"]');

                try {
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-70', 'cursor-not-allowed');
                    }

                    const response = await axios.post('{{ route('login.submit') }}', {
                        email,
                        password,
                        remember,
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Welcome back',
                        text: 'Redirecting you to the dashboard.',
                        timer: 1400,
                        showConfirmButton: false,
                    });

                    const redirectTo = response.data?.redirect_to || '{{ route('admin.dashboard') }}';
                    setTimeout(() => {
                        window.location.href = redirectTo;
                    }, 1400);
                } catch (error) {
                    const message = error.response?.data?.message || 'Login failed. Please check your credentials.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Unable to login',
                        text: message,
                        confirmButtonColor: '#D4AF37',
                    });
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-70', 'cursor-not-allowed');
                    }
                }
            });
        });
    </script>
</body>
</html>

