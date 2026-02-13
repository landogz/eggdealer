@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Hero / Intro -->
        <section
            class="relative overflow-hidden rounded-3xl border border-white/20 bg-gradient-to-r from-yolk/50 via-soft-cream to-orange-200/60 p-6 shadow-soft-xl backdrop-blur-xl dark:border-slate-700 dark:bg-gradient-to-r dark:from-charcoal dark:via-slate-900 dark:to-slate-800">
            <div class="absolute -left-10 bottom-0 h-40 w-40 rounded-full bg-white/40 blur-3xl opacity-70 dark:bg-amber-500/20"></div>
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-orange-400/40 blur-3xl opacity-70"></div>
            <div class="relative flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-700/80 dark:text-amber-200">Egg
                        Dealer HQ</p>
                    <h2 class="mt-1 text-2xl font-extrabold text-slate-900 sm:text-3xl dark:text-soft-cream flex items-center gap-2">
                        @if(!empty($logoUrl))
                            <img src="{{ $logoUrl }}" alt="" class="h-8 w-8 sm:h-9 sm:w-9 object-contain rounded-lg">
                        @else
                            <span aria-hidden="true">🥚</span>
                        @endif
                        {{ $businessName ?? 'Egg Supply' }} Dashboard
                    </h2>
                    <p class="mt-2 max-w-xl text-sm text-slate-700/80 dark:text-slate-200">
                        Monitor <span class="font-semibold">inventory</span>, <span class="font-semibold">sales</span>, and
                        <span class="font-semibold">cracked eggs</span> in one modern Gen‑Z style dashboard.
                    </p>
                </div>
                <div
                    class="mt-3 flex items-center gap-3 rounded-2xl bg-white/70 px-4 py-3 text-xs shadow-lg backdrop-blur-xl sm:mt-0 dark:bg-slate-900/60">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-tr from-amber-300 to-orange-400 text-lg shadow">
                        📊
                    </div>
                    <div>
                        <p class="text-[0.7rem] font-semibold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">
                            Today snapshot
                        </p>
                        <p class="text-xs text-slate-700 dark:text-slate-100">
                            ₱{{ number_format($salesTodayAmount, 2) }} sales · {{ number_format($stockInTodayCount) }} stock in ·
                            {{ number_format($stockOutTodayCount) }} stock out
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Top stat cards (2 rows on desktop) -->
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <x-admin.card-stat
                title="Total Stock"
                :value="$totalStockPieces"
                subtitle="pieces"
                icon="📦"
                href="{{ route('admin.inventory.index') }}"
            />
            <x-admin.card-stat
                title="Today’s Sales 💰"
                :value="(int) round($salesTodayAmount)"
                subtitle="PHP"
                icon="💵"
                href="{{ route('admin.stock-out.index') }}"
            />
            <x-admin.card-stat
                title="Stock In Today"
                :value="$stockInTodayCount"
                subtitle="deliveries"
                icon="📥"
                href="{{ route('admin.stock-in.index') }}"
            />
            <x-admin.card-stat
                title="Stock Out Today"
                :value="$stockOutTodayCount"
                subtitle="orders"
                icon="🚛"
                href="{{ route('admin.stock-out.index') }}"
            />
            <x-admin.card-stat
                title="Cracked Eggs"
                :value="$crackedTodayCount"
                subtitle="today"
                icon="❌"
                href="{{ route('admin.cracked-eggs.index') }}"
            />
            <x-admin.card-stat
                title="Monthly Revenue"
                :value="(int) round($netRevenueThisMonth)"
                subtitle="Revenue − expenses ({{ $currency }})"
                icon="📈"
                href="{{ route('admin.stock-out.index') }}"
            />
            <x-admin.card-stat
                title="Monthly expenses"
                :value="(int) round($stockInTotalCostThisMonth)"
                subtitle="Purchases (stock in)"
                icon="📤"
                href="{{ route('admin.stock-in.index') }}"
            />
            <x-admin.card-stat
                title="Feed inventory"
                :value="$feeds->count()"
                :subtitle="$lowFeedStockCount > 0 ? $lowFeedStockCount . ' low stock' : 'types'"
                icon="🌾"
                href="{{ route('admin.feeds.index') }}"
            />
        </section>

        <!-- Expenses summary -->
        <section class="rounded-3xl border border-white/20 bg-rose-50/50 p-4 shadow-soft-xl dark:border-slate-700 dark:bg-rose-950/20">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-slate-50">
                    <span aria-hidden="true">💸</span>
                    Expenses
                </h2>
                <a href="{{ route('admin.reports.index') }}" class="text-[0.7rem] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">View reports →</a>
            </div>
            <div class="mt-3 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Purchases (stock in) this month</p>
                    <p class="mt-1 text-lg font-bold text-slate-900 dark:text-slate-50">{{ $currency }} {{ number_format($stockInTotalCostThisMonth, 2) }}</p>
                    <a href="{{ route('admin.stock-in.index') }}" class="mt-2 inline-block text-[0.65rem] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">Stock in →</a>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-3 dark:border-slate-700 dark:bg-slate-800/60">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Expenses from Feeds</p>
                    <p class="mt-1 text-lg font-bold text-slate-900 dark:text-slate-50">{{ $currency }} {{ number_format($totalFeedStockValue, 2) }}</p>
                    <p class="mt-1 text-[0.65rem] text-slate-500 dark:text-slate-400">Feed inventory value (cost × quantity)</p>
                    <a href="{{ route('admin.feeds.index') }}" class="mt-2 inline-block text-[0.65rem] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">Manage Feeds →</a>
                </div>
                <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-3 dark:border-slate-700 dark:bg-slate-800/60 sm:col-span-2 lg:col-span-1">
                    <p class="text-[0.65rem] font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total expenses (this month + feed value)</p>
                    <p class="mt-1 text-lg font-bold text-slate-900 dark:text-slate-50">{{ $currency }} {{ number_format($stockInTotalCostThisMonth + $totalFeedStockValue, 2) }}</p>
                </div>
            </div>
        </section>

        <!-- Chart row 1 -->
        <section class="grid gap-4 lg:grid-cols-2">
            <!-- Sales Overview with period filter -->
            <div
                class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
                <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full bg-gradient-to-br from-amber-200/60 to-orange-400/40 blur-xl opacity-70 group-hover:scale-125 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 flex items-center gap-1.5">
                            <span class="text-base">📈</span>
                            <span>Sales Overview</span>
                        </p>
                        <p class="mt-0.5 text-[0.7rem] text-slate-500 dark:text-slate-400">Monthly sales trend</p>
                    </div>
                    <label class="flex items-center gap-1.5 text-[0.7rem] text-slate-600 dark:text-slate-400">
                        <span>Period</span>
                        <select id="salesOverviewPeriod"
                                class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                            <option value="today">Today</option>
                            <option value="yesterday">Yesterday</option>
                            <option value="this_month">This month</option>
                            <option value="last_month">Last month</option>
                        </select>
                    </label>
                </div>
                <div class="relative mt-3">
                    <canvas id="salesOverviewChart" class="h-52 w-full"></canvas>
                </div>
            </div>
            <!-- Inventory by Size: category (Pieces/Trays) + size filter -->
            <div
                class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
                <div class="absolute -left-10 -top-10 h-24 w-24 rounded-full bg-gradient-to-br from-amber-200/60 to-orange-400/40 blur-xl opacity-70 group-hover:scale-125 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 flex items-center gap-1.5">
                            <span class="text-base">📦</span>
                            <span>Inventory by Size</span>
                        </p>
                        <p class="mt-0.5 text-[0.7rem] text-slate-500 dark:text-slate-400">Live stock by egg size</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <label class="flex items-center gap-1.5 text-[0.7rem] text-slate-600 dark:text-slate-400">
                            <span>Category</span>
                            <select id="inventoryChartCategory"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                <option value="pieces">Pieces</option>
                                <option value="trays">Trays</option>
                                <option value="both">Pieces & Trays</option>
                            </select>
                        </label>
                        <label class="flex items-center gap-1.5 text-[0.7rem] text-slate-600 dark:text-slate-400">
                            <span>Size</span>
                            <select id="inventoryChartSizeFilter"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                <option value="">All sizes</option>
                                @foreach($inventories as $inv)
                                    <option value="{{ $inv->eggSize->size_name ?? '—' }}">{{ $inv->eggSize->size_name ?? '—' }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
                <div class="relative mt-3">
                    <canvas id="inventoryBySizeChart" class="h-52 w-full"></canvas>
                </div>
            </div>
        </section>

        <!-- Chart row 2 + inventory table -->
        <section class="grid gap-4 lg:grid-cols-[minmax(0,1.2fr)_minmax(0,1fr)]">
            <div class="space-y-4">
                <x-admin.chart-panel
                    title="Damage Percentage"
                    subtitle="% of cracked eggs per size"
                    emoji="❌"
                    chart-id="damagePercentageChart"
                />
                <div class="group relative overflow-hidden rounded-3xl border border-white/20 bg-white/40 p-4 shadow-xl backdrop-blur-xl transition-all duration-300 ease-out hover:-translate-y-1 hover:shadow-2xl hover:border-amber-200/80 dark:bg-slate-900/40 dark:border-slate-700/60">
                    <div class="relative flex flex-wrap items-end justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-50 flex items-center gap-1.5">
                                <span class="text-base">📊</span>
                                <span>Revenue vs Expenses</span>
                            </p>
                            <p class="mt-0.5 text-[0.7rem] text-slate-500 dark:text-slate-400">Expenses = Purchases (Stock In) + Feed inventory value</p>
                        </div>
                        <label class="flex items-center gap-1.5 text-[0.7rem] text-slate-600 dark:text-slate-400">
                            <span>Period</span>
                            <select id="revenueVsExpensesPeriod"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_month">This month</option>
                                <option value="last_month">Last month</option>
                                <option value="last_12_months">Last 12 months</option>
                            </select>
                        </label>
                    </div>
                    <div class="relative mt-3">
                        <canvas id="revenueVsExpensesChart" class="h-52 w-full"></canvas>
                    </div>
                </div>
            </div>
            <div
                class="rounded-3xl border border-white/20 bg-white/80 p-5 shadow-soft-xl backdrop-blur-xl dark:border-slate-700 dark:bg-slate-900/80">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-slate-50">
                        <span>Inventory by size</span>
                    @if($lowStockCount > 0)
                        <span
                            class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-[0.65rem] font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-200 animate-pulse">
                            ⚠️ {{ $lowStockCount }} low stock
                        </span>
                    @endif
                    </h3>
                    @if(!$inventories->isEmpty())
                        <label class="flex items-center gap-1.5 text-[0.7rem] text-slate-600 dark:text-slate-400">
                            <span>Filter size</span>
                            <select id="inventoryTableSizeFilter"
                                    class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs text-slate-800 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200">
                                <option value="">All sizes</option>
                                @foreach($inventories as $inv)
                                    <option value="{{ $inv->eggSize->size_name ?? '—' }}">{{ $inv->eggSize->size_name ?? '—' }}</option>
                                @endforeach
                            </select>
                        </label>
                    @endif
                </div>
                @if($inventories->isEmpty())
                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">
                        No inventory records yet. Add egg sizes and record stock in to see data here.
                    </p>
                @else
                    <div class="mt-3 overflow-hidden rounded-2xl border border-slate-100/80 dark:border-slate-800/80">
                        <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                            <thead
                                class="bg-slate-50 text-[0.65rem] uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2">Size</th>
                                    <th class="px-3 py-2 text-right">Pieces</th>
                                    <th class="px-3 py-2 text-right">Trays</th>
                                    <th class="px-3 py-2 text-right">Min alert</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inventories as $inv)
                                    @php
                                        $isLow = $inv->minimum_stock_alert > 0 && $inv->current_stock_pieces <= $inv->minimum_stock_alert;
                                    @endphp
                                    <tr
                                        class="inventory-table-row border-t border-slate-100/80 text-xs transition-colors duration-200 dark:border-slate-800/80 {{ $isLow ? 'bg-amber-50/80 dark:bg-amber-900/20' : 'hover:bg-slate-50/60 dark:hover:bg-slate-800/60' }}"
                                        data-size="{{ $inv->eggSize->size_name ?? '—' }}">
                                        <td class="px-3 py-2 font-medium text-slate-900 dark:text-slate-50">
                                            {{ $inv->eggSize->size_name ?? '—' }}
                                        </td>
                                        <td class="px-3 py-2 text-right">{{ number_format($inv->current_stock_pieces) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($inv->current_stock_trays) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($inv->minimum_stock_alert) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                {{-- Feed inventory --}}
                <div class="mt-4 rounded-2xl border border-slate-100 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/50">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h3 class="flex items-center gap-2 text-sm font-semibold text-slate-900 dark:text-slate-50">
                            <span>🌾</span>
                            <span>Feed inventory</span>
                            @if($lowFeedStockCount > 0)
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-0.5 text-[0.65rem] font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-200 animate-pulse">
                                    ⚠️ {{ $lowFeedStockCount }} low
                                </span>
                            @endif
                        </h3>
                        <a href="{{ route('admin.feeds.index') }}" class="text-[0.7rem] font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400 dark:hover:text-amber-300">View all →</a>
                    </div>
                    @if($feeds->isEmpty())
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">No feed types yet. Add feeds in the Feeds section.</p>
                    @else
                        <div class="mt-3 overflow-hidden rounded-2xl border border-slate-100/80 dark:border-slate-800/80">
                            <table class="min-w-full text-left text-xs text-slate-700 dark:text-slate-200">
                                <thead class="bg-slate-50 text-[0.65rem] uppercase tracking-wider text-slate-500 dark:bg-slate-800 dark:text-slate-400">
                                    <tr>
                                        <th class="px-3 py-2">Feed</th>
                                        <th class="px-3 py-2 text-right">Qty</th>
                                        <th class="px-3 py-2">Unit</th>
                                        <th class="px-3 py-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($feeds->take(5) as $feed)
                                        @php $isLow = $feed->isLowStock(); @endphp
                                        <tr class="border-t border-slate-100/80 dark:border-slate-800/80 {{ $isLow ? 'bg-amber-50/80 dark:bg-amber-900/20' : 'hover:bg-slate-50/60 dark:hover:bg-slate-800/60' }}">
                                            <td class="px-3 py-2 font-medium text-slate-900 dark:text-slate-50">{{ $feed->name }}</td>
                                            <td class="px-3 py-2 text-right">{{ number_format($feed->quantity, 2) }}</td>
                                            <td class="px-3 py-2">{{ $feed->unit }}</td>
                                            <td class="px-3 py-2">
                                                @if($isLow)
                                                    <span class="text-[0.65rem] font-semibold text-amber-700 dark:text-amber-200">Low</span>
                                                @else
                                                    <span class="text-slate-500 dark:text-slate-400">OK</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($feeds->count() > 5)
                            <p class="mt-2 text-[0.65rem] text-slate-500 dark:text-slate-400">Showing 5 of {{ $feeds->count() }}. <a href="{{ route('admin.feeds.index') }}" class="text-amber-600 dark:text-amber-400">View all</a></p>
                        @endif
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function getChartTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            textColor: isDark ? '#e2e8f0' : '#0f172a',
            gridColor: isDark ? 'rgba(148, 163, 184, 0.3)' : 'rgba(148, 163, 184, 0.3)',
        };
    }
    function updateChartsTheme() {
        const theme = getChartTheme();
        const textColor = theme.textColor;
        const gridColor = theme.gridColor;
        (window.adminCharts || []).forEach(function (chart) {
            if (!chart || !chart.options) return;
            if (chart.options.plugins && chart.options.plugins.legend && chart.options.plugins.legend.labels) {
                chart.options.plugins.legend.labels.color = textColor;
                if (!chart.options.plugins.legend.labels.font) chart.options.plugins.legend.labels.font = {};
                chart.options.plugins.legend.labels.font.color = textColor;
            }
            if (chart.options.scales) {
                ['x', 'y'].forEach(function (axis) {
                    if (chart.options.scales[axis]) {
                        if (chart.options.scales[axis].ticks) {
                            chart.options.scales[axis].ticks.color = textColor;
                            if (!chart.options.scales[axis].ticks.font) chart.options.scales[axis].ticks.font = {};
                            chart.options.scales[axis].ticks.font.color = textColor;
                        }
                        if (chart.options.scales[axis].grid) chart.options.scales[axis].grid.color = gridColor;
                    }
                });
            }
            chart.update();
        });
    }
    window.adminCharts = [];
    function initCharts() {
        if (!window.Chart) {
            setTimeout(initCharts, 80);
            return;
        }
        const theme = getChartTheme();
        const textColor = theme.textColor;
        const gridColor = theme.gridColor;

        const months = @json($chartMonths);
        const salesSeries = @json($chartSalesSeries);
        const expenseSeries = @json($chartExpenseSeries);
        const salesTodayAmount = {{ (float) $salesTodayAmount }};
        const salesYesterdayAmount = {{ (float) $salesYesterdayAmount }};
        const revExpTodayExpense = {{ (float) ($revExpTodayExpense ?? 0) }};
        const revExpYesterdayExpense = {{ (float) ($revExpYesterdayExpense ?? 0) }};
        const chartSalesThisMonthDays = @json($chartSalesThisMonthDays ?? []);
        const chartSalesThisMonthValues = @json($chartSalesThisMonthValues ?? []);
        const chartSalesLastMonthDays = @json($chartSalesLastMonthDays ?? []);
        const chartSalesLastMonthValues = @json($chartSalesLastMonthValues ?? []);
        const chartRevExpThisMonthExpense = @json($chartRevExpThisMonthExpense ?? []);
        const chartRevExpLastMonthExpense = @json($chartRevExpLastMonthExpense ?? []);
        const inventoryBySize = @json($chartInventoryBySize);
        const inventoryBySizeTrays = @json($chartInventoryBySizeTrays ?? []);
        const crackedBySize = @json($chartCrackedBySize);

        const salesCtx = document.getElementById('salesOverviewChart')?.getContext('2d');
        const salesPeriodSelect = document.getElementById('salesOverviewPeriod');
        let salesOverviewChart = null;
        if (salesCtx) {
            function getSalesOverviewData() {
                const period = (salesPeriodSelect && salesPeriodSelect.value) || 'today';
                switch (period) {
                    case 'yesterday':
                        return { labels: ['Yesterday'], data: [salesYesterdayAmount] };
                    case 'this_month':
                        return { labels: chartSalesThisMonthDays, data: chartSalesThisMonthValues };
                    case 'last_month':
                        return { labels: chartSalesLastMonthDays, data: chartSalesLastMonthValues };
                    default:
                        return { labels: ['Today'], data: [salesTodayAmount] };
                }
            }
            const initial = getSalesOverviewData();
            salesOverviewChart = new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: initial.labels,
                    datasets: [{
                        label: 'Sales',
                        data: initial.data,
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(248, 113, 113, 0.15)',
                        tension: 0.4,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: textColor, font: { color: textColor } } },
                        tooltip: { mode: 'index', intersect: false },
                    },
                    interaction: { intersect: false, mode: 'index' },
                    scales: {
                        x: { ticks: { color: textColor, font: { color: textColor } }, grid: { color: gridColor } },
                        y: { ticks: { color: textColor, font: { color: textColor } }, grid: { color: gridColor } },
                    },
                },
            });
            window.adminCharts.push(salesOverviewChart);
            if (salesPeriodSelect) {
                salesPeriodSelect.addEventListener('change', function () {
                    const { labels, data } = getSalesOverviewData();
                    salesOverviewChart.data.labels = labels;
                    salesOverviewChart.data.datasets[0].data = data;
                    salesOverviewChart.update();
                });
            }
        }

        const invCtx = document.getElementById('inventoryBySizeChart')?.getContext('2d');
        const catSelect = document.getElementById('inventoryChartCategory');
        const sizeSelect = document.getElementById('inventoryChartSizeFilter');
        let inventoryChart = null;
        if (invCtx && (inventoryBySize.length || inventoryBySizeTrays.length)) {
            function buildInventoryChartData() {
                const category = (catSelect && catSelect.value) || 'pieces';
                const sizeFilter = (sizeSelect && sizeSelect.value) || '';
                let labels = inventoryBySize.map(i => i.label);
                let piecesData = inventoryBySize.map(i => i.value);
                let traysData = inventoryBySizeTrays.map(i => i.value);
                if (sizeFilter) {
                    const idx = labels.indexOf(sizeFilter);
                    if (idx !== -1) {
                        labels = [labels[idx]];
                        piecesData = [piecesData[idx]];
                        traysData = [traysData[idx]];
                    }
                }
                const datasets = [];
                if (category === 'pieces' || category === 'both') {
                    datasets.push({
                        label: 'Pieces',
                        data: piecesData,
                        backgroundColor: 'rgba(250, 204, 21, 0.8)',
                        borderRadius: 8,
                    });
                }
                if (category === 'trays' || category === 'both') {
                    datasets.push({
                        label: 'Trays',
                        data: traysData,
                        backgroundColor: 'rgba(249, 115, 22, 0.8)',
                        borderRadius: 8,
                    });
                }
                return { labels, datasets };
            }
            const initial = buildInventoryChartData();
            inventoryChart = new Chart(invCtx, {
                type: 'bar',
                data: { labels: initial.labels, datasets: initial.datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { color: textColor, font: { color: textColor }, boxWidth: 12 } },
                        tooltip: { mode: 'index', intersect: false },
                    },
                    scales: {
                        x: { ticks: { color: textColor, font: { color: textColor } }, grid: { display: false } },
                        y: { ticks: { color: textColor, font: { color: textColor } }, grid: { color: gridColor } },
                    },
                },
            });
            window.adminCharts.push(inventoryChart);
            if (catSelect) catSelect.addEventListener('change', function () {
                const { labels, datasets } = buildInventoryChartData();
                inventoryChart.data.labels = labels;
                inventoryChart.data.datasets = datasets;
                inventoryChart.update();
            });
            if (sizeSelect) sizeSelect.addEventListener('change', function () {
                const { labels, datasets } = buildInventoryChartData();
                inventoryChart.data.labels = labels;
                inventoryChart.data.datasets = datasets;
                inventoryChart.update();
            });
        }

        const inventoryTableFilter = document.getElementById('inventoryTableSizeFilter');
        const inventoryRows = document.querySelectorAll('.inventory-table-row');
        if (inventoryTableFilter && inventoryRows.length) {
            inventoryTableFilter.addEventListener('change', function () {
                const size = (this.value || '').trim();
                inventoryRows.forEach(function (row) {
                    const rowSize = (row.getAttribute('data-size') || '').trim();
                    row.style.display = (!size || rowSize === size) ? '' : 'none';
                });
            });
        }

        const dmgCtx = document.getElementById('damagePercentageChart')?.getContext('2d');
        let damageChart = null;
        if (dmgCtx && crackedBySize.length) {
            damageChart = new Chart(dmgCtx, {
                type: 'doughnut',
                data: {
                    labels: crackedBySize.map(i => i.label),
                    datasets: [{
                        data: crackedBySize.map(i => i.value),
                        backgroundColor: ['#f97316', '#fb923c', '#fdba74', '#facc15', '#a855f7', '#0ea5e9'],
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { color: textColor, font: { color: textColor }, boxWidth: 10 } },
                        tooltip: { callbacks: { label: ctx => `${ctx.label}: ${ctx.raw}%` } },
                    },
                    cutout: '65%',
                },
            });
            window.adminCharts.push(damageChart);
        }

        const revCtx = document.getElementById('revenueVsExpensesChart')?.getContext('2d');
        const revenueVsExpensesPeriodSelect = document.getElementById('revenueVsExpensesPeriod');
        let revenueChart = null;
        if (revCtx) {
            function getRevenueVsExpensesData() {
                const period = (revenueVsExpensesPeriodSelect && revenueVsExpensesPeriodSelect.value) || 'today';
                switch (period) {
                    case 'yesterday':
                        return {
                            labels: ['Yesterday'],
                            revenue: [salesYesterdayAmount],
                            expense: [revExpYesterdayExpense],
                        };
                    case 'this_month':
                        return {
                            labels: chartSalesThisMonthDays,
                            revenue: chartSalesThisMonthValues,
                            expense: chartRevExpThisMonthExpense,
                        };
                    case 'last_month':
                        return {
                            labels: chartSalesLastMonthDays,
                            revenue: chartSalesLastMonthValues,
                            expense: chartRevExpLastMonthExpense,
                        };
                    case 'last_12_months':
                        return {
                            labels: months,
                            revenue: salesSeries,
                            expense: expenseSeries,
                        };
                    default:
                        return {
                            labels: ['Today'],
                            revenue: [salesTodayAmount],
                            expense: [revExpTodayExpense],
                        };
                }
            }
            const initialRevExp = getRevenueVsExpensesData();
            revenueChart = new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: initialRevExp.labels,
                    datasets: [
                        {
                            label: 'Revenue',
                            data: initialRevExp.revenue,
                            borderColor: '#22c55e',
                            backgroundColor: 'rgba(34, 197, 94, 0.15)',
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Expenses',
                            data: initialRevExp.expense,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.10)',
                            tension: 0.4,
                            fill: true,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { labels: { color: textColor, font: { color: textColor } } },
                        tooltip: { mode: 'index', intersect: false },
                    },
                    interaction: { intersect: false, mode: 'index' },
                    scales: {
                        x: { ticks: { color: textColor, font: { color: textColor } }, grid: { color: gridColor } },
                        y: { ticks: { color: textColor, font: { color: textColor } }, grid: { color: gridColor } },
                    },
                },
            });
            window.adminCharts.push(revenueChart);
            if (revenueVsExpensesPeriodSelect) {
                revenueVsExpensesPeriodSelect.addEventListener('change', function () {
                    const { labels, revenue, expense } = getRevenueVsExpensesData();
                    revenueChart.data.labels = labels;
                    revenueChart.data.datasets[0].data = revenue;
                    revenueChart.data.datasets[1].data = expense;
                    revenueChart.update();
                });
            }
        }
    }

    window.addEventListener('admin-dark-mode-change', updateChartsTheme);

    // Lazy-load charts when main content is visible
    const main = document.querySelector('main');
    if ('IntersectionObserver' in window && main) {
        const observer = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    initCharts();
                    obs.disconnect();
                }
            });
        }, { threshold: 0.2 });
        observer.observe(main);
    } else {
        initCharts();
    }
});
</script>
@endpush
