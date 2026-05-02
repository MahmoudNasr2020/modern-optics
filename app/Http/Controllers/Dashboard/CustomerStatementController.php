<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Customer;
use App\Invoice;
use App\CashierTransaction;

use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerStatementController extends Controller
{

    public function __construct()
    {
        // عرض كشف حساب العميل
        $this->middleware('permission.spatie:view-customer-status')->only([
            'searchStatement',
            'statement',
            'statementPrint',
            'statementPdf'
        ]);
    }


    public function searchStatement(Request $request)
    {

        return view('dashboard.pages.customers.statement.search');
    }

    /**
     * عرض كشف الحساب
     */
    public function statement(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        // جيب كل الفواتير
        $invoices = Invoice::where('customer_id', $customer_id)
            ->with(['payments', 'invoiceItems.getItemDescription'])
            ->orderBy('created_at', 'desc')
            ->get();

        // الإحصائيات العامة
        $stats = [
            'total_invoices' => $invoices->count(),
            'total_purchases' => $invoices->sum('total'),
            'total_paid' => $invoices->sum('paied'),
            'total_remaining' => $invoices->sum('remaining'),
            'total_discount' => $invoices->sum('discount_value'),
        ];

        // عدد الفواتير حسب الحالة
        $invoicesByStatus = [
            'pending' => $invoices->where('status', 'pending')->count(),
            'ready' => $invoices->where('status', 'ready')->count(),
            'delivered' => $invoices->where('status', 'delivered')->count(),
            'canceled' => $invoices->where('status', 'canceled')->count(),
        ];

        // المشتريات الشهرية (آخر 6 شهور)
        $monthlyPurchases = Invoice::where('customer_id', $customer_id)
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(total) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'month' => Carbon::create($item->year, $item->month)->format('M Y'),
                    'total' => $item->total
                ];
            });

        // آخر 5 دفعات
        $recentPayments = CashierTransaction::where('customer_id', $customer_id)
            ->with('invoice')
            ->orderBy('transaction_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.pages.customers.statement.index', compact(
            'customer',
            'invoices',
            'stats',
            'invoicesByStatus',
            'monthlyPurchases',
            'recentPayments'
        ));
    }

    /**
     * صفحة الطباعة
     */
    public function statementPrint($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        $invoices = Invoice::where('customer_id', $customer_id)
            ->with(['payments', 'invoiceItems'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_invoices' => $invoices->count(),
            'total_purchases' => $invoices->sum('total'),
            'total_paid' => $invoices->sum('paied'),
            'total_remaining' => $invoices->sum('remaining'),
        ];

        return view('dashboard.pages.customers.statement.print', compact(
            'customer',
            'invoices',
            'stats'
        ));
    }

    /**
     * تصدير PDF
     */
    public function statementPdf($customer_id)
    {
        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        $invoices = Invoice::where('customer_id', $customer_id)
            ->with(['payments', 'invoiceItems'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_invoices' => $invoices->count(),
            'total_purchases' => $invoices->sum('total'),
            'total_paid' => $invoices->sum('paied'),
            'total_remaining' => $invoices->sum('remaining'),
        ];

        $pdf = \Pdf::loadView('dashboard.pages.customers.statement.pdf', compact(
            'customer',
            'invoices',
            'stats'
        ));

        return $pdf->download('customer-statement-' . $customer_id . '.pdf');
    }

    /**
     * إرسال بالإيميل
     */
    /*public function statementEmail(Request $request, $customer_id)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $customer = Customer::where('customer_id', $customer_id)->firstOrFail();

        $invoices = Invoice::where('customer_id', $customer_id)
            ->with(['payments', 'invoiceItems'])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_invoices' => $invoices->count(),
            'total_purchases' => $invoices->sum('total'),
            'total_paid' => $invoices->sum('paied'),
            'total_remaining' => $invoices->sum('remaining'),
        ];

        // إنشاء PDF
        $pdf = PDF::loadView('dashboard.pages.customers.statement-pdf', compact(
            'customer',
            'invoices',
            'stats'
        ));

        // إرسال الإيميل
        try {
            Mail::send('dashboard.emails.customer-statement', compact('customer', 'stats'), function($message) use ($request, $customer, $pdf) {
                $message->to($request->email)
                    ->subject('Customer Statement - ' . $customer->english_name)
                    ->attachData($pdf->output(), 'statement.pdf');
            });

            session()->flash('success', 'Statement sent successfully to ' . $request->email);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send email: ' . $e->getMessage());
        }

        return redirect()->back();
    }*/
}
