<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Branch;
use App\BranchStock;
use App\Category;
use App\Customer;
use App\Expense;
use App\ExpenseCategory;
use App\Invoice;
use App\InvoiceItems;
use App\Product;
use App\glassLense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssistantController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-ai-assistant');
    }

    /** Show the assistant chat page */
    public function index()
    {
        return view('dashboard.pages.assistant.index');
    }

    /** Handle AJAX query from chat UI */
    public function query(Request $request)
    {
        $message = trim($request->input('message', ''));

        if (!$message) {
            return response()->json(['reply' => 'Please type a question first.', 'data' => null]);
        }

        $lower = mb_strtolower($message, 'UTF-8');

        try {
            $reply = $this->processMessage($lower, $message);
        } catch (\Exception $e) {
            $reply = [
                'text' => '⚠️ Sorry, something went wrong while fetching data.',
                'data' => null,
            ];
        }

        return response()->json($reply);
    }

    /* ═══════════════════════════════════════════════════════════
       QUERY ROUTER
    ═══════════════════════════════════════════════════════════ */

    private function processMessage($lower, $original)
    {
        // ─── Today's Sales ───
        if ($this->has($lower, ['مبيعات اليوم', 'مبيعات النهارده', 'مبيعاتنا النهارده', "today's sales", 'today sales', 'sales today'])) {
            return $this->todaySales();
        }

        // ─── This week sales ───
        if ($this->has($lower, ['مبيعات هذا الأسبوع', 'مبيعات الأسبوع', 'مبيعات الاسبوع ده', 'this week sales', 'sales this week', 'weekly sales'])) {
            return $this->weeklySales();
        }

        // ─── This month sales ───
        if ($this->has($lower, ['مبيعات هذا الشهر', 'مبيعات الشهر', 'this month sales', 'monthly sales', 'sales this month'])) {
            return $this->monthlySales();
        }

        // ─── Week comparison ───
        if ($this->has($lower, ['مقارنة الأسبوع', 'مقارنة الاسبوع', 'قارن بين الاسبوع', 'compare week', 'week comparison', 'اسبوع ده واللي فات', 'الأسبوع الحالي بالماضي'])) {
            return $this->compareWeeks();
        }

        // ─── Month comparison ───
        if ($this->has($lower, ['مقارنة الشهر', 'قارن بين الشهر', 'compare month', 'month comparison', 'الشهر ده واللي فات', 'الشهر الحالي بالماضي'])) {
            return $this->compareMonths();
        }

        // ─── Lenses sold today ───
        if ($this->has($lower, ['عدد العدسات', 'العدسات المباعة', 'كام عدسه', 'lenses sold', 'lenses today', 'عدسات اليوم', 'عدسات النهارده'])) {
            return $this->lensesToday();
        }

        // ─── Products sold today ───
        if ($this->has($lower, ['المنتجات المباعة', 'كام منتج', 'منتجات اليوم', 'products sold', 'products today', 'منتجات النهارده'])) {
            return $this->productsSoldToday();
        }

        // ─── Contact lenses ───
        if ($this->has($lower, ['العدسات اللاصقة', 'كونتاكت', 'contact lens', 'كونتاكت لينز', 'عدسات لاصقة', 'عدسات كونتاكت'])) {
            return $this->contactLensesStats();
        }

        // ─── By category (dynamic) ───
        if ($this->has($lower, ['المبيعات حسب القسم', 'حسب القسم', 'by category', 'اقسام', 'categories'])) {
            return $this->salesByCategory();
        }

        // ─── Branch comparison ───
        if ($this->has($lower, ['مقارنة الفروع', 'compare branches', 'branch comparison', 'branches sales'])) {
            return $this->branchComparison();
        }

        // ─── Pending invoices ───
        if ($this->has($lower, ['الفواتير المعلقة', 'فواتير معلقة', 'pending invoices', 'invoices pending', 'فواتير انتظار'])) {
            return $this->pendingInvoices();
        }

        // ─── Top selling products ───
        if ($this->has($lower, ['أكثر المنتجات مبيعاً', 'اكتر منتج', 'top products', 'best selling', 'اكثر مبيعا', 'أكثر مبيعاً'])) {
            return $this->topProducts();
        }

        // ─── Top customers ───
        if ($this->has($lower, ['أفضل العملاء', 'اكتر عميل', 'top customers', 'best customers', 'أفضل عملاء', 'اكثر عملاء'])) {
            return $this->topCustomers();
        }

        // ─── Customer lookup ───
        if ($this->has($lower, ['معلومات العميل', 'بيانات العميل', 'عميل رقم', 'ابحث عن عميل', 'find customer', 'customer info'])) {
            return $this->customerInfo($original);
        }

        // ─── Expenses today ───
        if ($this->has($lower, ['مصروفات اليوم', 'مصاريف اليوم', 'expenses today', 'مصروف اليوم'])) {
            return $this->expensesToday();
        }

        // ─── Expenses this week ───
        if ($this->has($lower, ['مصروفات الأسبوع', 'مصاريف الأسبوع', 'مصروفات هذا الأسبوع', 'expenses week'])) {
            return $this->expensesWeek();
        }

        // ─── Expenses this month ───
        if ($this->has($lower, ['مصروفات الشهر', 'مصاريف الشهر', 'مصروفات هذا الشهر', 'expenses month'])) {
            return $this->expensesMonth();
        }

        // ─── Expenses by category ───
        if ($this->has($lower, ['مصروفات حسب', 'مصاريف حسب', 'تصنيف المصروفات', 'expenses by category'])) {
            return $this->expensesByCategory();
        }

        // ─── General expenses ───
        if ($this->has($lower, ['مصروفات', 'مصاريف', 'expenses', 'مصروف'])) {
            return $this->expensesMonth();
        }

        // ─── Stock / Inventory ───
        if ($this->has($lower, ['مخزون', 'المخزون', 'مخزن', 'stock', 'inventory', 'كمية المنتج', 'رصيد المنتج'])) {
            return $this->stockInfo($original);
        }

        // ─── Help / What can you do ───
        if ($this->has($lower, ['help', 'مساعدة', 'ايه اللي تقدر', 'ايه تعرف', 'what can you', 'اسئلة'])) {
            return $this->helpMessage();
        }

        // ─── Greeting ───
        if ($this->has($lower, ['مرحبا', 'اهلا', 'هاي', 'hello', 'hi', 'سلام', 'صباح', 'مساء'])) {
            return ['text' => '👋 أهلاً! أنا المساعد الذكي. اسألني عن المبيعات، العدسات، المصروفات، المخزون، بيانات العملاء، أو الفروع. اكتب **مساعدة** لعرض جميع الأسئلة المتاحة.', 'data' => null];
        }

        // ─── Default ───
        return [
            'text' => "🤔 لم أفهم سؤالك تماماً. جرب مثلاً:\n• مبيعات اليوم\n• مصروفات هذا الشهر\n• مخزون [كود المنتج]\n• معلومات العميل رقم [ID]\n• مقارنة الفروع\n\nاكتب **مساعدة** لرؤية جميع الأسئلة المتاحة.",
            'data' => null,
        ];
    }

    /* ═══════════════════════════════════════════════════════════
       QUERY METHODS
    ═══════════════════════════════════════════════════════════ */

    private function todaySales()
    {
        $today = now()->toDateString();

        $invoices = Invoice::whereDate('created_at', $today)
            ->where('status', '!=', 'canceled')
            ->select('id', 'total', 'paied', 'remaining', 'branch_id', 'status')
            ->get();

        $total    = $invoices->sum('total');
        $collected = $invoices->sum('paied');
        $remaining = $invoices->sum('remaining');
        $count    = $invoices->count();

        $text  = "📊 **مبيعات اليوم** ({$today})\n\n";
        $text .= "• عدد الفواتير: **{$count}**\n";
        $text .= "• إجمالي المبيعات: **" . number_format($total, 2) . "**\n";
        $text .= "• المحصّل: **" . number_format($collected, 2) . "**\n";
        $text .= "• المتبقي: **" . number_format($remaining, 2) . "**";

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'Invoices', 'value' => $count, 'color' => '#6366f1'],
                ['label' => 'Total Sales', 'value' => number_format($total, 2), 'color' => '#10b981'],
                ['label' => 'Collected', 'value' => number_format($collected, 2), 'color' => '#3b82f6'],
                ['label' => 'Remaining', 'value' => number_format($remaining, 2), 'color' => '#f59e0b'],
            ],
        ]];
    }

    private function weeklySales()
    {
        $start = now()->startOfWeek()->toDateString();
        $end   = now()->toDateString();

        $invoices = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('status', '!=', 'canceled')
            ->get();

        $total = $invoices->sum('total');
        $count = $invoices->count();

        // Daily breakdown
        $daily = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('status', '!=', 'canceled')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total) as tot'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $text  = "📅 **مبيعات هذا الأسبوع** ({$start} → {$end})\n\n";
        $text .= "• إجمالي الفواتير: **{$count}**\n";
        $text .= "• إجمالي المبيعات: **" . number_format($total, 2) . "**\n\n";
        $text .= "**تفصيل يومي:**\n";
        foreach ($daily as $d) {
            $text .= "  {$d->day}: " . number_format($d->tot, 2) . " ({$d->cnt} فاتورة)\n";
        }

        $chartData = $daily->map(fn($d) => ['label' => $d->day, 'value' => (float)$d->tot])->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    private function monthlySales()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $invoices = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('status', '!=', 'canceled')
            ->get();

        $total     = $invoices->sum('total');
        $collected = $invoices->sum('paied');
        $count     = $invoices->count();

        $daily = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('status', '!=', 'canceled')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total) as tot'))
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $avgDaily = $daily->count() > 0 ? $total / $daily->count() : 0;

        $text  = "📆 **مبيعات هذا الشهر** ({$start} → {$end})\n\n";
        $text .= "• إجمالي الفواتير: **{$count}**\n";
        $text .= "• إجمالي المبيعات: **" . number_format($total, 2) . "**\n";
        $text .= "• المحصّل: **" . number_format($collected, 2) . "**\n";
        $text .= "• متوسط يومي: **" . number_format($avgDaily, 2) . "**";

        $chartData = $daily->map(fn($d) => ['label' => substr($d->day, 8, 2), 'value' => (float)$d->tot])->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    private function compareWeeks()
    {
        $thisStart  = now()->startOfWeek()->toDateString();
        $thisEnd    = now()->toDateString();
        $lastStart  = now()->subWeek()->startOfWeek()->toDateString();
        $lastEnd    = now()->subWeek()->endOfWeek()->toDateString();

        $thisWeek = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$thisStart, $thisEnd])
            ->where('status', '!=', 'canceled')->sum('total');
        $lastWeek = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$lastStart, $lastEnd])
            ->where('status', '!=', 'canceled')->sum('total');

        $diff    = $thisWeek - $lastWeek;
        $pct     = $lastWeek > 0 ? round(($diff / $lastWeek) * 100, 1) : 0;
        $arrow   = $diff >= 0 ? '📈' : '📉';
        $diffStr = ($diff >= 0 ? '+' : '') . number_format($diff, 2);

        $text  = "🔄 **مقارنة الأسابيع**\n\n";
        $text .= "• هذا الأسبوع: **" . number_format($thisWeek, 2) . "**\n";
        $text .= "• الأسبوع الماضي: **" . number_format($lastWeek, 2) . "**\n";
        $text .= "• الفرق: {$arrow} **{$diffStr}** ({$pct}%)";

        return ['text' => $text, 'data' => [
            'type'  => 'compare',
            'items' => [
                ['label' => 'This Week', 'value' => (float)$thisWeek, 'color' => '#6366f1'],
                ['label' => 'Last Week', 'value' => (float)$lastWeek, 'color' => '#94a3b8'],
            ],
        ]];
    }

    private function compareMonths()
    {
        $thisStart = now()->startOfMonth()->toDateString();
        $thisEnd   = now()->toDateString();
        $lastStart = now()->subMonth()->startOfMonth()->toDateString();
        $lastEnd   = now()->subMonth()->endOfMonth()->toDateString();

        $thisMonth = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$thisStart, $thisEnd])
            ->where('status', '!=', 'canceled')->sum('total');
        $lastMonth = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$lastStart, $lastEnd])
            ->where('status', '!=', 'canceled')->sum('total');

        $diff  = $thisMonth - $lastMonth;
        $pct   = $lastMonth > 0 ? round(($diff / $lastMonth) * 100, 1) : 0;
        $arrow = $diff >= 0 ? '📈' : '📉';
        $diffStr = ($diff >= 0 ? '+' : '') . number_format($diff, 2);

        $text  = "📊 **مقارنة الأشهر**\n\n";
        $text .= "• هذا الشهر: **" . number_format($thisMonth, 2) . "**\n";
        $text .= "• الشهر الماضي: **" . number_format($lastMonth, 2) . "**\n";
        $text .= "• الفرق: {$arrow} **{$diffStr}** ({$pct}%)";

        return ['text' => $text, 'data' => [
            'type'  => 'compare',
            'items' => [
                ['label' => 'This Month', 'value' => (float)$thisMonth, 'color' => '#10b981'],
                ['label' => 'Last Month', 'value' => (float)$lastMonth, 'color' => '#94a3b8'],
            ],
        ]];
    }

    private function lensesToday()
    {
        $today = now()->toDateString();

        $lensItems = InvoiceItems::where('type', 'lens')
            ->whereHas('invoice', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', '!=', 'canceled');
            })
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->get();

        $totalQty  = $lensItems->sum('qty');
        $totalInvoices = $lensItems->sum('cnt');

        $text  = "👁️ **عدسات مباعة اليوم** ({$today})\n\n";
        $text .= "• إجمالي العدسات: **{$totalQty}** قطعة\n";
        $text .= "• من خلال: **{$totalInvoices}** بند فاتورة\n";

        if ($lensItems->isNotEmpty()) {
            $text .= "\n**أكثر العدسات مبيعاً:**\n";
            foreach ($lensItems->take(5) as $item) {
                $lens = \App\glassLense::where('product_id', $item->product_id)->first();
                $name = $lens ? ($lens->description ?? $lens->product_id) : $item->product_id;
                $text .= "  • {$name}: **{$item->qty}** قطعة\n";
            }
        }

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'Total Lenses', 'value' => $totalQty, 'color' => '#6366f1'],
                ['label' => 'Invoice Lines', 'value' => $totalInvoices, 'color' => '#8b5cf6'],
            ],
        ]];
    }

    private function productsSoldToday()
    {
        $today = now()->toDateString();

        $prodItems = InvoiceItems::where('type', 'product')
            ->whereHas('invoice', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', '!=', 'canceled');
            })
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->get();

        $totalQty = $prodItems->sum('qty');

        $text  = "📦 **منتجات مباعة اليوم** ({$today})\n\n";
        $text .= "• إجمالي المنتجات: **{$totalQty}** قطعة\n";

        if ($prodItems->isNotEmpty()) {
            $text .= "\n**أكثر المنتجات مبيعاً:**\n";
            foreach ($prodItems->take(5) as $item) {
                $prod = \App\Product::where('product_id', $item->product_id)->first();
                $name = $prod ? ($prod->description ?? $prod->product_id) : $item->product_id;
                $text .= "  • {$name}: **{$item->qty}** قطعة\n";
            }
        }

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'Products Sold', 'value' => $totalQty, 'color' => '#10b981'],
                ['label' => 'SKUs Sold', 'value' => $prodItems->count(), 'color' => '#3b82f6'],
            ],
        ]];
    }

    private function contactLensesStats()
    {
        $today = now()->toDateString();

        // Try to find contact lens category
        $category = Category::whereIn(DB::raw('LOWER(name)'), ['contact lenses', 'contact lens', 'كونتاكت', 'عدسات لاصقة'])->first();

        $text = "🔵 **إحصائيات الكونتاكت لينز**\n\n";

        if ($category) {
            $productIds = \App\Product::where('category_id', $category->id)->pluck('product_id');

            $todayQty = InvoiceItems::whereIn('product_id', $productIds)
                ->whereHas('invoice', function ($q) use ($today) {
                    $q->whereDate('created_at', $today)->where('status', '!=', 'canceled');
                })->sum('quantity');

            $monthQty = InvoiceItems::whereIn('product_id', $productIds)
                ->whereHas('invoice', function ($q) {
                    $q->whereBetween(DB::raw('DATE(created_at)'), [now()->startOfMonth()->toDateString(), now()->toDateString()])
                      ->where('status', '!=', 'canceled');
                })->sum('quantity');

            $text .= "• مبيعات اليوم: **{$todayQty}** قطعة\n";
            $text .= "• مبيعات الشهر: **{$monthQty}** قطعة\n";
            $text .= "• القسم: **{$category->name}**";

            return ['text' => $text, 'data' => [
                'type'  => 'stats',
                'items' => [
                    ['label' => "Today's Contact Lenses", 'value' => $todayQty, 'color' => '#6366f1'],
                    ['label' => "This Month", 'value' => $monthQty, 'color' => '#10b981'],
                ],
            ]];
        }

        // Fallback: search by lens type or name
        $lensQty = InvoiceItems::where('type', 'lens')
            ->whereHas('invoice', function ($q) use ($today) {
                $q->whereDate('created_at', $today)->where('status', '!=', 'canceled');
            })->sum('quantity');

        $text .= "لم أجد قسم الكونتاكت لينز، إليك إجمالي العدسات اليوم:\n";
        $text .= "• إجمالي العدسات اليوم: **{$lensQty}** قطعة";

        return ['text' => $text, 'data' => null];
    }

    private function salesByCategory()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $data = InvoiceItems::where('type', 'product')
            ->whereHas('invoice', function ($q) use ($start, $end) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                  ->where('status', '!=', 'canceled');
            })
            ->join('products', 'invoice_items.product_id', '=', 'products.product_id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('categories.name as cat', DB::raw('SUM(invoice_items.quantity) as qty'), DB::raw('SUM(invoice_items.total) as total'))
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total')
            ->get();

        $text = "📋 **مبيعات حسب القسم** (هذا الشهر)\n\n";

        if ($data->isEmpty()) {
            $text .= "لا توجد بيانات بعد.";
        } else {
            foreach ($data as $row) {
                $text .= "• {$row->cat}: **" . number_format($row->total, 2) . "** ({$row->qty} قطعة)\n";
            }
        }

        $chartData = $data->map(fn($r) => ['label' => $r->cat, 'value' => (float)$r->total])->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    private function branchComparison()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $branches = Branch::where('is_active', true)->get();
        $rows = [];

        foreach ($branches as $branch) {
            $total = Invoice::where('branch_id', $branch->id)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->where('status', '!=', 'canceled')
                ->sum('total');

            $count = Invoice::where('branch_id', $branch->id)
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                ->where('status', '!=', 'canceled')
                ->count();

            $rows[] = ['branch' => $branch->name, 'total' => (float)$total, 'count' => $count];
        }

        usort($rows, fn($a, $b) => $b['total'] <=> $a['total']);

        $text = "🏢 **مقارنة الفروع** (هذا الشهر)\n\n";
        foreach ($rows as $row) {
            $text .= "• {$row['branch']}: **" . number_format($row['total'], 2) . "** ({$row['count']} فاتورة)\n";
        }

        $chartData = array_map(fn($r) => ['label' => $r['branch'], 'value' => $r['total']], $rows);

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    private function pendingInvoices()
    {
        $pending = Invoice::where('status', 'pending')
            ->select('id', 'invoice_code', 'total', 'remaining', 'created_at', 'branch_id')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $total    = Invoice::where('status', 'pending')->count();
        $totalAmt = Invoice::where('status', 'pending')->sum('remaining');

        $text  = "⏳ **الفواتير المعلقة**\n\n";
        $text .= "• عدد الفواتير المعلقة: **{$total}**\n";
        $text .= "• إجمالي المبالغ المستحقة: **" . number_format($totalAmt, 2) . "**";

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'Pending Invoices', 'value' => $total, 'color' => '#f59e0b'],
                ['label' => 'Amount Due', 'value' => number_format($totalAmt, 2), 'color' => '#ef4444'],
            ],
        ]];
    }

    private function topProducts()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $items = InvoiceItems::where('type', 'product')
            ->whereHas('invoice', function ($q) use ($start, $end) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
                  ->where('status', '!=', 'canceled');
            })
            ->select('product_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(total) as tot'))
            ->groupBy('product_id')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();

        $text = "🏆 **أكثر المنتجات مبيعاً** (هذا الشهر)\n\n";

        if ($items->isEmpty()) {
            $text .= "لا توجد بيانات بعد.";
        } else {
            $i = 1;
            foreach ($items as $item) {
                $prod = \App\Product::where('product_id', $item->product_id)->first();
                $name = $prod ? ($prod->description ?? $item->product_id) : $item->product_id;
                $text .= "{$i}. {$name}: **{$item->qty}** قطعة (" . number_format($item->tot, 2) . ")\n";
                $i++;
            }
        }

        $chartData = $items->map(function ($item) {
            $prod = \App\Product::where('product_id', $item->product_id)->first();
            return ['label' => $prod ? ($prod->description ?? $item->product_id) : $item->product_id, 'value' => (float)$item->qty];
        })->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    private function topCustomers()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $data = Invoice::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->where('status', '!=', 'canceled')
            ->select('customer_id', DB::raw('COUNT(*) as cnt'), DB::raw('SUM(total) as tot'))
            ->groupBy('customer_id')
            ->orderByDesc('tot')
            ->limit(10)
            ->with('customer')
            ->get();

        $text = "⭐ **أفضل العملاء** (هذا الشهر)\n\n";

        if ($data->isEmpty()) {
            $text .= "لا توجد بيانات بعد.";
        } else {
            $i = 1;
            foreach ($data as $row) {
                $name = $row->customer ? ($row->customer->local_name ?? $row->customer->english_name ?? "عميل #{$row->customer_id}") : "عميل #{$row->customer_id}";
                $text .= "{$i}. {$name}: **" . number_format($row->tot, 2) . "** ({$row->cnt} فاتورة)\n";
                $i++;
            }
        }

        return ['text' => $text, 'data' => null];
    }

    private function helpMessage()
    {
        $text  = "🤖 **ما أستطيع مساعدتك فيه:**\n\n";
        $text .= "**📊 المبيعات:**\n";
        $text .= "• مبيعات اليوم\n";
        $text .= "• مبيعات هذا الأسبوع\n";
        $text .= "• مبيعات هذا الشهر\n";
        $text .= "• مقارنة الأسبوع الحالي بالماضي\n";
        $text .= "• مقارنة الشهر الحالي بالماضي\n\n";
        $text .= "**👁️ العدسات:**\n";
        $text .= "• عدد العدسات المباعة اليوم\n";
        $text .= "• إحصائيات العدسات اللاصقة\n\n";
        $text .= "**📦 المنتجات:**\n";
        $text .= "• المنتجات المباعة اليوم\n";
        $text .= "• أكثر المنتجات مبيعاً\n";
        $text .= "• المبيعات حسب القسم\n\n";
        $text .= "**🏢 الفروع:**\n";
        $text .= "• مقارنة الفروع\n\n";
        $text .= "**💰 الفواتير والعملاء:**\n";
        $text .= "• الفواتير المعلقة\n";
        $text .= "• أفضل العملاء\n";
        $text .= "• معلومات العميل رقم [ID]\n\n";
        $text .= "**💸 المصروفات:**\n";
        $text .= "• مصروفات اليوم\n";
        $text .= "• مصروفات الأسبوع\n";
        $text .= "• مصروفات الشهر\n";
        $text .= "• مصروفات حسب الفئة\n\n";
        $text .= "**📦 المخزون:**\n";
        $text .= "• مخزون [اسم أو كود المنتج]\n";
        $text .= "• مخزون [المنتج] في [اسم الفرع]";

        return ['text' => $text, 'data' => null];
    }

    /* ─── Customer Info ─── */
    private function customerInfo($original)
    {
        $customer = null;

        // Try numeric customer_id
        if (preg_match('/\b(\d{3,})\b/', $original, $m)) {
            $customer = Customer::where('customer_id', $m[1])->first();
        }

        // Try name after keywords
        if (!$customer) {
            if (preg_match('/(?:باسم|اسمه|اسمها|named?|name:?)\s+(.{2,})/ui', $original, $m)) {
                $name = trim($m[1]);
                $customer = Customer::where('english_name', 'LIKE', "%{$name}%")
                    ->orWhere('local_name', 'LIKE', "%{$name}%")
                    ->first();
            }
        }

        if (!$customer) {
            return [
                'text' => "🔍 لم أجد العميل. يمكنك البحث بـ:\n• **معلومات العميل رقم 12345**\n• **معلومات العميل باسم Ahmed Ali**",
                'data' => null,
            ];
        }

        $name     = $customer->local_name ?? $customer->english_name ?? "عميل #{$customer->customer_id}";
        $invoices = Invoice::where('customer_id', $customer->customer_id)->where('status', '!=', 'canceled');
        $count    = $invoices->count();
        $total    = $invoices->sum('total');
        $paid     = $invoices->sum('paied');
        $remaining = $invoices->sum('remaining');

        $lastInvoice = Invoice::where('customer_id', $customer->customer_id)
            ->where('status', '!=', 'canceled')
            ->orderByDesc('created_at')->first();

        $text  = "👤 **بيانات العميل**\n\n";
        $text .= "• الاسم: **{$name}**\n";
        if ($customer->english_name && $customer->local_name) {
            $text .= "• الاسم الإنجليزي: **{$customer->english_name}**\n";
        }
        $text .= "• رقم العميل: **{$customer->customer_id}**\n";
        if ($customer->mobile_number) $text .= "• الهاتف: **{$customer->mobile_number}**\n";
        if ($customer->email)         $text .= "• البريد: **{$customer->email}**\n";
        $text .= "\n**📊 الإحصائيات:**\n";
        $text .= "• إجمالي الفواتير: **{$count}**\n";
        $text .= "• إجمالي المشتريات: **" . number_format($total, 2) . "**\n";
        $text .= "• المدفوع: **" . number_format($paid, 2) . "**\n";
        $text .= "• المتبقي: **" . number_format($remaining, 2) . "**\n";
        if ($lastInvoice) {
            $text .= "• آخر زيارة: **" . $lastInvoice->created_at->format('d M Y') . "**\n";
        }
        if ($customer->total_points) $text .= "• النقاط: **{$customer->total_points}**";

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'إجمالي الفواتير', 'value' => $count,                        'color' => '#6366f1'],
                ['label' => 'المشتريات',        'value' => number_format($total, 2),      'color' => '#10b981'],
                ['label' => 'المدفوع',          'value' => number_format($paid, 2),       'color' => '#3b82f6'],
                ['label' => 'المتبقي',          'value' => number_format($remaining, 2),  'color' => '#f59e0b'],
            ],
        ]];
    }

    /* ─── Expenses Today ─── */
    private function expensesToday()
    {
        $today    = now()->toDateString();
        $expenses = Expense::whereDate('expense_date', $today)->get();
        $total    = $expenses->sum('amount');
        $count    = $expenses->count();

        $text  = "💸 **مصروفات اليوم** ({$today})\n\n";
        $text .= "• عدد بنود المصروفات: **{$count}**\n";
        $text .= "• إجمالي المصروفات: **" . number_format($total, 2) . "**\n";

        if ($expenses->isNotEmpty()) {
            $text .= "\n**أعلى المصروفات:**\n";
            foreach ($expenses->sortByDesc('amount')->take(5) as $e) {
                $cat  = $e->category->name ?? 'غير محدد';
                $text .= "  • {$cat}: **" . number_format($e->amount, 2) . "**\n";
            }
        }

        return ['text' => $text, 'data' => [
            'type'  => 'stats',
            'items' => [
                ['label' => 'عدد البنود',     'value' => $count,                   'color' => '#f59e0b'],
                ['label' => 'إجمالي المصروفات', 'value' => number_format($total, 2), 'color' => '#ef4444'],
            ],
        ]];
    }

    /* ─── Expenses This Week ─── */
    private function expensesWeek()
    {
        $start    = now()->startOfWeek()->toDateString();
        $end      = now()->toDateString();
        $expenses = Expense::whereBetween('expense_date', [$start, $end])->get();
        $total    = $expenses->sum('amount');
        $count    = $expenses->count();

        $text  = "💸 **مصروفات هذا الأسبوع** ({$start} → {$end})\n\n";
        $text .= "• عدد بنود المصروفات: **{$count}**\n";
        $text .= "• إجمالي المصروفات: **" . number_format($total, 2) . "**\n";

        // Daily breakdown
        $daily = Expense::whereBetween('expense_date', [$start, $end])
            ->select(DB::raw('DATE(expense_date) as day'), DB::raw('SUM(amount) as tot'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('day')->orderBy('day')->get();

        if ($daily->isNotEmpty()) {
            $text .= "\n**تفصيل يومي:**\n";
            foreach ($daily as $d) {
                $text .= "  {$d->day}: " . number_format($d->tot, 2) . " ({$d->cnt} بند)\n";
            }
        }

        $chartData = $daily->map(fn($d) => ['label' => $d->day, 'value' => (float)$d->tot])->values()->toArray();
        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    /* ─── Expenses This Month ─── */
    private function expensesMonth()
    {
        $start    = now()->startOfMonth()->toDateString();
        $end      = now()->toDateString();
        $expenses = Expense::whereBetween('expense_date', [$start, $end])->get();
        $total    = $expenses->sum('amount');
        $count    = $expenses->count();

        $text  = "💸 **مصروفات هذا الشهر** ({$start} → {$end})\n\n";
        $text .= "• عدد بنود المصروفات: **{$count}**\n";
        $text .= "• إجمالي المصروفات: **" . number_format($total, 2) . "**\n";

        // By category
        $byCat = Expense::whereBetween('expense_date', [$start, $end])
            ->select('category_id', DB::raw('SUM(amount) as tot'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('category_id')->orderByDesc('tot')->with('category')->get();

        if ($byCat->isNotEmpty()) {
            $text .= "\n**حسب الفئة:**\n";
            foreach ($byCat->take(6) as $row) {
                $cat  = $row->category->name ?? 'غير محدد';
                $text .= "  • {$cat}: **" . number_format($row->tot, 2) . "** ({$row->cnt} بند)\n";
            }
        }

        $chartData = $byCat->map(fn($r) => [
            'label' => $r->category->name ?? 'غير محدد',
            'value' => (float)$r->tot,
        ])->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    /* ─── Expenses By Category ─── */
    private function expensesByCategory()
    {
        $start = now()->startOfMonth()->toDateString();
        $end   = now()->toDateString();

        $data = Expense::whereBetween('expense_date', [$start, $end])
            ->select('category_id', DB::raw('SUM(amount) as tot'), DB::raw('COUNT(*) as cnt'))
            ->groupBy('category_id')->orderByDesc('tot')->with('category')->get();

        $text = "📋 **المصروفات حسب الفئة** (هذا الشهر)\n\n";

        if ($data->isEmpty()) {
            $text .= "لا توجد مصروفات مسجلة هذا الشهر.";
        } else {
            foreach ($data as $row) {
                $cat  = $row->category->name ?? 'غير محدد';
                $text .= "• {$cat}: **" . number_format($row->tot, 2) . "** ({$row->cnt} بند)\n";
            }
        }

        $chartData = $data->map(fn($r) => [
            'label' => $r->category->name ?? 'غير محدد',
            'value' => (float)$r->tot,
        ])->values()->toArray();

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $chartData]];
    }

    /* ─── Stock Info ─── */
    private function stockInfo($original)
    {
        // Detect branch name
        $branches    = Branch::where('is_active', true)->get();
        $targetBranch = null;
        foreach ($branches as $branch) {
            if (mb_stripos($original, $branch->name, 0, 'UTF-8') !== false) {
                $targetBranch = $branch;
                break;
            }
        }

        // Detect product code / keyword (letters+digits, at least 3 chars, not Arabic)
        $productKeyword = null;
        if (preg_match('/\b([A-Za-z0-9\-]{3,})\b/', $original, $m)) {
            $productKeyword = $m[1];
        }
        // Detect Arabic product keyword (anything after مخزون/مخزن/كمية/رصيد)
        if (!$productKeyword && preg_match('/(?:مخزون|مخزن|كمية المنتج|رصيد المنتج)\s+([^\s].{1,})/u', $original, $m)) {
            $productKeyword = trim($m[1]);
        }

        $query = BranchStock::with(['branch', 'stockable']);

        if ($targetBranch) {
            $query->where('branch_id', $targetBranch->id);
        }

        if ($productKeyword) {
            $kw = $productKeyword;
            $query->where(function ($q) use ($kw) {
                $q->where('product_id', 'LIKE', "%{$kw}%")
                  ->orWhereHasMorph('stockable', [Product::class], function ($sq) use ($kw) {
                      $sq->where('product_id', 'LIKE', "%{$kw}%")
                         ->orWhere('description', 'LIKE', "%{$kw}%");
                  })
                  ->orWhereHasMorph('stockable', [glassLense::class], function ($sq) use ($kw) {
                      $sq->where('product_id', 'LIKE', "%{$kw}%")
                         ->orWhere('description', 'LIKE', "%{$kw}%");
                  });
            });
        }

        $stocks = $query->orderByDesc('quantity')->limit(12)->get();

        if ($stocks->isEmpty()) {
            $msg  = $targetBranch  ? "فرع **{$targetBranch->name}**" : "جميع الفروع";
            $prod = $productKeyword ? " للمنتج **{$productKeyword}**" : "";
            return [
                'text' => "📦 لا توجد بيانات مخزون{$prod} في {$msg}.\n\nجرب: **مخزون [كود المنتج] في [اسم الفرع]**",
                'data' => null,
            ];
        }

        $branchTitle  = $targetBranch  ? " — فرع {$targetBranch->name}" : "";
        $prodTitle    = $productKeyword ? " [{$productKeyword}]" : "";
        $text         = "📦 **المخزون{$prodTitle}{$branchTitle}**\n\n";

        $totalQty = $stocks->sum('quantity');
        $text .= "• إجمالي الكميات: **{$totalQty}** وحدة\n";
        $text .= "• عدد السجلات: **{$stocks->count()}**\n\n";

        $statItems = [];
        foreach ($stocks->take(8) as $s) {
            $itemName   = $s->description ?? $s->item_code ?? 'غير محدد';
            $branchName = $s->branch->name ?? '—';
            $status     = $s->stock_status;
            $st         = $status['status'] ?? '';
            if ($st === 'out_of_stock')     $statusIcon = '🔴';
            elseif ($st === 'low_stock')    $statusIcon = '🟡';
            elseif ($st === 'over_stock')   $statusIcon = '🔵';
            else                            $statusIcon = '🟢';
            $text .= "{$statusIcon} **{$itemName}** ({$branchName}): **{$s->quantity}** وحدة\n";
            $statItems[] = ['label' => mb_substr($itemName, 0, 20, 'UTF-8'), 'value' => (int)$s->quantity, 'color' => '#6366f1'];
        }

        return ['text' => $text, 'data' => ['type' => 'chart', 'chart' => $statItems]];
    }

    /* ═══════════════════════════════════════════════════════════
       HELPERS
    ═══════════════════════════════════════════════════════════ */

    /** Check if any of the keywords exist in the message */
    private function has($lower, array $keywords)
    {
        foreach ($keywords as $kw) {
            if (mb_strpos($lower, mb_strtolower($kw, 'UTF-8'), 0, 'UTF-8') !== false) {
                return true;
            }
        }
        return false;
    }
}
