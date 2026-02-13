@extends('admin.layouts.app')

@section('title', 'Reports')
@section('header_title', 'Reports')

@section('content')
    <div class="space-y-6">
        {{-- Toolbar: no-print --}}
        <section class="rounded-3xl bg-white/95 p-4 shadow-xl dark:bg-slate-900/80 print:hidden">
            <form method="get" action="{{ route('admin.reports.index') }}" class="flex flex-wrap items-end gap-4">
                <div class="flex flex-wrap items-end gap-3">
                    <div>
                        <label for="from" class="block text-xs font-medium text-slate-700 dark:text-slate-300">From</label>
                        <input id="from" name="from" type="date" value="{{ $from }}"
                               class="mt-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    </div>
                    <div>
                        <label for="to" class="block text-xs font-medium text-slate-700 dark:text-slate-300">To</label>
                        <input id="to" name="to" type="date" value="{{ $to }}"
                               class="mt-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-900 outline-none focus:border-amber-500 focus:ring-1 focus:ring-amber-500 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-100">
                    </div>
                    <button type="submit" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        Apply
                    </button>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="window.print();" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        <span aria-hidden="true">🖨️</span> Print
                    </button>
                    <a href="{{ route('admin.reports.export-pdf', ['from' => $from, 'to' => $to]) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700">
                        <span aria-hidden="true">📄</span> Export PDF
                    </a>
                    <a href="{{ route('admin.reports.export', ['from' => $from, 'to' => $to]) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-amber-400 px-4 py-2 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg">
                        <span aria-hidden="true">📥</span> Export CSV
                    </a>
                </div>
            </form>
        </section>

        {{-- Report content (printable) --}}
        <div id="report-content" class="rounded-3xl bg-white p-6 shadow-xl dark:bg-slate-900/80 dark:text-slate-100 print:shadow-none print:p-0">
            {{-- Report header --}}
            <header class="border-b border-slate-200 pb-4 dark:border-slate-700 print:pb-2">
                <div class="flex items-center gap-4">
                    @if(!empty($logoUrl))
                        <img src="{{ $logoUrl }}" alt="{{ $businessName }}" class="h-20 w-auto flex-shrink-0 object-contain print:h-16" />
                    @endif
                    <div class="min-w-0">
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-slate-50 print:text-xl">{{ $businessName }}</h1>
                        <p class="mt-0.5 text-sm font-semibold text-amber-700 dark:text-amber-400">Business Report</p>
                        <p class="mt-1.5 text-xs text-slate-500 dark:text-slate-400">
                            Period: <strong>{{ \Carbon\Carbon::parse($from)->format('F j, Y') }}</strong> to <strong>{{ \Carbon\Carbon::parse($to)->format('F j, Y') }}</strong>
                            &nbsp;·&nbsp; Generated: {{ now()->format('M j, Y g:i A') }}
                        </p>
                    </div>
                </div>
            </header>

            {{-- Executive summary --}}
            <section class="mt-6">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Executive Summary</h2>
                <div class="mt-3 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"><span aria-hidden="true">📦</span> Stock In</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-50">{{ number_format($stockInCount) }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300">{{ $currency }} {{ number_format($stockInTotal, 2) }} total cost</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"><span aria-hidden="true">💰</span> Sales</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-50">{{ number_format($stockOutCount) }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300">transactions</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-emerald-50/80 p-4 dark:border-slate-700 dark:bg-emerald-900/20">
                        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700 dark:text-emerald-400">Revenue</p>
                        <p class="mt-1 text-2xl font-bold text-emerald-800 dark:text-emerald-300">{{ $currency }} {{ number_format($stockOutRevenue, 2) }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300">Profit {{ $currency }} {{ number_format($stockOutProfit ?? 0, 2) }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400"><span aria-hidden="true">🚚</span> Cracked</p>
                        <p class="mt-1 text-2xl font-bold text-slate-900 dark:text-slate-50">{{ number_format($crackedQty) }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300">pieces · {{ number_format($crackedCount) }} records</p>
                    </div>
                    <div class="rounded-xl border border-rose-200 bg-rose-50/80 p-4 dark:border-slate-700 dark:bg-rose-900/20">
                        <p class="text-xs font-semibold uppercase tracking-wider text-rose-700 dark:text-rose-400">Expenses</p>
                        <p class="mt-1 text-2xl font-bold text-rose-800 dark:text-rose-300">{{ $currency }} {{ number_format($totalExpenses ?? 0, 2) }}</p>
                        <p class="text-xs text-slate-600 dark:text-slate-300">purchases + feed</p>
                    </div>
                </div>
                <div class="mt-4 rounded-xl border-2 border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">Revenue − Expenses</p>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">Revenue {{ $currency }} {{ number_format($stockOutRevenue, 2) }} − Expenses {{ $currency }} {{ number_format($totalExpenses ?? 0, 2) }}</p>
                        </div>
                        <p class="text-2xl font-bold {{ ($revenueMinusExpenses ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">
                            {{ $currency }} {{ number_format($revenueMinusExpenses ?? 0, 2) }}
                        </p>
                    </div>
                </div>
                @if(!empty($keyInsights) && count($keyInsights) > 0)
                    <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50/50 p-4 dark:border-slate-600 dark:bg-slate-800/80">
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-300 mb-2">Key Insights</p>
                        <ul class="list-disc list-inside space-y-1 text-sm text-slate-700 dark:text-slate-200">
                            @foreach($keyInsights as $insight)
                                <li>{{ $insight }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </section>

            {{-- Charts --}}
            <section class="mt-8 print:break-inside-avoid">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">At a Glance</h2>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800/50">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Sales by Size</p>
                        <canvas id="chartSalesBySize" class="max-h-48 w-full" aria-label="Sales by size pie chart"></canvas>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-800/50">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Stock Flow (pieces)</p>
                        <canvas id="chartStockFlow" class="max-h-48 w-full" aria-label="Stock in vs out vs cracked bar chart"></canvas>
                        @php
                            $stockFlowIn = $stockInDetail->sum('quantity_pieces');
                            $stockFlowOut = $stockOutDetail->sum(fn($r) => $r->quantity * $piecesPerTray);
                            $stockFlowCracked = (int) $crackedQty;
                        @endphp
                        <p class="mt-2 text-xs font-medium text-slate-600 dark:text-slate-400 text-center">
                            Stock In: <strong>{{ number_format($stockFlowIn) }}</strong>
                            &nbsp;·&nbsp; Sold: <strong>{{ number_format($stockFlowOut) }}</strong>
                            &nbsp;·&nbsp; Cracked: <strong>{{ number_format($stockFlowCracked) }}</strong>
                        </p>
                    </div>
                </div>
            </section>

            {{-- Profit breakdown & COGS --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Profit Breakdown & Cost Details</h2>
                <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4 items-baseline">
                            <dt class="text-slate-600 dark:text-slate-400">Revenue</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">{{ $currency }} {{ number_format($stockOutRevenue, 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline">
                            <dt class="text-slate-600 dark:text-slate-400">Cost of goods sold (COGS) — egg purchases</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">{{ ($cogs ?? 0) != 0 ? '− ' : '' }}{{ $currency }} {{ number_format($cogs ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline border-t border-slate-200 pt-2 dark:border-slate-700">
                            <dt class="font-medium text-slate-700 dark:text-slate-300">Gross profit (revenue − COGS)</dt>
                            <dd class="font-bold text-right tabular-nums {{ ($grossProfit ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">{{ $currency }} {{ number_format($grossProfit ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline">
                            <dt class="text-slate-600 dark:text-slate-400">Less: Feed & other expenses</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">− {{ $currency }} {{ number_format(($totalFeedStockValue ?? 0) + ($otherExpensesTotal ?? 0), 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline border-t border-slate-200 pt-2 dark:border-slate-700">
                            <dt class="font-medium text-slate-700 dark:text-slate-300">Net profit (revenue − all expenses)</dt>
                            <dd class="font-bold text-right tabular-nums {{ ($netProfit ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">{{ $currency }} {{ number_format($netProfit ?? 0, 2) }}</dd>
                        </div>
                        @if($crackedQty > 0 && ($crackedLossEstimate !== null || ($crackedLossAtSellPrice ?? null) !== null))
                            <div class="flex justify-between gap-4 items-baseline border-t border-slate-200 pt-2 dark:border-slate-700">
                                <dt class="text-slate-600 dark:text-slate-400">Cracked eggs impact ({{ number_format($crackedQty) }} pcs)</dt>
                                <dd class="font-semibold text-rose-700 dark:text-rose-400 text-right tabular-nums">
                                    @if($crackedLossEstimate !== null)
                                        Est. value lost (at cost): {{ $currency }} {{ number_format($crackedLossEstimate, 2) }}
                                    @else
                                        Est. value lost (at sell price): {{ $currency }} {{ number_format($crackedLossAtSellPrice ?? 0, 2) }}
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </section>

            {{-- Trends: previous period comparison --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Trends & Comparison</h2>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-left text-xs">
                        <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                            <tr>
                                <th class="px-3 py-2 font-semibold">Metric</th>
                                <th class="px-3 py-2 text-right font-semibold">This period</th>
                                <th class="px-3 py-2 text-right font-semibold">Previous period</th>
                                <th class="px-3 py-2 text-right font-semibold">Change</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                            <tr class="odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                <td class="px-3 py-2 text-slate-700 dark:text-slate-200">Revenue</td>
                                <td class="px-3 py-2 text-right font-medium">{{ $currency }} {{ number_format($stockOutRevenue, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($prevRevenue ?? 0, 2) }}</td>
                                @php $revChange = $stockOutRevenue - ($prevRevenue ?? 0); @endphp
                                <td class="px-3 py-2 text-right font-medium {{ $revChange >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $revChange >= 0 ? '+' : '' }}{{ $currency }} {{ number_format($revChange, 2) }}</td>
                            </tr>
                            <tr class="odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                <td class="px-3 py-2 text-slate-700 dark:text-slate-200">Sales (transactions)</td>
                                <td class="px-3 py-2 text-right font-medium">{{ number_format($stockOutCount) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($prevSalesCount ?? 0) }}</td>
                                @php $salesChange = $stockOutCount - ($prevSalesCount ?? 0); @endphp
                                <td class="px-3 py-2 text-right font-medium {{ $salesChange >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $salesChange >= 0 ? '+' : '' }}{{ $salesChange }}</td>
                            </tr>
                            <tr class="odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                <td class="px-3 py-2 text-slate-700 dark:text-slate-200">Stock In (cost)</td>
                                <td class="px-3 py-2 text-right font-medium">{{ $currency }} {{ number_format($stockInTotal, 2) }}</td>
                                <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($prevStockInTotal ?? 0, 2) }}</td>
                                @php $costChange = $stockInTotal - ($prevStockInTotal ?? 0); @endphp
                                <td class="px-3 py-2 text-right font-medium">{{ $currency }} {{ number_format($costChange, 2) }}</td>
                            </tr>
                            <tr class="odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                <td class="px-3 py-2 text-slate-700 dark:text-slate-200">Cracked (pieces)</td>
                                <td class="px-3 py-2 text-right font-medium">{{ number_format($crackedQty) }}</td>
                                <td class="px-3 py-2 text-right">{{ number_format($prevCracked ?? 0) }}</td>
                                @php $crackChange = $crackedQty - ($prevCracked ?? 0); @endphp
                                <td class="px-3 py-2 text-right font-medium {{ $crackChange <= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">{{ $crackChange >= 0 ? '+' : '' }}{{ $crackChange }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-2 text-[0.65rem] text-slate-500 dark:text-slate-400">Previous period: {{ \Carbon\Carbon::parse($prevFrom ?? $from)->format('M j, Y') }} – {{ \Carbon\Carbon::parse($prevTo ?? $to)->format('M j, Y') }}</p>
            </section>

            {{-- Top performers --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Top Performers</h2>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Top customers (by revenue)</p>
                        @if($topCustomers->isEmpty())
                            <p class="text-sm text-slate-500 dark:text-slate-400">No sales in this period.</p>
                        @else
                            <ul class="space-y-1 text-sm">
                                @foreach($topCustomers->take(5) as $c)
                                    <li class="flex justify-between gap-2 text-slate-700 dark:text-slate-200">
                                        <span class="truncate">{{ $c->customer_name ?: 'Walk-in' }}</span>
                                        <span class="font-medium shrink-0">{{ $currency }} {{ number_format($c->total_amount ?? 0, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Top sizes sold</p>
                        @if($salesBySize->isEmpty())
                            <p class="text-sm text-slate-500 dark:text-slate-400">No sales in this period.</p>
                        @else
                            <ul class="space-y-1 text-sm">
                                @foreach($salesBySize->sortByDesc('total_revenue')->take(5) as $s)
                                    <li class="flex justify-between gap-2 text-slate-700 dark:text-slate-200">
                                        <span>{{ $s->eggSize->size_name ?? '—' }}</span>
                                        <span class="font-medium">{{ $currency }} {{ number_format($s->total_revenue ?? 0, 2) }} ({{ number_format($s->total_quantity ?? 0) }} trays)</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Risk & alert insights + stock forecast --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Risk & Stock Insights</h2>
                <div class="mt-3 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Alerts</p>
                        @if(empty($inventoryAlerts) || count($inventoryAlerts) === 0)
                            <p class="text-sm text-slate-500 dark:text-slate-400">No alerts. Inventory levels OK.</p>
                        @else
                            <ul class="list-disc list-inside space-y-1 text-sm text-amber-800 dark:text-amber-200">
                                @foreach($inventoryAlerts as $a)
                                    <li><strong>{{ $a['size'] }}</strong>: {{ $a['message'] }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-2">Stock duration (at current sales rate)</p>
                        <p class="text-[0.65rem] text-slate-500 dark:text-slate-400 mb-2">Based on average daily sales + cracks over the period.</p>
                        @if(empty($stockForecasts) || count($stockForecasts) === 0)
                            <p class="text-sm text-slate-500 dark:text-slate-400">No inventory data.</p>
                        @else
                            <ul class="space-y-1 text-sm text-slate-700 dark:text-slate-200">
                                @foreach($stockForecasts as $f)
                                    <li class="flex justify-between gap-2">
                                        <span>{{ $f['size_name'] }}</span>
                                        <span class="tabular-nums">
                                            @if(!empty($f['no_sales_in_period']))
                                                Infinite (no sales)
                                            @elseif($f['days_stock_last'] !== null)
                                                ~{{ $f['days_stock_last'] }} days
                                            @else
                                                —
                                            @endif
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </section>

            {{-- Expenses breakdown --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Expenses</h2>
                <div class="mt-3 rounded-xl border border-slate-200 bg-slate-50/80 p-4 dark:border-slate-700 dark:bg-slate-800/80">
                    <dl class="space-y-2 text-sm">
                        <div class="flex justify-between gap-4 items-baseline">
                            <dt class="text-slate-600 dark:text-slate-400">Egg purchases (Stock In) — period</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">{{ $currency }} {{ number_format($periodExpenses ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline">
                            <dt class="text-slate-600 dark:text-slate-400">Feed (inventory value)</dt>
                            <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">{{ $currency }} {{ number_format($totalFeedStockValue ?? 0, 2) }}</dd>
                        </div>
                        @if(!empty($reportOtherExpenses) && is_array($reportOtherExpenses))
                            @foreach($reportOtherExpenses as $oe)
                                @if(!empty($oe['label']) && isset($oe['amount']))
                                    <div class="flex justify-between gap-4 items-baseline">
                                        <dt class="text-slate-600 dark:text-slate-400">{{ $oe['label'] }}</dt>
                                        <dd class="font-semibold text-slate-900 dark:text-slate-50 text-right tabular-nums">{{ $currency }} {{ number_format((float) $oe['amount'], 2) }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        @if(!empty($reportOtherIncome) && is_array($reportOtherIncome))
                            @foreach($reportOtherIncome as $oi)
                                @if(!empty($oi['label']) && isset($oi['amount']))
                                    <div class="flex justify-between gap-4 items-baseline">
                                        <dt class="text-slate-600 dark:text-slate-400 text-emerald-700 dark:text-emerald-400">Other income: {{ $oi['label'] }}</dt>
                                        <dd class="font-semibold text-emerald-700 dark:text-emerald-400 text-right tabular-nums">+ {{ $currency }} {{ number_format((float) $oi['amount'], 2) }}</dd>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                        <div class="flex justify-between gap-4 items-baseline border-t border-slate-200 pt-2 dark:border-slate-700">
                            <dt class="font-medium text-slate-700 dark:text-slate-300">Total expenses (purchases + feed{{ (($otherExpensesTotal ?? 0) > 0) ? ' + other' : '' }})</dt>
                            <dd class="font-bold text-rose-700 dark:text-rose-400 text-right tabular-nums">{{ $currency }} {{ number_format($totalExpensesInclOther ?? $totalExpenses ?? 0, 2) }}</dd>
                        </div>
                        <div class="flex justify-between gap-4 items-baseline border-t-2 border-slate-200 pt-3 dark:border-slate-700">
                            <dt class="font-medium text-slate-800 dark:text-slate-200">Net (revenue − all expenses + other income)</dt>
                            <dd class="font-bold text-right tabular-nums {{ ($netProfit ?? 0) >= 0 ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">{{ $currency }} {{ number_format($netProfit ?? 0, 2) }}</dd>
                        </div>
                    </dl>
                </div>
            </section>

            {{-- Inventory Snapshot (moved up to reduce white space on later pages) --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Inventory Snapshot (as of {{ now()->format('M j, Y') }})</h2>
                @if($inventorySnapshot->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No inventory records.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Starting (period)</th>
                                    <th class="px-3 py-2 font-semibold text-right">Ending (current)</th>
                                    <th class="px-3 py-2 font-semibold text-right">Trays</th>
                                    <th class="px-3 py-2 font-semibold text-right">Min. Alert</th>
                                    <th class="px-3 py-2 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($inventorySnapshot as $inv)
                                    @php
                                        $isLow = $inv->minimum_stock_alert > 0 && $inv->current_stock_pieces <= $inv->minimum_stock_alert;
                                        $startingPieces = $startingInventoryPieces[$inv->egg_size_id] ?? null;
                                    @endphp
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2 font-medium">{{ $inv->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right tabular-nums">{{ $startingPieces !== null ? number_format($startingPieces) : '—' }}</td>
                                        <td class="px-3 py-2 text-right tabular-nums">{{ number_format($inv->current_stock_pieces) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($inv->current_stock_trays) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($inv->minimum_stock_alert) }}</td>
                                        <td class="px-3 py-2">
                                            @if($isLow)
                                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-[0.65rem] font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">Low stock</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400">OK</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="mt-1.5 text-[0.65rem] text-slate-500 dark:text-slate-400">Starting = ending − stock in + sold + cracked (for this period).</p>
                @endif
            </section>

            {{-- Feed Inventory Snapshot --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Feed Inventory Snapshot (as of {{ now()->format('M j, Y') }})</h2>
                @if($feedsSnapshot->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No feed inventory records.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Feed</th>
                                    <th class="px-3 py-2 font-semibold text-right">Quantity</th>
                                    <th class="px-3 py-2 font-semibold">Unit</th>
                                    <th class="px-3 py-2 font-semibold text-right">Cost/unit</th>
                                    <th class="px-3 py-2 font-semibold text-right">Stock value</th>
                                    <th class="px-3 py-2 font-semibold text-right">Min. Alert</th>
                                    <th class="px-3 py-2 font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($feedsSnapshot as $feed)
                                    @php $isLow = $feed->minimum_stock_alert > 0 && $feed->quantity <= $feed->minimum_stock_alert; @endphp
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2 font-medium">{{ $feed->name }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($feed->quantity, 2) }}</td>
                                        <td class="px-3 py-2">{{ $feed->unit }}</td>
                                        <td class="px-3 py-2 text-right">{{ $feed->cost_per_unit !== null ? $currency . ' ' . number_format($feed->cost_per_unit, 2) : '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ $feed->stock_value !== null ? $currency . ' ' . number_format($feed->stock_value, 2) : '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($feed->minimum_stock_alert, 2) }}</td>
                                        <td class="px-3 py-2">
                                            @if($isLow)
                                                <span class="inline-flex rounded-full bg-amber-100 px-2 py-0.5 text-[0.65rem] font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-200">Low stock</span>
                                            @else
                                                <span class="text-slate-500 dark:text-slate-400">OK</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Stock In Report --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Stock In Report</h2>
                @if($stockInDetail->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No stock-in records in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Date</th>
                                    <th class="px-3 py-2 font-semibold">Supplier</th>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Pieces</th>
                                    <th class="px-3 py-2 font-semibold text-right">Trays</th>
                                    <th class="px-3 py-2 font-semibold text-right">Cost/Piece</th>
                                    <th class="px-3 py-2 font-semibold text-right">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($stockInDetail as $row)
                                    @php $trays = $piecesPerTray > 0 ? (int) floor($row->quantity_pieces / $piecesPerTray) : 0; @endphp
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2">{{ $row->delivery_date?->format('M j, Y') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->supplier_name ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->quantity_pieces) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($trays) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $row->cost_per_piece ? $currency . ' ' . number_format($row->cost_per_piece, 2) : '—' }}</td>
                                        <td class="px-3 py-2 text-right font-medium">{{ $currency }} {{ number_format($row->total_cost ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-slate-200 bg-slate-50 font-semibold dark:border-slate-700 dark:bg-slate-800">
                                @php
                                    $stockInTotalPieces = $stockInDetail->sum('quantity_pieces');
                                    $stockInTotalTrays = $piecesPerTray > 0 ? (int) floor($stockInTotalPieces / $piecesPerTray) : 0;
                                @endphp
                                <tr>
                                    <td colspan="3" class="px-3 py-2">Total</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($stockInTotalPieces) }}</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($stockInTotalTrays) }}</td>
                                    <td class="px-3 py-2 text-right"></td>
                                    <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($stockInDetail->sum('total_cost'), 2) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Sales Report --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Sales Report</h2>
                @if($stockOutDetail->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No sales in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Date</th>
                                    <th class="px-3 py-2 font-semibold">Customer</th>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold">Type</th>
                                    <th class="px-3 py-2 font-semibold text-right">Qty</th>
                                    <th class="px-3 py-2 font-semibold text-right">Amount</th>
                                    <th class="px-3 py-2 font-semibold text-right">Profit</th>
                                    <th class="px-3 py-2 font-semibold">Payment</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($stockOutDetail as $row)
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2">{{ $row->transaction_date?->format('M j, Y H:i') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->customer_name ?: '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($row->order_type ?? '—') }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->quantity) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($row->total_amount ?? 0, 2) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $row->profit !== null ? $currency . ' ' . number_format($row->profit, 2) : '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->payment_method ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-slate-200 bg-slate-50 font-semibold dark:border-slate-700 dark:bg-slate-800">
                                <tr>
                                    <td colspan="5" class="px-3 py-2">Total</td>
                                    <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($stockOutDetail->sum('total_amount'), 2) }}</td>
                                    <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($stockOutDetail->sum('profit') ?? 0, 2) }}</td>
                                    <td class="px-3 py-2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Cracked Eggs Report --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Cracked Eggs Report</h2>
                @if($crackedDetail->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No cracked egg records in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Date</th>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Quantity</th>
                                    <th class="px-3 py-2 font-semibold">Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($crackedDetail as $row)
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2">{{ $row->date_recorded?->format('M j, Y') ?? '—' }}</td>
                                        <td class="px-3 py-2">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->quantity_cracked) }}</td>
                                        <td class="px-3 py-2">{{ $row->reason ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-t-2 border-slate-200 bg-slate-50 font-semibold dark:border-slate-700 dark:bg-slate-800">
                                <tr>
                                    <td colspan="2" class="px-3 py-2">Total</td>
                                    <td class="px-3 py-2 text-right">{{ number_format($crackedDetail->sum('quantity_cracked')) }}</td>
                                    <td class="px-3 py-2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Sales by size (period) --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Sales by Size (Period)</h2>
                @if($salesBySize->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No sales in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Transactions</th>
                                    <th class="px-3 py-2 font-semibold text-right">Quantity</th>
                                    <th class="px-3 py-2 font-semibold text-right">Revenue</th>
                                    <th class="px-3 py-2 font-semibold text-right">Profit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($salesBySize as $row)
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2 font-medium">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->transaction_count) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->total_quantity) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($row->total_revenue ?? 0, 2) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($row->total_profit ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Stock In by size (period) --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Stock In by Size (Period)</h2>
                @if($stockInBySize->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No stock-in in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Deliveries</th>
                                    <th class="px-3 py-2 font-semibold text-right">Pieces</th>
                                    <th class="px-3 py-2 font-semibold text-right">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($stockInBySize as $row)
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2 font-medium">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->delivery_count) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->total_pieces) }}</td>
                                        <td class="px-3 py-2 text-right">{{ $currency }} {{ number_format($row->total_cost ?? 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Cracked by size (period) --}}
            <section class="mt-8">
                <h2 class="text-base font-bold uppercase tracking-wider text-slate-600 dark:text-slate-400">Cracked by Size (Period)</h2>
                @if($crackedBySize->isEmpty())
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">No cracked records in this period.</p>
                @else
                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full text-left text-xs">
                            <thead class="border-b border-slate-200 bg-slate-50 text-slate-600 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-3 py-2 font-semibold">Size</th>
                                    <th class="px-3 py-2 font-semibold text-right">Pieces Cracked</th>
                                    <th class="px-3 py-2 font-semibold text-right">Records</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                @foreach($crackedBySize as $row)
                                    <tr class="text-slate-700 dark:text-slate-200 odd:bg-white even:bg-sky-50/60 dark:odd:bg-slate-900/30 dark:even:bg-sky-900/20">
                                        <td class="px-3 py-2 font-medium">{{ $row->eggSize->size_name ?? '—' }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->total_cracked) }}</td>
                                        <td class="px-3 py-2 text-right">{{ number_format($row->record_count) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            <footer class="mt-10 border-t border-slate-200 pt-4 text-center text-xs text-slate-500 dark:border-slate-700 dark:text-slate-400 print:mt-8 print:hidden">
                <p>{{ $businessName }} · Report generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
                <p class="mt-1">Data as of {{ now()->format('M j, Y') }} — subject to audit.</p>
            </footer>
            {{-- Print-only fixed footer (repeats on every page) --}}
            <div class="hidden print:block fixed bottom-0 left-0 right-0 py-2 px-4 text-center text-[10px] text-slate-500 border-t border-slate-200 bg-white" id="print-footer">
                <span class="report-title-footer">{{ $businessName }} Report</span> &nbsp;|&nbsp; Page <span class="page-number"></span>
                &nbsp;·&nbsp; Data as of {{ now()->format('M j, Y') }} — subject to audit.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart !== 'undefined' && typeof ChartDataLabels !== 'undefined') {
                Chart.register(ChartDataLabels);
            }
            var salesData = @json($salesBySize->map(fn($s) => ['label' => $s->eggSize->size_name ?? '—', 'revenue' => (float)($s->total_revenue ?? 0)])->values());
            var stockInPieces = {{ $stockInDetail->sum('quantity_pieces') }};
            var stockOutPieces = {{ $stockOutDetail->sum(fn($r) => $r->quantity * $piecesPerTray) }};
            var crackedPieces = {{ (int) $crackedQty }};

            // Color-blind friendly palette (distinct hues)
            var pieColors = ['#0077BB', '#33BBEE', '#009988', '#EE7733', '#CC3311', '#EE3377', '#BBBBBB'];

            if (document.getElementById('chartSalesBySize') && salesData.length > 0) {
                var sliceColors = pieColors.slice(0, Math.max(salesData.length, 1));
                new Chart(document.getElementById('chartSalesBySize'), {
                    type: 'pie',
                    data: {
                        labels: salesData.map(function(d) { return d.label; }),
                        datasets: [{
                            data: salesData.map(function(d) { return d.revenue; }),
                            backgroundColor: sliceColors,
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: { enabled: true }
                        }
                    }
                });
            }
            if (document.getElementById('chartStockFlow')) {
                new Chart(document.getElementById('chartStockFlow'), {
                    type: 'bar',
                    data: {
                        labels: ['Stock In', 'Sold', 'Cracked'],
                        datasets: [{
                            label: 'Pieces',
                            data: [stockInPieces, stockOutPieces, crackedPieces],
                            backgroundColor: ['#10B981', '#D4AF37', '#EF4444']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: { y: { beginAtZero: true } },
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true },
                            datalabels: {
                                anchor: 'end',
                                align: 'top',
                                formatter: function(value) { return value.toLocaleString(); }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 12mm 12mm 16mm 12mm;
            }
            body * { visibility: hidden; }
            #report-content, #report-content * { visibility: visible; }
            #print-footer { visibility: visible !important; }
            /* Force background and color to print (background graphics) */
            #report-content, #report-content * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            #report-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                background: #fff !important;
                color: #111;
            }
            #report-content thead { background: #f1f5f9 !important; }
            #report-content tfoot { background: #f1f5f9 !important; }
            #report-content tr.odd\\:bg-white { background: #fff !important; }
            #report-content tr.even\\:bg-sky-50\\/60,
            #report-content tr.even\\:bg-slate-50\\/70 { background: #e0f2fe !important; }
            .print\\:hidden { display: none !important; }
            .page-number::before { content: counter(page) ' of ' counter(pages); }
            .dark\\:bg-slate-900\\/80 { background: #fff !important; }
            .dark\\:text-slate-100 { color: #111 !important; }
            .dark\\:border-slate-700 { border-color: #e2e8f0 !important; }
            .dark\\:bg-slate-800 { background: #f1f5f9 !important; }
            .dark\\:text-slate-400 { color: #475569 !important; }
            .dark\\:text-slate-200 { color: #1e293b !important; }
            .dark\\:text-slate-50 { color: #111 !important; }
            .dark\\:text-emerald-400 { color: #059669 !important; }
            .dark\\:text-emerald-300 { color: #047857 !important; }
            .dark\\:bg-emerald-900\\/20 { background: #d1fae5 !important; }
            .dark\\:bg-rose-900\\/20 { background: #fce7f3 !important; }
            .dark\\:text-rose-400 { color: #be185d !important; }
            .dark\\:text-rose-300 { color: #c026d3 !important; }
        }
    </style>
@endsection
