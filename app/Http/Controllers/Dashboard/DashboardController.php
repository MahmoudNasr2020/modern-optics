<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $filterBranchId = $user->canSeeAllBranches() ? null : $user->branch_id;
        $isSuperAdmin   = $user->canSeeAllBranches();

        // ── Cache: 24 hours, keyed by branch + date (not hour) ──────────────
        $cacheKey = 'dashboard_v2_' . ($filterBranchId ?? 'all') . '_' . now()->format('Y-m-d');
        $cached   = \Cache::get($cacheKey);
        if ($cached) {
            return view('dashboard.pages.index', $cached);
        }

        // ── Pre-compute date boundaries (avoids repeated now() calls) ───────
        $todayStart      = now()->startOfDay()->toDateTimeString();
        $todayEnd        = now()->endOfDay()->toDateTimeString();
        $thisMonthStart  = now()->startOfMonth()->toDateTimeString();
        $thisMonthEnd    = now()->endOfMonth()->toDateTimeString();
        $lastMonthStart  = now()->subMonth()->startOfMonth()->toDateTimeString();
        $lastMonthEnd    = now()->subMonth()->endOfMonth()->toDateTimeString();
        $chartStart      = now()->subMonths(11)->startOfMonth()->toDateTimeString();

        // ── Invoice summary: one query, date-range WHERE (index-friendly) ───
        $inv = \DB::table('invoices')
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('branch_id', $filterBranchId);
            })
            ->selectRaw("
                COUNT(*)                                                              AS total_invoices,
                COALESCE(SUM(total), 0)                                              AS total_sales,
                COALESCE(SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN total END), 0) AS this_month,
                COALESCE(SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN total END), 0) AS last_month,
                COALESCE(SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN 1   END), 0) AS today_count,
                COALESCE(SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN total END), 0) AS today_sales,
                COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 END), 0)            AS pending_count
            ", [
                $thisMonthStart, $thisMonthEnd,
                $lastMonthStart, $lastMonthEnd,
                $todayStart,     $todayEnd,
                $todayStart,     $todayEnd,
            ])
            ->first();

        $totalSales      = $inv->total_sales      ?? 0;
        $totalInvoices   = $inv->total_invoices   ?? 0;
        $thisMonthSales  = $inv->this_month        ?? 0;
        $lastMonthSales  = $inv->last_month        ?? 0;
        $invoicesToday   = $inv->today_count       ?? 0;
        $todaySales      = $inv->today_sales       ?? 0;
        $pendingInvoices = $inv->pending_count     ?? 0;
        $salesGrowth     = $lastMonthSales > 0
            ? (($thisMonthSales - $lastMonthSales) / $lastMonthSales) * 100
            : 0;

        // ── Customers ────────────────────────────────────────────────────────
        $cust = \DB::table('customers')
            ->selectRaw("
                COUNT(*) AS total,
                COALESCE(SUM(CASE WHEN created_at >= ? AND created_at <= ? THEN 1 END), 0) AS new_this_month
            ", [$thisMonthStart, $thisMonthEnd])
            ->first();

        $totalCustomers    = $cust->total           ?? 0;
        $newCustomersMonth = $cust->new_this_month  ?? 0;

        // ── Products count ────────────────────────────────────────────────────
        $totalProducts  = \DB::table('products')
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('branch_id', $filterBranchId);
            })
            ->count();
        $activeProducts = $totalProducts;

        // ── Items to deliver ──────────────────────────────────────────────────
        $itemsToDeliver = \DB::table('invoice_items')
            ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
            ->whereIn('invoice_items.status', ['under_process', 'ready'])
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('invoices.branch_id', $filterBranchId);
            })
            ->count();

        $monthlyRevenue = $thisMonthSales;
        $revenueGrowth  = $salesGrowth;

        // ── Monthly charts (single queries each) ─────────────────────────────
        $customersRaw = \DB::table('customers')
            ->selectRaw('YEAR(created_at) AS yr, MONTH(created_at) AS mo, COUNT(*) AS cnt')
            ->where('created_at', '>=', $chartStart)
            ->groupBy('yr', 'mo')
            ->get()
            ->keyBy(function ($r) { return $r->yr . '-' . $r->mo; });

        $salesRaw = \DB::table('invoices')
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('branch_id', $filterBranchId);
            })
            ->selectRaw('YEAR(created_at) AS yr, MONTH(created_at) AS mo, SUM(total) AS total')
            ->where('created_at', '>=', $chartStart)
            ->groupBy('yr', 'mo')
            ->get()
            ->keyBy(function ($r) { return $r->yr . '-' . $r->mo; });

        $customersChartData = ['labels' => [], 'data' => []];
        $salesChartData     = ['labels' => [], 'data' => []];
        for ($i = 11; $i >= 0; $i--) {
            $m   = now()->subMonths($i);
            $key = $m->year . '-' . $m->month;
            $customersChartData['labels'][] = $m->format('M Y');
            $customersChartData['data'][]   = $customersRaw->has($key) ? (int) $customersRaw->get($key)->cnt : 0;
            $salesChartData['labels'][]     = $m->format('M Y');
            $salesChartData['data'][]       = $salesRaw->has($key) ? round($salesRaw->get($key)->total, 2) : 0;
        }

        // ── Category chart: count invoice_items by category (cached 12h) ────
        $catCacheKey  = 'dash_cat_' . ($filterBranchId ?? 'all') . '_' . now()->format('Y-m-d');
        $categoryStats = \Cache::remember($catCacheKey, 720, function () use ($filterBranchId) {
            // Join invoice_items → invoices (for branch filter) → products → categories
            // Use product_id integer FK (invoice_items.product_id → products.id via invoice_items)
            // Fallback: count by category_id on products joined through invoice_items.product_id (string)
            return \DB::table('invoice_items')
                ->join('invoices', 'invoices.id', '=', 'invoice_items.invoice_id')
                ->join('products', 'products.product_id', '=', 'invoice_items.product_id')
                ->join('categories', 'categories.id', '=', 'products.category_id')
                ->when($filterBranchId, function ($q) use ($filterBranchId) {
                    $q->where('invoices.branch_id', $filterBranchId);
                })
                ->where('invoice_items.type', '!=', 'lens')
                ->select('categories.category_name', \DB::raw('COUNT(invoice_items.id) AS cnt'))
                ->groupBy('categories.id', 'categories.category_name')
                ->orderByDesc('cnt')
                ->limit(6)
                ->get();
        });

        $categoryChartData = [
            'labels' => $categoryStats->pluck('category_name')->toArray(),
            'data'   => $categoryStats->pluck('cnt')->toArray(),
        ];

        // ── Payment chart: simple group-by, no subquery ───────────────────────
        $paymentStats = \DB::table('cashier_transactions')
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->join('invoices', 'invoices.id', '=', 'cashier_transactions.invoice_id')
                  ->where('invoices.branch_id', $filterBranchId);
            })
            ->select('payment_type', \DB::raw('SUM(ABS(amount)) AS total'))
            ->groupBy('payment_type')
            ->get();

        $paymentChartData = [
            'labels' => $paymentStats->pluck('payment_type')->map(function ($t) { return strtoupper($t); })->toArray(),
            'data'   => $paymentStats->pluck('total')->toArray(),
        ];

        // ── Recent rows (cheap LIMIT queries) ────────────────────────────────
        $recentCustomers = \DB::table('customers')
            ->select('customer_id', 'english_name', 'local_name', 'mobile_number', 'created_at')
            ->orderByDesc('created_at')->limit(5)->get();

        $recentDoctors = \DB::table('doctors')
            ->select('code', 'name', 'created_at')
            ->orderByDesc('created_at')->limit(5)->get();

        $recentInvoices = \DB::table('invoices')
            ->join('customers', 'customers.id', '=', 'invoices.customer_id')
            ->when($filterBranchId, function ($q) use ($filterBranchId) {
                $q->where('invoices.branch_id', $filterBranchId);
            })
            ->select(
                'invoices.id', 'invoices.invoice_code', 'invoices.total',
                'invoices.status', 'invoices.created_at',
                'customers.english_name AS customer_name',
                'customers.customer_id  AS customer_code'
            )
            ->orderByDesc('invoices.created_at')->limit(5)->get();

        // ── Super-admin: branch performance ──────────────────────────────────
        $branchesData      = null;
        $branchesChartData = null;
        $bestBranch        = null;

        if ($isSuperAdmin) {
            $branchesData = \DB::table('invoices')
                ->join('branches', 'branches.id', '=', 'invoices.branch_id')
                ->select(
                    'branches.id', 'branches.name',
                    \DB::raw('COUNT(invoices.id)  AS total_invoices'),
                    \DB::raw('SUM(invoices.total) AS total_sales'),
                    \DB::raw("COALESCE(SUM(CASE WHEN invoices.created_at >= '{$thisMonthStart}' AND invoices.created_at <= '{$thisMonthEnd}' THEN invoices.total END), 0) AS monthly_sales")
                )
                ->groupBy('branches.id', 'branches.name')
                ->orderByDesc('total_sales')
                ->get();

            $bestBranch        = $branchesData->first();
            $branchesChartData = [
                'labels' => $branchesData->pluck('name')->toArray(),
                'data'   => $branchesData->pluck('total_sales')->toArray(),
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

        // Cache for 24 hours (resets at midnight via date-keyed key)
        \Cache::put($cacheKey, $viewData, 1440);

        return view('dashboard.pages.index', $viewData);
    }

    public function logout() {
        Auth::logout();
        return redirect()->back();
    }
}
