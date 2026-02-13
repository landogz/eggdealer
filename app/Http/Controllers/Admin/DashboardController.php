<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CrackedEgg;
use App\Models\EggPrice;
use App\Models\EggSize;
use App\Models\Feed;
use App\Models\Inventory;
use App\Models\Setting;
use App\Models\StockIn;
use App\Models\StockOut;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Collection;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with stats and quick links.
     */
    public function index(): View
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $today = $now->copy()->startOfDay();
        $endOfToday = $now->copy()->endOfDay();

        $eggSizesCount = EggSize::count();
        $activePricesCount = EggPrice::where('status', 'active')->count();
        $totalEggPricesCount = EggPrice::count();

        $inventories = Inventory::with('eggSize')->get();
        $totalStockPieces = $inventories->sum('current_stock_pieces');
        $totalStockTrays = $inventories->sum('current_stock_trays');
        $lowStockCount = $inventories->filter(function ($inv) {
            return $inv->minimum_stock_alert > 0 && $inv->current_stock_pieces <= $inv->minimum_stock_alert;
        })->count();

        $feeds = Feed::orderBy('name')->get();
        $lowFeedStockCount = $feeds->filter(fn (Feed $f) => $f->isLowStock())->count();
        $totalFeedStockValue = (float) $feeds->sum(function (Feed $f) {
            $v = $f->stock_value;
            return $v !== null ? $v : 0;
        });

        $setting = Setting::first();
        $currency = $setting?->currency ?? 'PHP';

        $stockInThisMonth = StockIn::whereDate('delivery_date', '>=', $startOfMonth)
            ->whereDate('delivery_date', '<=', $endOfMonth)
            ->count();
        $stockInTotalCostThisMonth = (float) StockIn::whereDate('delivery_date', '>=', $startOfMonth)
            ->whereDate('delivery_date', '<=', $endOfMonth)
            ->sum('total_cost');

        $stockOutThisMonth = StockOut::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->count();
        $stockOutTotalAmountThisMonth = StockOut::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('total_amount');
        $stockOutProfitThisMonth = StockOut::whereBetween('transaction_date', [$startOfMonth, $endOfMonth])->sum('profit');

        $crackedThisMonth = CrackedEgg::whereBetween('date_recorded', [$startOfMonth, $endOfMonth])->sum('quantity_cracked');
        $crackedTotalCount = CrackedEgg::count();

        // Today metrics
        $salesTodayAmount = StockOut::whereBetween('transaction_date', [$today, $endOfToday])->sum('total_amount');
        $stockInTodayCount = StockIn::whereDate('delivery_date', $today)->count();
        $stockOutTodayCount = StockOut::whereBetween('transaction_date', [$today, $endOfToday])->count();
        $crackedTodayCount = CrackedEgg::whereBetween('date_recorded', [$today, $endOfToday])->sum('quantity_cracked');

        // Yesterday metrics (for Sales Overview + Revenue vs Expenses filter)
        $yesterdayStart = $now->copy()->subDay()->startOfDay();
        $yesterdayEnd = $now->copy()->subDay()->endOfDay();
        $salesYesterdayAmount = StockOut::whereBetween('transaction_date', [$yesterdayStart, $yesterdayEnd])->sum('total_amount');
        $expenseToday = (float) StockIn::whereDate('delivery_date', $today)->sum('total_cost') + $totalFeedStockValue;
        $expenseYesterday = (float) StockIn::whereDate('delivery_date', $yesterdayStart)->sum('total_cost') + $totalFeedStockValue;

        // This month daily (for Sales Overview + Revenue vs Expenses: "This month")
        $chartSalesThisMonthDays = collect();
        $chartSalesThisMonthValues = collect();
        $chartRevExpThisMonthExpense = collect();
        $dayCursor = $startOfMonth->copy();
        while ($dayCursor <= $now) {
            $chartSalesThisMonthDays->push($dayCursor->format('M j'));
            $endOfDay = $dayCursor->copy()->endOfDay();
            $chartSalesThisMonthValues->push(
                (float) StockOut::whereBetween('transaction_date', [$dayCursor, $endOfDay])->sum('total_amount')
            );
            $chartRevExpThisMonthExpense->push(
                (float) StockIn::whereDate('delivery_date', $dayCursor)->sum('total_cost') + $totalFeedStockValue
            );
            $dayCursor->addDay();
        }

        // Last month daily (for Sales Overview + Revenue vs Expenses: "Last month")
        $lastMonthStart = $now->copy()->subMonthNoOverflow()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonthNoOverflow()->endOfMonth();
        $chartSalesLastMonthDays = collect();
        $chartSalesLastMonthValues = collect();
        $chartRevExpLastMonthExpense = collect();
        $dayCursor = $lastMonthStart->copy();
        while ($dayCursor <= $lastMonthEnd) {
            $chartSalesLastMonthDays->push($dayCursor->format('M j'));
            $endOfDay = $dayCursor->copy()->endOfDay();
            $chartSalesLastMonthValues->push(
                (float) StockOut::whereBetween('transaction_date', [$dayCursor, $endOfDay])->sum('total_amount')
            );
            $chartRevExpLastMonthExpense->push(
                (float) StockIn::whereDate('delivery_date', $dayCursor)->sum('total_cost') + $totalFeedStockValue
            );
            $dayCursor->addDay();
        }

        // Charts data - last 12 months (Revenue vs Expenses)
        $months = collect();
        $salesSeries = collect();
        $expenseSeries = collect();

        for ($i = 11; $i >= 0; $i--) {
            $monthStart = $startOfMonth->copy()->subMonthsNoOverflow($i);
            $monthEnd = $monthStart->copy()->endOfMonth();
            $label = $monthStart->format('M');

            $months->push($label);
            $salesSeries->push(
                (float) StockOut::whereBetween('transaction_date', [$monthStart, $monthEnd])->sum('total_amount')
            );
            $expenseSeries->push(
                (float) StockIn::whereDate('delivery_date', '>=', $monthStart)
                    ->whereDate('delivery_date', '<=', $monthEnd)
                    ->sum('total_cost') + $totalFeedStockValue
            );
        }

        $inventoryBySize = $inventories->map(function (Inventory $inv) {
            return [
                'label' => optional($inv->eggSize)->size_name ?? '—',
                'value' => (int) $inv->current_stock_pieces,
            ];
        });

        $inventoryBySizeTrays = $inventories->map(function (Inventory $inv) {
            return [
                'label' => optional($inv->eggSize)->size_name ?? '—',
                'value' => (int) $inv->current_stock_trays,
            ];
        });

        $crackedBySizeRaw = CrackedEgg::selectRaw('egg_size_id, SUM(quantity_cracked) as total')
            ->groupBy('egg_size_id')
            ->with('eggSize')
            ->get();

        $totalCrackedAll = $crackedBySizeRaw->sum('total') ?: 1;
        $crackedBySize = $crackedBySizeRaw->map(function (CrackedEgg $row) use ($totalCrackedAll) {
            return [
                'label' => optional($row->eggSize)->size_name ?? 'Unknown',
                'value' => round(($row->total / $totalCrackedAll) * 100, 1),
            ];
        });

        return view('admin.dashboard', [
            'eggSizesCount' => $eggSizesCount,
            'activePricesCount' => $activePricesCount,
            'totalEggPricesCount' => $totalEggPricesCount,
            'totalStockPieces' => $totalStockPieces,
            'totalStockTrays' => $totalStockTrays,
            'lowStockCount' => $lowStockCount,
            'feeds' => $feeds,
            'lowFeedStockCount' => $lowFeedStockCount,
            'totalFeedStockValue' => $totalFeedStockValue,
            'currency' => $currency,
            'inventories' => $inventories,
            'stockInThisMonth' => $stockInThisMonth,
            'stockInTotalCostThisMonth' => $stockInTotalCostThisMonth,
            'totalExpensesThisMonth' => $stockInTotalCostThisMonth + $totalFeedStockValue,
            'netRevenueThisMonth' => $stockOutTotalAmountThisMonth - ($stockInTotalCostThisMonth + $totalFeedStockValue),
            'stockOutThisMonth' => $stockOutThisMonth,
            'stockOutTotalAmountThisMonth' => $stockOutTotalAmountThisMonth,
            'stockOutProfitThisMonth' => $stockOutProfitThisMonth,
            'crackedThisMonth' => $crackedThisMonth,
            'crackedTotalCount' => $crackedTotalCount,
            'salesTodayAmount' => $salesTodayAmount,
            'salesYesterdayAmount' => $salesYesterdayAmount,
            'chartSalesThisMonthDays' => $chartSalesThisMonthDays,
            'chartSalesThisMonthValues' => $chartSalesThisMonthValues,
            'chartSalesLastMonthDays' => $chartSalesLastMonthDays,
            'chartSalesLastMonthValues' => $chartSalesLastMonthValues,
            'chartRevExpThisMonthExpense' => $chartRevExpThisMonthExpense,
            'chartRevExpLastMonthExpense' => $chartRevExpLastMonthExpense,
            'stockInTodayCount' => $stockInTodayCount,
            'stockOutTodayCount' => $stockOutTodayCount,
            'crackedTodayCount' => $crackedTodayCount,
            'revExpTodayExpense' => $expenseToday,
            'revExpYesterdayExpense' => $expenseYesterday,
            'chartMonths' => $months,
            'chartSalesSeries' => $salesSeries,
            'chartExpenseSeries' => $expenseSeries,
            'chartInventoryBySize' => $inventoryBySize,
            'chartInventoryBySizeTrays' => $inventoryBySizeTrays,
            'chartCrackedBySize' => $crackedBySize,
        ]);
    }
}

