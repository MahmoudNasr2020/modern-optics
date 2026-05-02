<?php

namespace App\Http\Controllers;

use App\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

// Add this to your HomeController or DashboardController

    public function index()
    {
        $user = auth()->user();

        // ✅ Determine branch filter
        $filterBranchId = $user->branch_id; // Regular users see their branch only

        if ($user->canSeeAllBranches()) {
            $filterBranchId = null; // Super Admin sees all branches
        }

        // ===== STATISTICS =====

        // Base query with branch filter
        $invoiceQuery = Invoice::query();
        if ($filterBranchId) {
            $invoiceQuery->where('branch_id', $filterBranchId);
        }

        // Total Sales (all time)
        $totalSales = (clone $invoiceQuery)->sum('total');

        // Total Invoices
        $totalInvoices = (clone $invoiceQuery)->count();

        // Sales Growth (compare this month vs last month)
        $thisMonthSales = (clone $invoiceQuery)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');

        $lastMonthSales = (clone $invoiceQuery)
            ->whereBetween('created_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])
            ->sum('total');

        $salesGrowth = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // Invoices Today
        $invoicesToday = (clone $invoiceQuery)
            ->whereDate('created_at', today())
            ->count();

        // Customers Query
        $customerQuery = \App\Customer::query();
        if ($filterBranchId) {
            $customerQuery->where('branch_id', $filterBranchId);
        }

        $totalCustomers = $customerQuery->count();

        $newCustomersMonth = (clone $customerQuery)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count();

        // Products Query
        $productQuery = \App\Product::query();
        if ($filterBranchId) {
            $productQuery->where('branch_id', $filterBranchId);
        }

        $totalProducts = $productQuery->count();
        $activeProducts = (clone $productQuery)->count();

        // Pending Invoices
        $pendingInvoices = (clone $invoiceQuery)
            ->where('status', 'pending')
            ->count();

        // Items to Deliver (under_process or ready)
        $itemsToDeliver = \DB::table('invoice_items')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->whereIn('invoice_items.status', ['under_process', 'ready'])
            ->when($filterBranchId, function($q) use ($filterBranchId) {
                $q->where('invoices.branch_id', $filterBranchId);
            })
            ->count();

        // Monthly Revenue
        $monthlyRevenue = $thisMonthSales;
        $revenueGrowth = $salesGrowth;

        // Today's Sales
        $todaySales = (clone $invoiceQuery)
            ->whereDate('created_at', today())
            ->sum('total');

        // ===== CHARTS DATA =====

        // Sales Chart (Last 12 months)
        $salesChartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $salesChartData['labels'][] = $month->format('M Y');

            $monthlySales = (clone $invoiceQuery)
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');

            $salesChartData['data'][] = round($monthlySales, 2);
        }

        // Category Chart
        $categoryStats = \DB::table('invoice_items')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('products', 'products.product_id', '=', 'invoice_items.product_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->when($filterBranchId, function($q) use ($filterBranchId) {
                $q->where('invoices.branch_id', $filterBranchId);
            })
            ->select('categories.category_name', \DB::raw('COUNT(invoice_items.id) as count'))
            ->groupBy('categories.category_name')
            ->orderByDesc('count')
            ->limit(6)
            ->get();

        $categoryChartData = [
            'labels' => $categoryStats->pluck('category_name')->toArray(),
            'data' => $categoryStats->pluck('count')->toArray()
        ];

        // Payment Methods Chart
        $paymentStats = \App\CashierTransaction::query()
            ->when($filterBranchId, function($q) use ($filterBranchId) {
                $q->where(function($query) use ($filterBranchId) {
                    $query->whereHas('invoice', function($iq) use ($filterBranchId) {
                        $iq->where('branch_id', $filterBranchId);
                    })->orWhereHas('cashier', function($cq) use ($filterBranchId) {
                        $cq->where('branch_id', $filterBranchId);
                    });
                });
            })
            ->select('payment_type', \DB::raw('SUM(amount) as total'))
            ->groupBy('payment_type')
            ->get();

        $paymentChartData = [
            'labels' => $paymentStats->pluck('payment_type')->map(function($type) {
                return strtoupper($type);
            })->toArray(),
            'data' => $paymentStats->pluck('total')->map(function($amount) {
                return abs($amount);
            })->toArray()
        ];

        // ===== TOP PRODUCTS =====
        $topProducts = \DB::table('invoice_items')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->join('products', 'products.product_id', '=', 'invoice_items.product_id')
            ->when($filterBranchId, function($q) use ($filterBranchId) {
                $q->where('invoices.branch_id', $filterBranchId);
            })
            ->select(
                'products.description as name',
                \DB::raw('COUNT(invoice_items.id) as sales_count')
            )
            ->groupBy('products.product_id', 'products.description')
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        // ===== RECENT ACTIVITY =====
        $recentInvoices = (clone $invoiceQuery)
            ->with('customer')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ===== COMPILE STATS =====
        $stats = [
            'total_sales' => $totalSales,
            'total_invoices' => $totalInvoices,
            'sales_growth' => $salesGrowth,
            'invoices_today' => $invoicesToday,
            'total_customers' => $totalCustomers,
            'new_customers_month' => $newCustomersMonth,
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'pending_invoices' => $pendingInvoices,
            'items_to_deliver' => $itemsToDeliver,
            'monthly_revenue' => $monthlyRevenue,
            'revenue_growth' => $revenueGrowth,
            'today_sales' => $todaySales,
        ];

        return view('home', compact(
            'stats',
            'salesChartData',
            'categoryChartData',
            'paymentChartData',
            'topProducts',
            'recentInvoices'
        ));
    }
}
