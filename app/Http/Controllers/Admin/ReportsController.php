<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrackedEgg;
use App\Models\Feed;
use App\Models\Inventory;
use App\Models\Setting;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportsController extends Controller
{
    /**
     * Display reports page with optional date range and all report sections.
     */
    public function index(Request $request): View
    {
        return view('admin.reports.index', $this->getReportData($request));
    }

    /**
     * Export report as PDF for the given date range.
     */
    public function exportPdf(Request $request)
    {
        $data = $this->getReportData($request);
        $filename = 'report-' . $data['from'] . '-to-' . $data['to'] . '.pdf';

        return Pdf::loadView('admin.reports.pdf', $data)->download($filename);
    }

    /**
     * Build report data array (shared by index and exportPdf).
     */
    protected function getReportData(Request $request): array
    {
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();

        $setting = Setting::first();
        $businessName = $setting?->business_name ?: 'Egg Supply';
        $currency = $setting?->currency ?? 'PHP';

        // ——— Summary ———
        $stockInCount = StockIn::whereBetween('delivery_date', [$from, $to])->count();
        $stockInTotal = StockIn::whereBetween('delivery_date', [$from, $to])->sum('total_cost');
        $stockOutCount = StockOut::whereBetween('transaction_date', [$from, $to])->count();
        $stockOutRevenue = StockOut::whereBetween('transaction_date', [$from, $to])->sum('total_amount');
        $stockOutProfit = StockOut::whereBetween('transaction_date', [$from, $to])->sum('profit');
        $crackedQty = CrackedEgg::whereBetween('date_recorded', [$from, $to])->sum('quantity_cracked');
        $crackedCount = CrackedEgg::whereBetween('date_recorded', [$from, $to])->count();

        // ——— Stock In detail (period) ———
        $stockInDetail = StockIn::with('eggSize')
            ->whereBetween('delivery_date', [$from, $to])
            ->orderBy('delivery_date')
            ->orderBy('id')
            ->get();

        $piecesPerTray = (int) ($setting?->default_tray_size ?? 30);

        // ——— Stock Out / Sales detail (period) ———
        $stockOutDetail = StockOut::with('eggSize')
            ->whereBetween('transaction_date', [$from, $to])
            ->orderBy('transaction_date')
            ->orderBy('id')
            ->get();

        // ——— Cracked Eggs detail (period) ———
        $crackedDetail = CrackedEgg::with('eggSize')
            ->whereBetween('date_recorded', [$from, $to])
            ->orderBy('date_recorded')
            ->orderBy('id')
            ->get();

        // ——— Inventory snapshot (current) ———
        $inventorySnapshot = Inventory::with('eggSize')->orderBy('id')->get();

        // ——— Feed inventory snapshot (current) ———
        $feedsSnapshot = Feed::orderBy('name')->get();
        $totalFeedStockValue = (float) $feedsSnapshot->sum(fn ($f) => $f->stock_value ?? 0);

        // ——— Expenses ———
        $periodExpenses = (float) $stockInTotal;
        $totalExpenses = $periodExpenses + $totalFeedStockValue;
        $revenueMinusExpenses = (float) $stockOutRevenue - $totalExpenses;

        // ——— Sales by size (period) ———
        $salesBySize = StockOut::query()
            ->whereBetween('transaction_date', [$from, $to])
            ->select('egg_size_id', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_amount) as total_revenue'), DB::raw('SUM(profit) as total_profit'))
            ->groupBy('egg_size_id')
            ->with('eggSize')
            ->get();

        // ——— Stock In by size (period) ———
        $stockInBySize = StockIn::query()
            ->whereBetween('delivery_date', [$from, $to])
            ->select('egg_size_id', DB::raw('COUNT(*) as delivery_count'), DB::raw('SUM(quantity_pieces) as total_pieces'), DB::raw('SUM(total_cost) as total_cost'))
            ->groupBy('egg_size_id')
            ->with('eggSize')
            ->get();

        // ——— Cracked by size (period) ———
        $crackedBySize = CrackedEgg::query()
            ->whereBetween('date_recorded', [$from, $to])
            ->select('egg_size_id', DB::raw('SUM(quantity_cracked) as total_cracked'), DB::raw('COUNT(*) as record_count'))
            ->groupBy('egg_size_id')
            ->with('eggSize')
            ->get();

        // ——— Previous period ———
        $daysDiff = $from->diffInDays($to) + 1;
        $prevTo = $from->copy()->subDay()->endOfDay();
        $prevFrom = $prevTo->copy()->subDays($daysDiff - 1)->startOfDay();
        $prevStockInTotal = (float) StockIn::whereBetween('delivery_date', [$prevFrom, $prevTo])->sum('total_cost');
        $prevRevenue = (float) StockOut::whereBetween('transaction_date', [$prevFrom, $prevTo])->sum('total_amount');
        $prevSalesCount = StockOut::whereBetween('transaction_date', [$prevFrom, $prevTo])->count();
        $prevCracked = (float) CrackedEgg::whereBetween('date_recorded', [$prevFrom, $prevTo])->sum('quantity_cracked');

        // ——— Top customers ———
        $topCustomers = StockOut::query()
            ->whereBetween('transaction_date', [$from, $to])
            ->select('customer_name', DB::raw('COUNT(*) as transaction_count'), DB::raw('SUM(total_amount) as total_amount'))
            ->groupBy('customer_name')
            ->orderByDesc('total_amount')
            ->limit(10)
            ->get();

        // ——— COGS & profit ———
        $cogs = (float) $stockInTotal;
        $grossProfit = (float) $stockOutRevenue - $cogs;

        // ——— Cracked loss ———
        $totalPiecesWithCost = $stockInDetail->sum('quantity_pieces');
        $avgCostPerPiece = $totalPiecesWithCost > 0 && $stockInTotal > 0 ? (float) $stockInTotal / $totalPiecesWithCost : null;
        $crackedLossEstimate = $avgCostPerPiece !== null && $crackedQty > 0 ? (float) round($crackedQty * $avgCostPerPiece, 2) : null;
        $totalSoldPieces = $stockOutDetail->sum(fn ($r) => $r->quantity * $piecesPerTray);
        $avgSellPricePerPiece = $totalSoldPieces > 0 && $stockOutRevenue > 0 ? (float) $stockOutRevenue / $totalSoldPieces : null;
        $crackedLossAtSellPrice = $avgSellPricePerPiece !== null && $crackedQty > 0 ? (float) round($crackedQty * $avgSellPricePerPiece, 2) : null;

        // ——— Inventory alerts & stock duration ———
        $daysInPeriod = max(1, $from->diffInDays($to) + 1);
        $salesBySizeMap = $salesBySize->keyBy('egg_size_id');
        $crackedBySizeMap = $crackedBySize->keyBy('egg_size_id');
        $stockInBySizeMap = $stockInBySize->keyBy('egg_size_id');
        $inventoryAlerts = [];
        $stockForecasts = [];
        $startingInventoryPieces = [];
        foreach ($inventorySnapshot as $inv) {
            $sizeId = $inv->egg_size_id;
            $sizeName = $inv->eggSize->size_name ?? '—';
            $current = (int) $inv->current_stock_pieces;
            $minAlert = (int) $inv->minimum_stock_alert;
            $soldTrays = (int) ($salesBySizeMap->get($sizeId)->total_quantity ?? 0);
            $soldPieces = $soldTrays * $piecesPerTray;
            $crackedPiecesSize = (int) ($crackedBySizeMap->get($sizeId)->total_cracked ?? 0);
            $stockInPiecesSize = (int) ($stockInBySizeMap->get($sizeId)->total_pieces ?? 0);
            $totalOutflowPieces = $soldPieces + $crackedPiecesSize;
            $avgDailyOutflow = $daysInPeriod > 0 && $totalOutflowPieces > 0 ? $totalOutflowPieces / $daysInPeriod : 0.0;
            $daysStockLasts = $avgDailyOutflow > 0 ? (int) floor($current / $avgDailyOutflow) : null;
            $startingPieces = $current - $stockInPiecesSize + $soldPieces + $crackedPiecesSize;
            $startingInventoryPieces[$sizeId] = max(0, $startingPieces);
            if ($minAlert > 0 && $current <= $minAlert) {
                $inventoryAlerts[] = ['size' => $sizeName, 'message' => 'Low stock — reorder soon.'];
            }
            if ($daysStockLasts !== null && $daysStockLasts <= 7 && $current > 0) {
                $inventoryAlerts[] = ['size' => $sizeName, 'message' => "Based on current sales rate, {$sizeName} stock will last ~{$daysStockLasts} days."];
            }
            $stockForecasts[] = [
                'size_name' => $sizeName,
                'current_pieces' => $current,
                'avg_daily_outflow_pieces' => round($avgDailyOutflow, 1),
                'days_stock_last' => $daysStockLasts,
                'no_sales_in_period' => $soldPieces === 0 && $crackedPiecesSize === 0,
            ];
        }

        // ——— Key insights ———
        $keyInsights = [];
        if ($revenueMinusExpenses < 0 && $totalExpenses > 0) {
            $keyInsights[] = "Net loss of {$currency} " . number_format(abs($revenueMinusExpenses), 2) . ' — review expenses (egg purchases and feed).';
        }
        if ($stockInTotal == 0 && $stockInCount > 0) {
            $keyInsights[] = 'Zero costs recorded — input supplier prices for accurate gross profit.';
            $keyInsights[] = number_format($stockInDetail->sum('quantity_pieces')) . ' pieces stocked in with zero cost — verify data entry.';
        }
        if ($crackedQty > 0) {
            if ($crackedLossEstimate !== null) {
                $keyInsights[] = "Cracked eggs: " . number_format($crackedQty) . " pieces (est. value lost: {$currency} " . number_format($crackedLossEstimate, 2) . '). Review handling or transport.';
            } elseif ($crackedLossAtSellPrice !== null) {
                $keyInsights[] = "Cracked eggs: " . number_format($crackedQty) . " pieces (est. value lost at sell price: {$currency} " . number_format($crackedLossAtSellPrice, 2) . '). Review handling or transport.';
            }
        }
        if ($totalFeedStockValue > 0 && $totalFeedStockValue > $stockInTotal) {
            $keyInsights[] = 'Feed inventory value exceeds egg purchase cost this period — consider bulk feed discounts.';
        }
        foreach ($inventoryAlerts as $a) {
            $keyInsights[] = $a['size'] . ': ' . $a['message'];
        }

        // ——— Other expenses / income ———
        $reportOtherExpenses = $setting?->report_other_expenses ?? [];
        $reportOtherIncome = $setting?->report_other_income ?? [];
        $otherExpensesTotal = is_array($reportOtherExpenses) ? (float) array_sum(array_column($reportOtherExpenses, 'amount')) : 0;
        $otherIncomeTotal = is_array($reportOtherIncome) ? (float) array_sum(array_column($reportOtherIncome, 'amount')) : 0;
        $netProfit = $revenueMinusExpenses - $otherExpensesTotal + $otherIncomeTotal;

        return [
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
            'businessName' => $businessName,
            'currency' => $currency,
            'piecesPerTray' => $piecesPerTray,
            'stockInCount' => $stockInCount,
            'stockInTotal' => $stockInTotal,
            'stockOutCount' => $stockOutCount,
            'stockOutRevenue' => $stockOutRevenue,
            'stockOutProfit' => $stockOutProfit,
            'crackedQty' => $crackedQty,
            'crackedCount' => $crackedCount,
            'stockInDetail' => $stockInDetail,
            'stockOutDetail' => $stockOutDetail,
            'crackedDetail' => $crackedDetail,
            'inventorySnapshot' => $inventorySnapshot,
            'feedsSnapshot' => $feedsSnapshot,
            'totalFeedStockValue' => $totalFeedStockValue,
            'periodExpenses' => $periodExpenses,
            'totalExpenses' => $totalExpenses,
            'revenueMinusExpenses' => $revenueMinusExpenses,
            'salesBySize' => $salesBySize,
            'stockInBySize' => $stockInBySize,
            'crackedBySize' => $crackedBySize,
            'prevFrom' => $prevFrom->format('Y-m-d'),
            'prevTo' => $prevTo->format('Y-m-d'),
            'prevStockInTotal' => $prevStockInTotal,
            'prevRevenue' => $prevRevenue,
            'prevSalesCount' => $prevSalesCount,
            'prevCracked' => $prevCracked,
            'topCustomers' => $topCustomers,
            'cogs' => $cogs,
            'grossProfit' => $grossProfit,
            'netProfit' => $netProfit,
            'totalExpensesInclOther' => $totalExpenses + $otherExpensesTotal,
            'crackedLossEstimate' => $crackedLossEstimate,
            'crackedLossAtSellPrice' => $crackedLossAtSellPrice ?? null,
            'avgCostPerPiece' => $avgCostPerPiece,
            'startingInventoryPieces' => $startingInventoryPieces,
            'inventoryAlerts' => $inventoryAlerts,
            'stockForecasts' => $stockForecasts,
            'keyInsights' => $keyInsights,
            'daysInPeriod' => $daysInPeriod,
            'reportOtherExpenses' => $reportOtherExpenses,
            'reportOtherIncome' => $reportOtherIncome,
            'otherExpensesTotal' => $otherExpensesTotal,
            'otherIncomeTotal' => $otherIncomeTotal,
            'logoUrl' => $setting?->logo_url,
        ];
    }

    /**
     * Export report as CSV for the given date range.
     */
    public function export(Request $request)
    {
        $from = $request->input('from') ? Carbon::parse($request->input('from'))->startOfDay() : now()->startOfMonth();
        $to = $request->input('to') ? Carbon::parse($request->input('to'))->endOfDay() : now()->endOfDay();
        $setting = Setting::first();
        $businessName = $setting?->business_name ?: 'Egg Supply';
        $currency = $setting?->currency ?? 'PHP';

        $stockInCount = StockIn::whereBetween('delivery_date', [$from, $to])->count();
        $stockInTotal = StockIn::whereBetween('delivery_date', [$from, $to])->sum('total_cost');
        $stockOutCount = StockOut::whereBetween('transaction_date', [$from, $to])->count();
        $stockOutRevenue = StockOut::whereBetween('transaction_date', [$from, $to])->sum('total_amount');
        $stockOutProfit = StockOut::whereBetween('transaction_date', [$from, $to])->sum('profit');
        $crackedQty = CrackedEgg::whereBetween('date_recorded', [$from, $to])->sum('quantity_cracked');

        $rows = [
            [$businessName . ' — Report'],
            ['Period: ' . $from->format('Y-m-d') . ' to ' . $to->format('Y-m-d')],
            ['Generated: ' . now()->format('Y-m-d H:i')],
            [],
            ['Summary', ''],
            ['Stock In (deliveries)', $stockInCount],
            ['Stock In (total cost)', $currency . ' ' . number_format($stockInTotal, 2)],
            ['Sales (transactions)', $stockOutCount],
            ['Revenue', $currency . ' ' . number_format($stockOutRevenue, 2)],
            ['Profit', $currency . ' ' . number_format($stockOutProfit ?? 0, 2)],
            ['Cracked eggs (pieces)', $crackedQty],
            [],
            ['Expenses', ''],
            ['Egg purchases (Stock In) — period', $currency . ' ' . number_format($stockInTotal, 2)],
            ['Feed (inventory value)', $currency . ' ' . number_format($feedStockValueExport = (float) Feed::orderBy('name')->get()->sum(fn ($f) => $f->stock_value ?? 0), 2)],
            ['Total expenses (purchases + feed)', $currency . ' ' . number_format($totalExpensesExport = $stockInTotal + $feedStockValueExport, 2)],
            [],
            ['Revenue − Expenses', $currency . ' ' . number_format($stockOutRevenue - $totalExpensesExport, 2)],
        ];

        $stockInDetail = StockIn::with('eggSize')->whereBetween('delivery_date', [$from, $to])->orderBy('delivery_date')->get();
        if ($stockInDetail->isNotEmpty()) {
            $rows[] = [];
            $rows[] = ['Stock In Detail', '', '', '', ''];
            $rows[] = ['Date', 'Supplier', 'Size', 'Pieces', 'Total Cost'];
            foreach ($stockInDetail as $row) {
                $rows[] = [
                    $row->delivery_date?->format('Y-m-d'),
                    $row->supplier_name ?? '',
                    $row->eggSize->size_name ?? '—',
                    $row->quantity_pieces ?? 0,
                    $currency . ' ' . number_format($row->total_cost ?? 0, 2),
                ];
            }
        }

        $stockOutDetail = StockOut::with('eggSize')->whereBetween('transaction_date', [$from, $to])->orderBy('transaction_date')->get();
        if ($stockOutDetail->isNotEmpty()) {
            $rows[] = [];
            $rows[] = ['Sales Detail', '', '', '', '', ''];
            $rows[] = ['Date', 'Customer', 'Size', 'Qty', 'Amount', 'Profit'];
            foreach ($stockOutDetail as $row) {
                $rows[] = [
                    $row->transaction_date?->format('Y-m-d'),
                    $row->customer_name ?? '',
                    $row->eggSize->size_name ?? '—',
                    $row->quantity ?? 0,
                    $currency . ' ' . number_format($row->total_amount ?? 0, 2),
                    $currency . ' ' . number_format($row->profit ?? 0, 2),
                ];
            }
        }

        $feedsSnapshot = Feed::orderBy('name')->get();
        if ($feedsSnapshot->isNotEmpty()) {
            $rows[] = [];
            $rows[] = ['Feed Inventory (as of ' . now()->format('Y-m-d') . ')', '', '', '', '', ''];
            $rows[] = ['Name', 'Quantity', 'Unit', 'Cost/unit', 'Stock value', 'Status'];
            foreach ($feedsSnapshot as $feed) {
                $rows[] = [
                    $feed->name,
                    number_format($feed->quantity, 2),
                    $feed->unit,
                    $feed->cost_per_unit !== null ? $currency . ' ' . number_format($feed->cost_per_unit, 2) : '—',
                    $feed->stock_value !== null ? $currency . ' ' . number_format($feed->stock_value, 2) : '—',
                    $feed->minimum_stock_alert > 0 && $feed->quantity <= $feed->minimum_stock_alert ? 'Low stock' : 'OK',
                ];
            }
        }

        $csv = implode("\n", array_map(function ($row) {
            return implode(',', array_map(function ($cell) {
                return '"' . str_replace('"', '""', (string) $cell) . '"';
            }, $row));
        }, $rows));

        $filename = 'egg-report-' . $from->format('Y-m-d') . '-to-' . $to->format('Y-m-d') . '.csv';

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
