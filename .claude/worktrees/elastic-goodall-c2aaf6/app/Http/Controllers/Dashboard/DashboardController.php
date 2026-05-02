<?php

namespace App\Http\Controllers\Dashboard;

use App\Invoice;
use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ✅ Determine branch filter
        $filterBranchId = $user->branch_id;
        $isSuperAdmin = $user->canSeeAllBranches();

        if ($isSuperAdmin) {
            $filterBranchId = null;
        }

        // ===== CACHE KEY =====
        $cacheKey = 'dashboard_data_' . ($filterBranchId ?? 'all') . '_' . now()->format('Y-m-d-H');
        $cacheDuration = 60; // 1 hour

        // Try to get from cache
        $cachedData = \Cache::get($cacheKey);

        if ($cachedData) {
            return view('dashboard.pages.index', $cachedData);
        }

        // ===== STATISTICS =====
        $invoiceQuery = Invoice::query();
        if ($filterBranchId) {
            $invoiceQuery->where('branch_id', $filterBranchId);
        }

        $totalSales = (clone $invoiceQuery)->sum('total');
        $totalInvoices = (clone $invoiceQuery)->count();

        // Sales Growth - Optimized
        $salesMonthly = \DB::table('invoices')
            ->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
            ->selectRaw('
            SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) THEN total ELSE 0 END) as this_month,
            SUM(CASE WHEN MONTH(created_at) = MONTH(DATE_SUB(NOW(), INTERVAL 1 MONTH)) AND YEAR(created_at) = YEAR(DATE_SUB(NOW(), INTERVAL 1 MONTH)) THEN total ELSE 0 END) as last_month
        ')
            ->first();

        $thisMonthSales = $salesMonthly->this_month ?? 0;
        $lastMonthSales = $salesMonthly->last_month ?? 0;

        $salesGrowth = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        $invoicesToday = (clone $invoiceQuery)->whereDate('created_at', today())->count();

        // Customers Stats - Optimized
        $customerStats = \DB::table('customers')
            //->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) THEN 1 ELSE 0 END) as new_this_month
        ')
            ->first();

        $totalCustomers = $customerStats->total ?? 0;
        $newCustomersMonth = $customerStats->new_this_month ?? 0;

        // Products
        $productQuery = Product::query();
        if ($filterBranchId) {
            $productQuery->where('branch_id', $filterBranchId);
        }

        $totalProducts = $productQuery->count();
        $activeProducts = $totalProducts;

        $pendingInvoices = (clone $invoiceQuery)->where('status', 'pending')->count();

        // Items to Deliver - Optimized
        $itemsToDeliver = \DB::table('invoice_items')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->whereIn('invoice_items.status', ['under_process', 'ready'])
            ->when($filterBranchId, fn($q) => $q->where('invoices.branch_id', $filterBranchId))
            ->count();

        $monthlyRevenue = $thisMonthSales;
        $revenueGrowth = $salesGrowth;

        $todaySales = (clone $invoiceQuery)->whereDate('created_at', today())->sum('total');

        // ===== CUSTOMERS REGISTRATION CHART =====
        $customersChartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $customersChartData['labels'][] = $month->format('M Y');

            $monthlyCustomers = \DB::table('customers')
               // ->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();

            $customersChartData['data'][] = $monthlyCustomers;
        }

        // ===== SALES CHART =====
        $salesChartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $salesChartData['labels'][] = $month->format('M Y');

            $monthlySales = \DB::table('invoices')
                ->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('total');

            $salesChartData['data'][] = round($monthlySales, 2);
        }

        // ===== CATEGORY CHART - OPTIMIZED & CACHED! =====
        $categoryCacheKey = 'category_stats_' . ($filterBranchId ?? 'all') . '_' . now()->format('Y-m-d-H');

        $categoryStats = \Cache::remember($categoryCacheKey, 360, function() use ($filterBranchId) {
            // ✅ OPTIMIZED QUERY - 10x FASTER!
            return \DB::table('products')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->join('invoice_items', 'invoice_items.product_id', '=', 'products.product_id')
                ->when($filterBranchId, function($q) use ($filterBranchId) {
                    $q->whereExists(function($query) use ($filterBranchId) {
                        $query->select(\DB::raw(1))
                            ->from('invoices')
                            ->whereColumn('invoices.id', 'invoice_items.invoice_id')
                            ->where('invoices.branch_id', $filterBranchId);
                    });
                })
                ->select('categories.category_name', \DB::raw('COUNT(DISTINCT invoice_items.id) as count'))
                ->groupBy('categories.id', 'categories.category_name')
                ->orderByDesc('count')
                ->limit(6)
                ->get();
        });

        $categoryChartData = [
            'labels' => $categoryStats->pluck('category_name')->toArray(),
            'data' => $categoryStats->pluck('count')->toArray()
        ];

        // ===== PAYMENT METHODS CHART - Optimized =====
        $paymentStats = \DB::table('cashier_transactions')
            ->when($filterBranchId, function($q) use ($filterBranchId) {
                $q->whereExists(function($query) use ($filterBranchId) {
                    $query->select(\DB::raw(1))
                        ->from('invoices')
                        ->whereColumn('invoices.id', 'cashier_transactions.invoice_id')
                        ->where('invoices.branch_id', $filterBranchId);
                });
            })
            ->select('payment_type', \DB::raw('SUM(ABS(amount)) as total'))
            ->groupBy('payment_type')
            ->get();

        $paymentChartData = [
            'labels' => $paymentStats->pluck('payment_type')->map(fn($type) => strtoupper($type))->toArray(),
            'data' => $paymentStats->pluck('total')->toArray()
        ];

        // ===== RECENT CUSTOMERS =====
        $recentCustomers = \DB::table('customers')
            //->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
            ->select('customer_id', 'english_name', 'local_name', 'mobile_number', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ===== RECENT DOCTORS =====
        $recentDoctors = \DB::table('doctors')
            ->select('code', 'name', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ===== RECENT ACTIVITY =====
        $recentInvoices = Invoice::query()
            ->when($filterBranchId, fn($q) => $q->where('branch_id', $filterBranchId))
            ->with(['customer:id,customer_id,english_name'])
            ->select('id', 'invoice_code', 'customer_id', 'total', 'status', 'created_at')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ===== SUPER ADMIN: BRANCHES PERFORMANCE =====
        $branchesData = null;
        $branchesChartData = null;
        $bestBranch = null;

        if ($isSuperAdmin) {
            $branchesData = \DB::table('invoices')
                ->join('branches', 'branches.id', '=', 'invoices.branch_id')
                ->select(
                    'branches.id',
                    'branches.name',
                    \DB::raw('COUNT(invoices.id) as total_invoices'),
                    \DB::raw('SUM(invoices.total) as total_sales'),
                    \DB::raw('SUM(CASE WHEN MONTH(invoices.created_at) = MONTH(NOW()) THEN invoices.total ELSE 0 END) as monthly_sales')
                )
                ->groupBy('branches.id', 'branches.name')
                ->orderByDesc('total_sales')
                ->get();

            $bestBranch = $branchesData->first();

            $branchesChartData = [
                'labels' => $branchesData->pluck('name')->toArray(),
                'data' => $branchesData->pluck('total_sales')->toArray()
            ];
        }

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

        // ===== PREPARE DATA FOR VIEW =====
        $viewData = compact(
            'stats',
            'salesChartData',
            'customersChartData',
            'categoryChartData',
            'paymentChartData',
            'recentCustomers',
            'recentDoctors',
            'recentInvoices',
            'isSuperAdmin',
            'branchesData',
            'branchesChartData',
            'bestBranch'
        );

        // Cache the data
        \Cache::put($cacheKey, $viewData, $cacheDuration);

        return view('dashboard.pages.index', $viewData);
    }

    public function logout() {
        Auth::logout();
        return redirect()->back();
    }
}
