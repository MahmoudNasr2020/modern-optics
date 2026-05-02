<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{

    public function __construct()
    {
        // عرض الفروع
        $this->middleware('permission.spatie:view-branches')->only([
            'index',
            'show',
            'statistics',
            'statisticsPdf',
            'report',
        ]);

        // إضافة فرع
        $this->middleware('permission.spatie:create-branches')->only([
            'create',
            'store',
        ]);

        // تعديل فرع
        $this->middleware('permission.spatie:edit-branches')->only([
            'edit',
            'update',
            'toggleStatus',
        ]);

        // حذف فرع
        $this->middleware('permission.spatie:delete-branches')->only([
            'destroy',
        ]);
    }

    /**
     * عرض قائمة الفروع
     */
    /*public function index()
    {
        $branches = Branch::withCount(['users', 'invoices'])
                          ->with(['users' => function($q) {
                              $q->latest()->take(3);
                          }])
                          ->latest()
                          ->paginate(15);

        return view('dashboard.pages.branches.index', compact('branches'));
    }*/

    /**
     * عرض قائمة الفروع
     */
    public function index(Request $request)
    {
        $query = Branch::withCount(['users', 'invoices'])
            ->with(['users' => function($q) {
                $q->latest()->take(3);
            }]);

        // تطبيق فلتر البرانش باستخدام الـ helper method الموجود
        $query = auth()->user()->applyBranchFilter($query, $request->branch_id, 'id');

        // البحث (إذا موجود)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('name_ar', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('city', 'LIKE', "%{$search}%")
                    ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        $branches = $query->latest()->paginate(15);

        return view('dashboard.pages.branches.index', compact('branches'));
    }

    /**
     * عرض صفحة إضافة فرع جديد
     */
    public function create()
    {
        return view('dashboard.pages.branches.create');
    }

    /**
     * حفظ فرع جديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'name_ar' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'is_main' => 'boolean',
        ]);

        // إذا كان فرع رئيسي، نلغي الفرع الرئيسي السابق
        if ($request->is_main) {
            Branch::where('is_main', true)->update(['is_main' => false]);
        }

        $branch = Branch::create($validated);

        return redirect()
            ->route('dashboard.branches.show', $branch)
            ->with('success', 'تم إضافة الفرع بنجاح');
    }

    /**
     * عرض تفاصيل فرع
     */
    public function show(Branch $branch)
    {
        $branch->load([
            'users',
            'stock.product',
        ]);

        // إحصائيات الفرع
        $stats = [
            'total_users' => $branch->users()->count(),
            'total_invoices' => $branch->invoices()->count(),
            'total_sales' => $branch->invoices()->sum('total'),
            'total_products' => $branch->stock()->count(),
            'total_stock_value' => $branch->stock()
                                         ->join('products', 'branch_stock.product_id', '=', 'products.product_id')
                                         ->sum(DB::raw('branch_stock.quantity * products.price')),
            'low_stock_count' => $branch->stock()
                                       ->whereColumn('quantity', '<=', 'min_quantity')
                                       ->count(),
        ];

        // المنتجات قليلة المخزون
        $lowStockProducts = $branch->stock()
                                  ->whereColumn('quantity', '<=', 'min_quantity')
                                  ->with('product')
                                  ->limit(5)
                                  ->get();

        // آخر الفواتير
        $recentInvoices = $branch->invoices()
                                ->with('customer')
                                ->latest()
                                ->limit(5)
                                ->get();

        return view('dashboard.pages.branches.show', compact('branch', 'stats', 'lowStockProducts', 'recentInvoices'));
    }

    /**
     * عرض صفحة تعديل فرع
     */
    public function edit(Branch $branch)
    {
        return view('dashboard.pages.branches.edit', compact('branch'));
    }

    /**
     * تحديث بيانات فرع
     */
    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'name_ar' => 'nullable|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'manager_name' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'opening_time' => 'nullable|date_format:H:i',
            'closing_time' => 'nullable|date_format:H:i',
            'is_active' => 'boolean',
            'is_main' => 'boolean',
        ]);

        // إذا كان فرع رئيسي، نلغي الفرع الرئيسي السابق
        if ($request->is_main && !$branch->is_main) {
            Branch::where('is_main', true)->update(['is_main' => false]);
        }

        $branch->update($validated);

        return redirect()
            ->route('dashboard.branches.show', $branch)
            ->with('success', 'تم تحديث بيانات الفرع بنجاح');
    }

    /**
     * حذف فرع
     */
    public function destroy(Branch $branch)
    {
        // التحقق من عدم وجود بيانات مرتبطة
        if ($branch->invoices()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'لا يمكن حذف الفرع لوجود فواتير مرتبطة به');
        }

        if ($branch->is_main) {
            return redirect()
                ->back()
                ->with('error', 'لا يمكن حذف الفرع الرئيسي');
        }

        $branch->delete();

        return redirect()
            ->route('dashboard.branches.index')
            ->with('success', 'تم حذف الفرع بنجاح');
    }

    /**
     * تفعيل/تعطيل فرع
     */
    public function toggleStatus(Branch $branch)
    {
        $branch->update([
            'is_active' => !$branch->is_active
        ]);

        $message = $branch->is_active ? 'تم تفعيل الفرع' : 'تم تعطيل الفرع';

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * عرض إحصائيات الفروع
     */

    /*public function statistics()
    {
        // مبيعات كل فرع - مع إصلاح GROUP BY
        $salesByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.city',
            'branches.is_main'
        )
            ->selectRaw('COALESCE(SUM(invoices.total), 0) as total_sales')
            ->selectRaw('COUNT(invoices.invoice_code) as invoices_count')
            ->leftJoin('invoices', 'branches.id', '=', 'invoices.branch_id')
            ->groupBy(
                'branches.id',
                'branches.name',
                'branches.name_ar',
                'branches.city',
                'branches.is_main'
            )
            ->orderBy('total_sales', 'desc')
            ->get();

        // المخزون حسب الفرع - مع إصلاح GROUP BY
        $stockByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.is_main'
        )
            ->selectRaw('COALESCE(SUM(branch_stock.quantity), 0) as total_quantity')
            ->selectRaw('COUNT(DISTINCT branch_stock.product_id) as products_count')
            ->leftJoin('branch_stock', 'branches.id', '=', 'branch_stock.branch_id')
            ->groupBy(
                'branches.id',
                'branches.name',
                'branches.name_ar',
                'branches.is_main'
            )
            ->get();

        return view('dashboard.pages.branches.statistics', compact('salesByBranch', 'stockByBranch'));
    }*/

    public function statistics()
    {
        // مبيعات كل فرع (فواتير Delivered فقط)
        $salesByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.city',
            'branches.is_main'
        )

            // 🧾 كل الفواتير (نشاط الفرع)
            ->selectRaw('COUNT(DISTINCT all_invoices.id) as invoices_count')

            // 💰 المبيعات الفعلية (Delivered فقط)
            ->selectRaw('COALESCE(SUM(delivered_invoices.total), 0) as total_sales')

            // Join لكل الفواتير
            ->leftJoin('invoices as all_invoices', 'branches.id', '=', 'all_invoices.branch_id')

            // Join لفواتير المبيعات فقط
            ->leftJoin('invoices as delivered_invoices', function ($join) {
                $join->on('branches.id', '=', 'delivered_invoices.branch_id')
                    ->where('delivered_invoices.status', 'delivered');
            })

            ->groupBy(
                'branches.id',
                'branches.name',
                'branches.name_ar',
                'branches.city',
                'branches.is_main'
            )
            ->orderByDesc('total_sales')
            ->get();



        // المخزون حسب الفرع (زي ما هو)
        $stockByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.is_main'
        )
            ->selectRaw('COALESCE(SUM(branch_stock.quantity), 0) as total_quantity')
            ->selectRaw('COUNT(DISTINCT branch_stock.product_id) as products_count')
            ->leftJoin('branch_stock', 'branches.id', '=', 'branch_stock.branch_id')
            ->groupBy(
                'branches.id',
                'branches.name',
                'branches.name_ar',
                'branches.is_main'
            )
            ->get();

        return view('dashboard.pages.branches.statistics', compact('salesByBranch', 'stockByBranch'));
    }

    public function statisticsPdf()
    {
        // نفس الاستعلامات زي الـ statistics
        $salesByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.city',
            'branches.is_main'
        )
            ->selectRaw('COUNT(DISTINCT all_invoices.id) as invoices_count')
            ->selectRaw('COALESCE(SUM(delivered_invoices.total), 0) as total_sales')
            ->leftJoin('invoices as all_invoices', 'branches.id', '=', 'all_invoices.branch_id')
            ->leftJoin('invoices as delivered_invoices', function ($join) {
                $join->on('branches.id', '=', 'delivered_invoices.branch_id')
                    ->where('delivered_invoices.status', 'delivered');
            })
            ->groupBy('branches.id','branches.name','branches.name_ar','branches.city','branches.is_main')
            ->orderByDesc('total_sales')
            ->get();

        $stockByBranch = Branch::select(
            'branches.id',
            'branches.name',
            'branches.name_ar',
            'branches.is_main'
        )
            ->selectRaw('COALESCE(SUM(branch_stock.quantity), 0) as total_quantity')
            ->selectRaw('COUNT(DISTINCT branch_stock.product_id) as products_count')
            ->leftJoin('branch_stock', 'branches.id', '=', 'branch_stock.branch_id')
            ->groupBy('branches.id','branches.name','branches.name_ar','branches.is_main')
            ->get();

        // عمل view للـ PDF
        $pdf = \Pdf::loadView('dashboard.pages.branches.statistics_pdf', compact('salesByBranch', 'stockByBranch'));

        return $pdf->download('branches-statistics.pdf');
    }

    /**
     * تقرير الفرع (PDF)
     */
    public function report(Branch $branch)
    {
        $branch->load(['users', 'stock.product', 'invoices']);

        $stats = [
            'total_sales' => $branch->invoices()->sum('total'),
            'total_invoices' => $branch->invoices()->count(),
            'total_stock_value' => $branch->stock()
                                         ->join('products', 'branch_stock.product_id', '=', 'products.product_id')
                                         ->sum(DB::raw('branch_stock.quantity * products.price')),
        ];

        // يمكن استخدام PDF library مثل DomPDF
        return view('dashboard.pages.branches.report', compact('branch', 'stats'));
    }
}
