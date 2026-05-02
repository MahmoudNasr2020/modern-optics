<?php

namespace App\Http\Controllers\Dashboard;

use App\Branch;
use App\CashierTransaction;
use App\Customer;
use App\DailyClosing;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\InvoiceItems;
use App\Payments;
use Illuminate\Http\Request;
use Carbon\Carbon;


class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission.spatie:view-reports')->only([
            'itemsToBeDelivired',
            'reportItemsToBeDelivired',
            'getPendingInvoices',
            'reportgetPendingInvoices',
            'getReturnInvoices',
            'reportgetReturnInvoices',
            'getCustomers',
            'reportgetCustomers',
            'getSalesTransactions',
            'reportSalesTransactions',
            'getCashierTransactions',
            'reportCashierTransactions',
            'getSalesSummary',
            'reportSalesSummary',
            'getSalesSummarySalesman',
            'reportSalesSummarySalesman',
            'reportReturningInvoice',
            'printReturnInvoiceEn',
            'printReturnInvoiceAr',
            'salesmanSummaryIndex',
            'reportSalesSummaryPerSalesman',
            'getSalesSummaryDoctor',
            'reportSalesSummaryDoctor',
            'calc',
            'dailyCloseCalc',
            'itemsDelivered',
            'reportItemsDelivired',
            'salesmanSalary',
            'reportSalesmanSalary'
        ]);

        $this->middleware('permission.spatie:export-reports')->only([
            'reportItemsToBeDelivired',
            'reportgetPendingInvoices',
            'reportgetReturnInvoices',
            'reportgetCustomers',
            'reportSalesTransactions',
            'reportCashierTransactions',
            'reportSalesSummary',
            'reportSalesSummarySalesman',
            'reportSalesSummaryPerSalesman',
            'reportSalesSummaryDoctor',
            'calc',
            'dailyCloseCalc',
            'reportItemsDelivired',
            'reportSalesmanSalary'
        ]);

        $this->middleware('permission.spatie:view-items-to-arrive-report')->only([
            'itemsToBeDelivired',
            'reportItemsToBeDelivired'
        ]);

        $this->middleware('permission.spatie:view-items-delivered-report')->only([
            'itemsDelivered',
            'reportItemsDelivired'
        ]);

        $this->middleware('permission.spatie:view-pending-invoices-report')->only([
            'getPendingInvoices',
            'reportgetPendingInvoices'
        ]);

        $this->middleware('permission.spatie:view-returned-invoices-report')->only([
            'getReturnInvoices',
            'reportgetReturnInvoices',
            'reportReturningInvoice',
            'printReturnInvoiceEn',
            'printReturnInvoiceAr'
        ]);

        $this->middleware('permission.spatie:view-customers-report')->only([
            'getCustomers',
            'reportgetCustomers'
        ]);

        $this->middleware('permission.spatie:view-sales-transactions-report')->only([
            'getSalesTransactions',
            'reportSalesTransactions'
        ]);

        $this->middleware('permission.spatie:view-cashier-report')->only([
            'getCashierTransactions',
            'reportCashierTransactions',
            'calc',
            'dailyCloseCalc'
        ]);

        $this->middleware('permission.spatie:view-sales-summary-report')->only([
            'getSalesSummary',
            'reportSalesSummary'
        ]);

        $this->middleware('permission.spatie:view-sales-by-seller-report')->only([
            'getSalesSummarySalesman',
            'reportSalesSummarySalesman'
        ]);

        $this->middleware('permission.spatie:view-seller-sales-summary-report')->only([
            'salesmanSummaryIndex',
            'reportSalesSummaryPerSalesman'
        ]);

        $this->middleware('permission.spatie:view-employee-salaries-report')->only([
            'salesmanSalary',
            'reportSalesmanSalary'
        ]);

        $this->middleware('permission.spatie:view-sales-by-doctor-report')->only([
            'getSalesSummaryDoctor',
            'reportSalesSummaryDoctor'
        ]);
    }

    public function itemsToBeDelivired(Request $request){
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.items_delivired', compact('branches'));
    }

    public function reportItemsToBeDelivired(Request $request){
        $user = auth()->user();

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branchId = $request->branch_id;

        if(isset($start_date) && isset($end_date)){

            $start = Carbon::parse($start_date)->startOfDay();
            $end   = Carbon::parse($end_date)->endOfDay();

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            /*$query = Invoice::leftJoin('invoice_items','invoice_items.invoice_id','invoices.id')
                ->leftJoin('products','products.product_id','invoice_items.product_id')
                ->leftJoin('categories','categories.id','products.category_id')
                ->leftJoin('glass_lenses','glass_lenses.product_id','invoice_items.product_id')
                ->select(
                    'invoices.*',
                    'invoice_items.type',
                    'invoice_items.product_id',
                    'invoice_items.quantity',
                    'products.description as product_description',
                    'glass_lenses.description as lenses_description',
                    'categories.category_name'
                )
                ->whereBetween('invoice_items.created_at', [$start, $end])
                ->whereNotIn('invoices.status', ['delivered', 'returned', 'canceled'])
                ->whereIn('invoice_items.status', ['under_process', 'ready']);*/

            $query = InvoiceItems::with(['invoice', 'product.category', 'lens'])
                ->whereBetween('created_at', [$start, $end])
                ->whereIn('status', ['under_process', 'ready'])
                ->whereHas('invoice', function ($q) use ($filterBranchId) {
                    $q->whereNotIn('status', ['delivered', 'returned', 'canceled']);

                    if ($filterBranchId) {
                        $q->where('branch_id', $filterBranchId);
                    }
                })
                ->orderBy('created_at', 'asc');

            // ✅ Apply branch filter
            /*if ($filterBranchId) {
                $query->where('invoices.branch_id', $filterBranchId);
            }*/
            // else: Super Admin with "All Branches" - no branch filter

            //$invoices = $query->orderBy('invoices.created_at','asc')->get();
            $invoices = $query->get();


            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;
        }
        else{
            $invoices = collect();
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_items_delivired_report', compact(
            'invoices',
            'start_date',
            'end_date',
            'selectedBranch'
        ));
    }


    public function itemsDelivered()
    {
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.items_delivired_action', compact('branches'));
    }

    /*public function reportItemsDelivired(Request $request){
        $user = auth()->user();

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branchId = $request->branch_id;

        if(isset($start_date) && isset($end_date)){

            $start = Carbon::parse($start_date)->startOfDay(); // 00:00:00
            $end   = Carbon::parse($end_date)->endOfDay();     // 23:59:59

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = Invoice::leftJoin('invoice_items','invoice_items.invoice_id','invoices.id')
                ->leftJoin('products','products.product_id','invoice_items.product_id')
                ->leftJoin('categories','categories.id','products.category_id')
                ->leftJoin('glass_lenses','glass_lenses.product_id','invoice_items.product_id')
                ->select(
                    'invoices.*',
                    'invoice_items.type',
                    'invoice_items.product_id',
                    'invoice_items.quantity',
                    'products.description as product_description',
                    'glass_lenses.description as lenses_description',
                    'categories.category_name'
                )
                ->whereBetween('invoice_items.created_at', [$start, $end])
                ->whereIn('invoice_items.status', ['delivery']);

            // ✅ Apply branch filter
            if ($filterBranchId) {
                $query->where('invoices.branch_id', $filterBranchId);
            }
            // else: Super Admin with "All Branches" - no branch filter

            $invoices = $query->orderBy('invoices.created_at','asc')->get();

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;
        }
        else{
            $invoices = collect();
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_items_delivired_action_report', compact(
            'invoices',
            'start_date',
            'end_date',
            'selectedBranch'
        ));
    }*/

    public function reportItemsDelivired(Request $request){
        $user = auth()->user();

        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $branchId = $request->branch_id;

        if(isset($start_date) && isset($end_date)){

            $start = Carbon::parse($start_date)->startOfDay();
            $end   = Carbon::parse($end_date)->endOfDay();

            $filterBranchId = $user->getFilterBranchId($branchId);

            $query = InvoiceItems::with(['invoice', 'product.category', 'lens'])
                ->whereBetween('created_at', [$start, $end])
                ->whereIn('status', ['delivery'])
                ->whereHas('invoice', function ($q) use ($filterBranchId) {
                    $q->whereNotIn('status', ['canceled']);
                    if ($filterBranchId) {
                        $q->where('branch_id', $filterBranchId);
                    }
                })
                ->orderBy('created_at', 'asc');

            $invoices = $query->get();
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;
        }
        else{
            $invoices = collect();
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_items_delivired_action_report', compact(
            'invoices',
            'start_date',
            'end_date',
            'selectedBranch'
        ));
    }

    public function getPendingInvoices(Request $request){
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.pending_invoices', compact('branches'));
    }

    public function reportgetPendingInvoices(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from;
        $branchId = $request->branch_id;

        if(!$date_from){
            return back();
        }

        // ✅ Determine which branch to filter by
        $filterBranchId = $user->getFilterBranchId($branchId);

        // ✅ Build query with branch filtering
        $baseQuery = Invoice::with(['customer:customer_id,english_name'])
            ->whereDate('created_at','<=', $date_from)
            ->whereNotIn('status', ['delivered', 'returned', 'canceled']);

        // ✅ Apply branch filter
        if ($filterBranchId) {
            $baseQuery->where('branch_id', $filterBranchId);
        }
        // else: Super Admin with "All Branches" - no branch filter

        $invoices = $baseQuery
            ->select(
                'invoice_code',
                'customer_id',
                'status',
                'total',
                'total_before_discount',
                'paied',
                'remaining',
                'discount_type',
                'discount_value',
                'pickup_date',
                'created_at'
            )
            ->orderBy('created_at','asc')
            ->get();

        // ✅ Get selected branch info (if any)
        $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        return view(
            'dashboard.pages.reports.print_pending_invoices_report',
            compact(
                'invoices',
                'date_from',
                'selectedBranch'
            )
        );
    }


    public function getReturnInvoices(Request $request){
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.return_invoices', compact('branches'));
    }

    public function reportgetReturnInvoices(Request $request){
        $user = auth()->user();

        $date_from = $request->return_date_from;
        $date_to = $request->return_date_to;
        $branchId = $request->branch_id;

        if(isset($date_from) && isset($date_to)){

            $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
            $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = Invoice::leftJoin('invoice_items','invoice_items.invoice_id','invoices.id')
                ->select('invoices.*')
                ->where('invoices.status','canceled')
                ->whereBetween('invoices.updated_at', [$start, $end])
                ->distinct();

            // ✅ Apply branch filter
            if ($filterBranchId) {
                $query->where('invoices.branch_id', $filterBranchId);
            }
            // else: Super Admin with "All Branches" - no branch filter

            $invoices = $query->get();

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        }else{
            $invoices = collect();
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_return_invoices_report', compact(
            'invoices',
            'date_from',
            'date_to',
            'selectedBranch'
        ));
    }

    public function getCustomers(Request $request){
        return view('dashboard.pages.reports.customers');
    }

    /*public function reportgetCustomers(Request $request){
        $date_from = $request->date_from;
        $date_to = $request->date_to;
       if(isset($date_from) && isset($date_to)){

           $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
           $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

          /*$customers = Customer::where('customers.created_at', '>=',$date_from)
            ->orwhere('customers.created_at','<=',$date_to)
            ->get();*/
    /*  $customers = Customer::whereBetween('customers.created_at', [$start, $end])
          ->orderBy('customers.created_at','asc')
          ->get();

  }else{
      $customers = null;
  }
   return view('dashboard.pages.reports.print_customers_report')->with(compact('customers','date_from','date_to'));
}*/


    public function reportgetCustomers(Request $request)
    {
        $date_from = $request->date_from;
        $date_to   = $request->date_to;

        if ($date_from && $date_to) {

            $start = Carbon::parse($date_from)->startOfDay();
            $end   = Carbon::parse($date_to)->endOfDay();


            $customers = Customer::whereHas('customerinvoices', function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            })
                ->with([
                    'customerinvoices' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end])
                            ->orderBy('created_at', 'asc');
                    }
                ])
                ->withCount([
                    'customerinvoices as invoices_count' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                ])
                ->get();

            foreach ($customers as $customer) {

                $customer->first_invoice = Invoice::where('customer_id', $customer->customer_id)
                    ->min('created_at');

                $customer->last_invoice = $customer->customerinvoices->max('created_at');

                $customer->total_payed = $customer->customerinvoices->sum('paied');
            }

        } else {
            $customers = collect();
        }

        return view(
            'dashboard.pages.reports.print_customers_report',
            compact('customers', 'date_from', 'date_to')
        );
    }




    // Add this to your ReportsController or wherever you handle reports

    public function getSalesTransactions(Request $request)
    {
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.sales_transactions', compact('branches'));
    }

    public function reportSalesTransactions(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        if (isset($date_from) && isset($date_to)) {

            $start = Carbon::parse($date_from)->startOfDay();
            $end = Carbon::parse($date_to)->endOfDay();

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = Invoice::orderBy('created_at')
                ->whereDate('invoices.created_at', '>=', $start)
                ->whereDate('invoices.created_at', '<=', $end);

            // ✅ Apply branch filter
            if ($filterBranchId) {
                $query->where('branch_id', $filterBranchId);
            } else {
                // Super Admin with "All Branches" - no branch filter
                // Shows all invoices
            }

            $invoices = $query->get()
                ->groupBy(function($item) {
                    return $item->created_at->format('Y-m-d');
                });

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        } else {
            $invoices = collect();
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_sales_transactions_report', compact(
            'invoices',
            'date_from',
            'date_to',
            'selectedBranch'
        ));
    }


    public function getCashierTransactions(Request $request)
    {
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.cashier_transactions', compact('branches'));
    }


    /**
     * ✅ CORRECT Controller Function
     * Uses the inBranch() scope from CashierTransaction model
     */

    public function reportCashierTransactions(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        $transactions = null;
        $paymentTotals = [];
        $salesTotals = [];
        $refundsTotals = [];
        $expensesTotals = [];
        $netTotals = [];

        if (isset($date_from) && isset($date_to)) {
            $start = Carbon::parse($date_from)->startOfDay();
            $end = Carbon::parse($date_to)->endOfDay();

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = CashierTransaction::with(['invoice.customer', 'invoice.branch', 'cashier'])
                ->whereBetween('transaction_date', [$start, $end]);

            // ✅ Apply branch filter using the scope (handles invoice->branch and cashier->branch)
            if ($filterBranchId) {
                $query->inBranch($filterBranchId);
            }

            $transactions = $query->orderBy('transaction_date')->get();

            // ✓ حساب الإجماليات حسب نوع الدفع
            $paymentTotals = $transactions
                ->groupBy(fn($row) => strtoupper($row->payment_type))
                ->map(fn($rows) => $rows->sum('amount'));

            // ✓ إجمالي المبيعات فقط
            $salesTotals = $transactions
                ->where('transaction_type', 'sale')
                ->groupBy(fn($row) => strtoupper($row->payment_type))
                ->map(fn($rows) => $rows->sum('amount'));

            // ✓ إجمالي الاسترجاعات فقط
            $refundsTotals = $transactions
                ->where('transaction_type', 'refund')
                ->groupBy(fn($row) => strtoupper($row->payment_type))
                ->map(fn($rows) => $rows->sum('amount'));

            // ✓ إجمالي المصاريف فقط
            $expensesTotals = $transactions
                ->where('transaction_type', 'expense')
                ->groupBy(fn($row) => strtoupper($row->payment_type))
                ->map(fn($rows) => $rows->sum('amount'));

            // ✓ الصافي لكل نوع
            $netTotals = $paymentTotals;

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;
        } else {
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_cashier_transactions_report')
            ->with(compact(
                'transactions',
                'date_from',
                'date_to',
                'paymentTotals',
                'salesTotals',
                'refundsTotals',
                'expensesTotals',
                'netTotals',
                'selectedBranch'
            ));
    }


    public function getSalesSummary(Request $request){
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.sales_summary', compact('branches'));
    }

    public function reportSalesSummary(Request $request){
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        if(isset($date_from) && isset($date_to)){

            $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
            $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = InvoiceItems::leftJoin('invoices', 'invoices.id', 'invoice_items.invoice_id')
                ->leftJoin('products','products.product_id','invoice_items.product_id')
                ->leftJoin('glass_lenses','glass_lenses.product_id','invoice_items.product_id')
                ->leftJoin('categories','categories.id','products.category_id')
                ->whereDate('invoice_items.created_at', '>=',$start)
                ->whereDate('invoice_items.created_at','<=',$end);

            // ✅ Apply branch filter
            if ($filterBranchId) {
                $query->where('invoices.branch_id', $filterBranchId);
            }

            $products = $query->select([
                'invoice_items.*',
                'categories.category_name',
                'glass_lenses.description as lens_description',
                'glass_lenses.price as lens_price',
                'glass_lenses.retail_price as lens_retail_price',
                'products.description',
                'products.total',
                'products.price',
                'products.retail_price',
                \DB::raw('count(*) as item_count')
            ])
                ->groupBy('invoice_items.product_id')
                ->get()
                ->groupBy(function($item) {
                    return $item->category_name ?? 'Uncategorized';
                });

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        }else{
            $products = null;
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_sales_summary_report')
            ->with(compact('products', 'date_from', 'date_to', 'selectedBranch'));
    }


    public function getSalesSummarySalesman(Request $request){
        $user = auth()->user();

        // Get accessible branches for the dropdown
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.sales_summary_salesman', compact('branches'));
    }

    public function reportSalesSummarySalesman(Request $request){
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        if(isset($date_from) && isset($date_to)){

            $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
            $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

            // ✅ Determine which branch to filter by
            $filterBranchId = $user->getFilterBranchId($branchId);

            // ✅ Build query with branch filtering
            $query = Invoice::leftJoin('invoice_items','invoice_items.invoice_id','invoices.id')
                ->leftJoin('products','products.product_id','invoice_items.product_id')
                ->leftJoin('users','users.id','invoices.user_id')
                ->leftJoin('glass_lenses','glass_lenses.product_id','invoice_items.product_id')
                ->leftJoin('categories','categories.id','products.category_id')
                ->whereDate('invoice_items.created_at', '>=',$start)
                ->whereDate('invoice_items.created_at','<=',$end);

            // ✅ Apply branch filter
            if ($filterBranchId) {
                $query->where('users.branch_id', $filterBranchId);
            }

            $products = $query->select([
                'invoice_items.*',
                'invoices.invoice_code',
                'categories.category_name',
                'users.first_name as user_name',
                'glass_lenses.description as lens_description',
                'glass_lenses.price as lens_price',
                'glass_lenses.retail_price as lens_retail_price',
                'products.description',
                'products.total',
                'products.price',
                'products.retail_price',
                \DB::raw('count(*) as item_count')
            ])
                ->groupBy('invoice_items.product_id')
                ->get()
                ->groupBy(function($e) {
                    return $e->user_name ?? 'Unknown Salesman';
                });

            // ✅ Get selected branch info (if any)
            $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        }else{
            $products = null;
            $selectedBranch = null;
        }

        return view('dashboard.pages.reports.print_sales_summary_salesman_report')
            ->with(compact('products', 'date_from', 'date_to', 'selectedBranch'));
    }


    public function reportReturningInvoice(Request $request){

        $invoices = Invoice::leftJoin('users', 'users.id', 'invoices.user_id')
            ->leftJoin('customers', 'customers.customer_id', 'invoices.customer_id')
            ->select(['invoices.*', 'users.first_name as user_name', 'customers.english_name as customer_name'])
            ->where('status', 'canceled')
            ->get();

        return view('dashboard.pages.reports.returning_invoice')->with(compact('invoices'));
    }

    public function printReturnInvoiceEn(Request $request, $id)
    {
        $invoice = Invoice::where('invoice_code', $id)->first();

        $invoiceItems = Invoice::where('invoice_code', $id)
            ->leftJoin('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->leftJoin('products', 'products.product_id', 'invoice_items.product_id')
            ->select('invoice_items.*', 'products.description', 'products.discount_type', 'products.discount_value')->get();

        $payments = Payments::where('invoice_id',$invoice->id)->first();

        return view('dashboard.pages.reports.print_return_invoice_en')->with(compact('invoice', 'invoiceItems', 'id','payments'));
    }

    public function printReturnInvoiceAr(Request $request, $id)
    {
        $invoice = Invoice::where('invoice_code', $id)->first();

        $invoiceItems = Invoice::where('invoice_code', $id)
            ->leftJoin('invoice_items', 'invoice_items.invoice_id', 'invoices.id')
            ->leftJoin('products', 'products.product_id', 'invoice_items.product_id')
            ->select('invoice_items.*', 'products.description', 'products.discount_type', 'products.discount_value')->get();

        $payments = Payments::where('invoice_id',$invoice->id)->first();

        return view('dashboard.pages.reports.print_return_invoice_ar')->with(compact('invoice', 'invoiceItems', 'id','payments'));
    }



    public function salesmanSummaryIndex()
    {
        $user = auth()->user();

        // Get accessible branches for the dropdown
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.salesman_summary_index', compact('branches'));
    }

    public function reportSalesSummaryPerSalesman(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        if(!$date_from || !$date_to){
            return view('dashboard.pages.reports.print_sales_summary_salesman_totals', [
                'salesSummary' => collect(),
                'date_from' => null,
                'date_to' => null,
                'selectedBranch' => null
            ]);
        }

        $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
        $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

        // ✅ Determine which branch to filter by
        $filterBranchId = $user->getFilterBranchId($branchId);

        // ✅ Build query with branch filtering
        $query = Invoice::leftJoin('users', 'users.id', '=', 'invoices.user_id')
            ->whereDate('invoices.created_at', '>=', $start)
            ->whereDate('invoices.created_at', '<=', $end);

        // ✅ Apply branch filter
        if ($filterBranchId) {
            $query->where('users.branch_id', $filterBranchId);
        }

        $salesSummary = $query->groupBy('users.id', 'users.first_name')
            ->select([
                'users.first_name as salesman',
                \DB::raw('COALESCE(SUM(invoices.total_before_discount), 0) as total_before_discount'),
                \DB::raw('COALESCE(SUM(invoices.total), 0) as total_after_discount')
            ])
            ->get();

        // ✅ Get selected branch info (if any)
        $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        return view('dashboard.pages.reports.print_sales_summary_salesman_totals',
            compact('salesSummary', 'date_from', 'date_to', 'selectedBranch')
        );
    }


    public function getSalesSummaryDoctor(Request $request) {
        return view('dashboard.pages.reports.sales_summary_doctor');
    }

    public function reportSalesSummaryDoctor(Request $request) {
        $date_from = $request->date_from;
        $date_to = $request->date_to;

        if(isset($date_from) && isset($date_to)) {
            $start = Carbon::parse($date_from)->startOfDay(); // 00:00:00
            $end   = Carbon::parse($date_to)->endOfDay();     // 23:59:59

            $doctorSummary = Invoice::leftJoin('doctors','doctors.code','invoices.doctor_id')
                ->whereDate('invoices.created_at','>=',$start)
                ->whereDate('invoices.created_at','<=',$end)
                ->select([
                    'doctors.code as doctor_code',
                    'doctors.name as doctor_name',
                    \DB::raw('COUNT(invoices.id) as invoices_count'),
                    \DB::raw('SUM(invoices.total) as total_sales'),
                    \DB::raw('SUM(invoices.total_before_discount) as total_sales_before_discount'),
                ])
                ->groupBy('doctors.name')
                ->get();
        } else {
            $doctorSummary = collect();
        }

        return view('dashboard.pages.reports.print_sales_summary_doctor', compact('doctorSummary','date_from','date_to'));
    }


    public function calc(Request $request)
    {
        //$dateFrom = $request->date_from;
        //$dateTo   = $request->date_to;
        $paymentType = strtoupper($request->payment_type);
        $start = Carbon::parse($request->date_from)->startOfDay(); // 00:00:00
        $end   = Carbon::parse($request->date_to)->endOfDay();     // 23:59:59

        $total = Payments::whereHas('invoice', function($q) use ($start, $end){
            $q->whereDate('created_at', '>=', $start)
                ->whereDate('created_at', '<=', $end);
        })
            ->where('type', $paymentType)
            ->sum('payed_amount');

        return response()->json(['total' => $total]);
    }

    public function dailyCloseCalc(Request $request)
    {
        $dateFrom = $request->date_from;
        $dateTo   = $request->date_to;
        $start = Carbon::parse($request->date_from)->startOfDay(); // 00:00:00
        $end   = Carbon::parse($request->date_to)->endOfDay();     // 23:59:59
        $rows     = $request->rows;

        if (!$rows || !is_array($rows)) {
            return redirect()->back()->withErrors('No data received');
        }

        foreach ($rows as $paymentType => $row) {

            $paymentType = strtoupper($paymentType);

            $exists = DailyClosing::where('date_from', $start)
                ->where('date_to', $end)
                ->where('payment_type', $paymentType)
                ->exists();

            if ($exists) {
                continue;
            }

            $total = Payments::whereHas('invoice', function ($q) use ($start, $end) {
                $q->whereDate('created_at', '>=', $start)
                    ->whereDate('created_at', '<=', $end);
            })
                ->where('type', $paymentType)
                ->sum('payed_amount');

            DailyClosing::create([
                'date_from'    => $dateFrom,
                'date_to'      => $dateTo,
                'payment_type' => $paymentType,
                'entry_amount' => $row['entry'] ?? 0,
                'total_amount' => $total,
                'difference'   => ($row['entry'] ?? 0) - $total,
                'reason'       => $row['reason'] ?? null,
                'notes'        => $row['notes'] ?? null,
            ]);
        }
        session()->flash('success', 'Daily Closing created successfully!');
        return redirect()->route('dashboard.report-cashier-transactions', [
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
        ]);
    }





// Add this to your ReportsController

    public function salesmanSalary()
    {
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.reports.salesman_salary', compact('branches'));
    }

    public function reportSalesmanSalary(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $branchId = $request->branch_id;

        if (!$date_from || !$date_to) {
            return view('dashboard.pages.reports.print_salesman_salary_report', [
                'data' => collect(),
                'date_from' => null,
                'date_to' => null,
                'selectedBranch' => null
            ]);
        }

        $start = Carbon::parse($date_from)->startOfDay();
        $end = Carbon::parse($date_to)->endOfDay();

        // ✅ Determine which branch to filter by
        $filterBranchId = $user->getFilterBranchId($branchId);

        // ✅ Build query with branch filtering
        $query = Invoice::leftJoin('users', 'users.id', '=', 'invoices.user_id')
            ->whereDate('invoices.created_at', '>=', $start)
            ->whereDate('invoices.created_at', '<=', $end);

        // ✅ Apply branch filter
        if ($filterBranchId) {
            $query->where('invoices.branch_id', $filterBranchId);
        }

        // Group by salesman
        $data = $query
            ->groupBy(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.salary',
                'users.commission',
                'users.branch_id'
            )
            ->select([
                'users.first_name',
                'users.last_name',
                'users.salary',
                'users.commission',
                'users.branch_id',
                \DB::raw('COALESCE(SUM(invoices.total), 0) as total_sales'),
                \DB::raw('COUNT(invoices.id) as invoice_count')
            ])
            ->get()
            ->map(function ($row) {
                // Calculate commission
                $commissionValue = ($row->total_sales * $row->commission) / 100;

                $row->salesman = trim($row->first_name . ' ' . $row->last_name);
                $row->commission_value = $commissionValue;
                $row->total_salary = $row->salary + $commissionValue;

                // Get branch name
                if ($row->branch_id) {
                    $branch = \App\Branch::find($row->branch_id);
                    $row->branch_name = $branch ? $branch->name : '-';
                } else {
                    $row->branch_name = '-';
                }

                return $row;
            });

        // ✅ Get selected branch info (if any)
        $selectedBranch = $filterBranchId ? Branch::find($filterBranchId) : null;

        return view('dashboard.pages.reports.print_salesman_salary_report', compact(
            'data',
            'date_from',
            'date_to',
            'selectedBranch'
        ));
    }



}
