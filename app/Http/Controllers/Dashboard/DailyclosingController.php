<?php

namespace App\Http\Controllers\Dashboard;

use App\CashierTransaction;
use App\DailyClosing;
use App\DailyClosingBalanceLog;
use App\DailyClosingPayment;
use App\Branch;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DailyclosingController extends Controller
{

    public function __construct()
    {
        // عرض صفحة التقفيل والاختيار
        $this->middleware('permission.spatie:view-daily-closing')->only(['index', 'closing']);

        // تنفيذ العمليات على التقفيل اليومي (save, auto-balance, close, reopen, clear logs)
        $this->middleware('permission.spatie:perform-daily-closing')->only([
            'saveEntry',
            'autoBalance',
            'closeDailyClosing',
            'reopenDailyClosing',
            'clearBalanceLogs'
        ]);
    }

    /**
     * صفحة الاختيار (Index)
     */
    public function index()
    {
        $user = auth()->user();

        // Get accessible branches
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.dailyClosing.index', compact('branches'));
    }

    /**
     * صفحة التقفيل اليومي مع Branch Support
     */
    public function closing(Request $request)
    {
        $user = auth()->user();

        // Validate required fields
        $request->validate([
            'date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $date = $request->date;
        $branchId = $request->branch_id;

        // Check if user can access this branch
        if (!$user->canAccessBranch($branchId)) {
            abort(403, 'You do not have access to this branch.');
        }

        $branch = Branch::findOrFail($branchId);

        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // ✅ جيب الحركات من cashier_transactions بفلترة حسب الـ Branch
        // افترض إن عندك branch_id في cashier_transactions أو عن طريق invoice
        $transactions = CashierTransaction::with(['invoice.customer', 'cashier'])
            ->whereBetween('transaction_date', [$start, $end])
            // Add branch filtering here based on your schema
            // Option 1: If cashier_transactions has branch_id
            // ->where('branch_id', $branchId)
            // Option 2: If filtering through invoice
            ->whereHas('invoice', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            // Option 3: If filtering through cashier (user)
            // ->whereHas('cashier', function($q) use ($branchId) {
            //     $q->where('branch_id', $branchId);
            // })
            ->orderBy('transaction_date')
            ->get();

        // حساب الإجماليات حسب نوع الدفع
        $paymentTotals = $transactions
            ->groupBy(fn($row) => strtoupper($row->payment_type))
            ->map(fn($rows) => $rows->sum('amount'));

        // إجمالي المبيعات
        $salesTotals = $transactions
            ->where('transaction_type', 'sale')
            ->groupBy(fn($row) => strtoupper($row->payment_type))
            ->map(fn($rows) => $rows->sum('amount'));

        // إجمالي الاسترجاعات
        $refundsTotals = $transactions
            ->where('transaction_type', 'refund')
            ->groupBy(fn($row) => strtoupper($row->payment_type))
            ->map(fn($rows) => $rows->sum('amount'));

        // إجمالي المصاريف
        $expensesTotals = $transactions
            ->where('transaction_type', 'expense')
            ->groupBy(fn($row) => strtoupper($row->payment_type))
            ->map(fn($rows) => $rows->sum('amount'));

        // جلب أو إنشاء التقفيل اليومي للفرع المحدد
        $dailyClosing = DailyClosing::firstOrCreate(
            [
                'date' => $date,
                'branch_id' => $branchId
            ],
            [
                'status' => 'open',
                'created_by' => $user->id
            ]
        );

        // Initialize payments for this closing
        $this->initializeClosingPayments($dailyClosing, $date, $branchId);

        $closingPayments = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)
            ->get()
            ->keyBy('payment_type');

        $balanceLogs = DailyClosingBalanceLog::where('daily_closing_id', $dailyClosing->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.pages.dailyClosing.closing')->with(compact(
            'transactions',
            'date',
            'branch',
            'branchId',
            'paymentTotals',
            'salesTotals',
            'refundsTotals',
            'expensesTotals',
            'dailyClosing',
            'closingPayments',
            'balanceLogs'
        ));
    }

    /**
     * Initialize closing payments
     */
    private function initializeClosingPayments($dailyClosing, $date, $branchId)
    {
        $paymentTypes = ['CASH', 'VISA', 'MASTERCARD', 'ATM'];

        foreach ($paymentTypes as $type) {
            $exists = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)
                ->where('payment_type', $type)
                ->exists();

            if (!$exists) {
                $systemAmount = $this->getSystemTotalByType($date, $type, $branchId);

                DailyClosingPayment::create([
                    'daily_closing_id' => $dailyClosing->id,
                    'payment_type' => $type,
                    'system_amount' => $systemAmount,
                    'entry_amount' => 0,
                    'difference' => 0,
                    'is_cleared' => 0
                ]);
            }
        }
    }

    /**
     * Get system total by payment type and branch
     */
    private function getSystemTotalByType($date, $paymentType, $branchId)
    {
        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        $total = CashierTransaction::whereBetween('transaction_date', [$start, $end])
            ->where('payment_type', strtoupper($paymentType))
            // Filter by branch - adjust based on your schema
            ->whereHas('invoice', function($q) use ($branchId) {
                $q->where('branch_id', $branchId);
            })
            ->sum('amount');

        return $total ?? 0;
    }

    /**
     * Save entry
     */
    public function saveEntry(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'daily_closing_id' => 'required|exists:daily_closings,id',
            'payment_type' => 'required|in:CASH,VISA,MASTERCARD,ATM',
            'entry_amount' => 'required|numeric|min:0',
            'difference' => 'nullable|numeric',
            'reason' => 'nullable|in:ACTUAL_AVERAGE,WRONG_PAYMENT,ACTUAL_SHORTAGE',
            'notes' => 'nullable|string|max:500',
            'date' => 'required',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $dailyClosing = DailyClosing::findOrFail($request->daily_closing_id);

        // Check branch access
        if (!$user->canAccessBranch($dailyClosing->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch!'
            ], 403);
        }

        if ($dailyClosing->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'Daily closing is already closed!'
            ], 403);
        }

        $payment = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)
            ->where('payment_type', $request->payment_type)
            ->firstOrFail();

        $payment->entry_amount = $request->entry_amount;
        $payment->system_amount = $this->getSystemTotalByType($request->date, $request->payment_type, $request->branch_id) ?? $payment->system_amount;

        if ($request->has('difference') && $request->difference !== null) {
            $payment->difference = $request->difference;
        } else {
            $payment->difference = $payment->entry_amount - $payment->system_amount;
        }

        $payment->reason = $request->reason;
        $payment->notes = $request->notes;
        $payment->is_cleared = ($payment->difference == 0);
        $payment->save();

        return response()->json([
            'success' => true,
            'data' => $payment,
            'message' => 'Saved successfully'
        ]);
    }

    /**
     * Auto Balance
     */
    public function autoBalance(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'daily_closing_id' => 'required|exists:daily_closings,id'
        ]);

        $dailyClosing = DailyClosing::findOrFail($request->daily_closing_id);

        // Check branch access
        if (!$user->canAccessBranch($dailyClosing->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch!'
            ], 403);
        }

        if ($dailyClosing->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'Daily closing is already closed!'
            ], 403);
        }

        DB::beginTransaction();
        try {
            if ($request->has('manual_differences')) {
                foreach ($request->manual_differences as $type => $diff) {
                    $payment = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)
                        ->where('payment_type', $type)
                        ->first();

                    if ($payment) {
                        $payment->difference = $diff;
                        $payment->save();
                    }
                }
            }

            $balanceLog = $this->zeroDifferences($dailyClosing->id);

            foreach ($balanceLog as $log) {
                DailyClosingBalanceLog::create([
                    'daily_closing_id' => $dailyClosing->id,
                    'from_payment_type' => $log['from'],
                    'to_payment_type' => $log['to'],
                    'amount' => $log['amount']
                ]);
            }

            $payments = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)->get();

            DB::commit();

            return response()->json([
                'success' => true,
                'payments' => $payments,
                'balance_log' => $balanceLog,
                'message' => 'Auto-balance completed successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Zero differences algorithm
     */
    private function zeroDifferences($closingId)
    {
        $rows = DailyClosingPayment::where('daily_closing_id', $closingId)
            ->where('difference', '!=', 0)
            ->where(function ($q) {
                $q->whereNull('reason')
                    ->orWhere('reason', '!=', 'ACTUAL_AVERAGE');
            })
            ->get();

        $positives = $rows->where('difference', '>', 0)->sortByDesc('difference');
        $negatives = $rows->where('difference', '<', 0)->sortBy('difference');

        $balanceLog = [];

        foreach ($positives as $pos) {
            if ($pos->difference <= 0) continue;

            foreach ($negatives as $neg) {
                if ($neg->difference >= 0) continue;
                if ($pos->difference <= 0) break;

                $amount = min($pos->difference, abs($neg->difference));

                $pos->difference -= $amount;
                $neg->difference += $amount;

                $pos->is_cleared = ($pos->difference == 0);
                $neg->is_cleared = ($neg->difference == 0);

                $pos->save();
                $neg->save();

                $balanceLog[] = [
                    'from' => $pos->payment_type,
                    'to' => $neg->payment_type,
                    'amount' => $amount
                ];
            }
        }

        return $balanceLog;
    }

    /**
     * Close daily closing
     */
    public function closeDailyClosing(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'daily_closing_id' => 'required|exists:daily_closings,id'
        ]);

        $dailyClosing = DailyClosing::findOrFail($request->daily_closing_id);

        // Check branch access
        if (!$user->canAccessBranch($dailyClosing->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch!'
            ], 403);
        }

        if ($dailyClosing->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'Daily closing is already closed!'
            ], 403);
        }

        $unresolved = DailyClosingPayment::where('daily_closing_id', $dailyClosing->id)
            ->where('difference', '!=', 0)
            ->where(function ($q) {
                $q->whereNull('reason')
                    ->orWhere('reason', '!=', 'ACTUAL_AVERAGE');
            })
            ->get();

        if ($unresolved->isNotEmpty()) {
            $errors = $unresolved->map(function ($p) {
                return "{$p->payment_type}: Difference = {$p->difference}";
            })->implode(', ');

            return response()->json([
                'success' => false,
                'message' => "Cannot close! Unresolved differences: {$errors}",
                'unresolved' => $unresolved
            ], 400);
        }

        $dailyClosing->status = 'closed';
        $dailyClosing->closed_at = now();
        $dailyClosing->save();

        return response()->json([
            'success' => true,
            'message' => 'Daily closing has been closed successfully! ✅'
        ]);
    }

    /**
     * Reopen daily closing
     */
    public function reopenDailyClosing(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'daily_closing_id' => 'required|exists:daily_closings,id'
        ]);

        $dailyClosing = DailyClosing::findOrFail($request->daily_closing_id);

        // Check branch access
        if (!$user->canAccessBranch($dailyClosing->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch!'
            ], 403);
        }

        if (!$dailyClosing->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'Daily closing is already open!'
            ], 403);
        }

        $dailyClosing->status = 'open';
        $dailyClosing->closed_at = null;
        $dailyClosing->save();

        return response()->json([
            'success' => true,
            'message' => 'Daily closing has been reopened successfully! 🔓'
        ]);
    }

    /**
     * Clear balance logs
     */
    public function clearBalanceLogs(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'daily_closing_id' => 'required|exists:daily_closings,id'
        ]);

        $dailyClosing = DailyClosing::findOrFail($request->daily_closing_id);

        // Check branch access
        if (!$user->canAccessBranch($dailyClosing->branch_id)) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have access to this branch!'
            ], 403);
        }

        if ($dailyClosing->isClosed()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot clear logs for a closed daily closing!'
            ], 403);
        }

        DailyClosingBalanceLog::where('daily_closing_id', $dailyClosing->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Balance history cleared successfully!'
        ]);
    }
}
