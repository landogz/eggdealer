<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $businessName }} Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; margin: 16px; }
        h1 { font-size: 18px; margin: 0 0 4px 0; }
        .meta { font-size: 10px; color: #64748b; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #e2e8f0; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; font-weight: 600; }
        .text-right { text-align: right; }
        .section { margin-bottom: 16px; }
        .section h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: #475569; margin: 0 0 8px 0; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        .summary-grid { display: table; width: 100%; margin-bottom: 12px; }
        .summary-cell { display: table-cell; padding: 8px; border: 1px solid #e2e8f0; background: #f8fafc; width: 20%; }
        .summary-cell strong { display: block; font-size: 14px; }
        .footer { margin-top: 24px; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <h1>{{ $businessName }}</h1>
    <p class="meta">Business Report · Period: {{ \Carbon\Carbon::parse($from)->format('F j, Y') }} to {{ \Carbon\Carbon::parse($to)->format('F j, Y') }} · Generated: {{ now()->format('M j, Y g:i A') }}</p>

    <div class="section">
        <h2>Executive Summary</h2>
        <div class="summary-grid">
            <div class="summary-cell">Stock In<br><strong>{{ number_format($stockInCount) }}</strong><br>{{ $currency }} {{ number_format($stockInTotal, 2) }}</div>
            <div class="summary-cell">Sales<br><strong>{{ number_format($stockOutCount) }}</strong> transactions</div>
            <div class="summary-cell">Revenue<br><strong>{{ $currency }} {{ number_format($stockOutRevenue, 2) }}</strong></div>
            <div class="summary-cell">Cracked<br><strong>{{ number_format($crackedQty) }}</strong> pieces</div>
            <div class="summary-cell">Expenses<br><strong>{{ $currency }} {{ number_format($totalExpenses ?? 0, 2) }}</strong></div>
        </div>
        <p><strong>Revenue − Expenses: {{ $currency }} {{ number_format($revenueMinusExpenses ?? 0, 2) }}</strong></p>
    </div>

    <div class="section">
        <h2>Profit Breakdown</h2>
        <table>
            <tr><td>Revenue</td><td class="text-right">{{ $currency }} {{ number_format($stockOutRevenue, 2) }}</td></tr>
            <tr><td>Cost of goods sold (COGS)</td><td class="text-right">− {{ $currency }} {{ number_format($cogs ?? 0, 2) }}</td></tr>
            <tr><td>Gross profit</td><td class="text-right">{{ $currency }} {{ number_format($grossProfit ?? 0, 2) }}</td></tr>
            <tr><td>Less: Feed & other expenses</td><td class="text-right">− {{ $currency }} {{ number_format(($totalFeedStockValue ?? 0) + ($otherExpensesTotal ?? 0), 2) }}</td></tr>
            <tr><td><strong>Net profit</strong></td><td class="text-right"><strong>{{ $currency }} {{ number_format($netProfit ?? 0, 2) }}</strong></td></tr>
        </table>
    </div>

    @if(!empty($keyInsights) && count($keyInsights) > 0)
    <div class="section">
        <h2>Key Insights</h2>
        <ul style="margin: 0; padding-left: 18px;">
            @foreach($keyInsights as $insight)
                <li>{{ $insight }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="section">
        <h2>Stock In (Period)</h2>
        @if($stockInDetail->isEmpty())
            <p>No stock-in records in this period.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Size</th>
                        <th class="text-right">Pieces</th>
                        <th class="text-right">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockInDetail as $row)
                        <tr>
                            <td>{{ $row->delivery_date?->format('M j, Y') ?? '—' }}</td>
                            <td>{{ $row->supplier_name ?? '—' }}</td>
                            <td>{{ $row->eggSize->size_name ?? '—' }}</td>
                            <td class="text-right">{{ number_format($row->quantity_pieces) }}</td>
                            <td class="text-right">{{ $currency }} {{ number_format($row->total_cost ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ number_format($stockInDetail->sum('quantity_pieces')) }}</strong></td>
                        <td class="text-right"><strong>{{ $currency }} {{ number_format($stockInDetail->sum('total_cost'), 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    </div>

    <div class="section">
        <h2>Sales (Period)</h2>
        @if($stockOutDetail->isEmpty())
            <p>No sales in this period.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Size</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($stockOutDetail->take(50) as $row)
                        <tr>
                            <td>{{ $row->transaction_date?->format('M j, Y H:i') ?? '—' }}</td>
                            <td>{{ $row->customer_name ?: '—' }}</td>
                            <td>{{ $row->eggSize->size_name ?? '—' }}</td>
                            <td class="text-right">{{ number_format($row->quantity) }}</td>
                            <td class="text-right">{{ $currency }} {{ number_format($row->total_amount ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4"><strong>Total</strong></td>
                        <td class="text-right"><strong>{{ $currency }} {{ number_format($stockOutDetail->sum('total_amount'), 2) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
            @if($stockOutDetail->count() > 50)
                <p style="font-size: 10px; color: #64748b;">Showing first 50 of {{ $stockOutDetail->count() }} sales. Full report available on web.</p>
            @endif
        @endif
    </div>

    <p class="footer">Data as of {{ now()->format('M j, Y') }} — subject to audit.</p>
</body>
</html>
