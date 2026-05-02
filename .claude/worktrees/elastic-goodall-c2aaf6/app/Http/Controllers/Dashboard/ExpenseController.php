<?php

namespace App\Http\Controllers\Dashboard;

use App\Expense;
use App\ExpenseCategory;
//use App\CashierTransaction;
use App\Branch;
use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ExpenseController extends Controller
{

    public function __construct()
    {
        // ========================================
        // EXPENSES PERMISSIONS
        // ========================================
        $this->middleware('permission.spatie:view-expenses')->only(['index']);
        $this->middleware('permission.spatie:create-expenses')->only(['create', 'store']);
        $this->middleware('permission.spatie:edit-expenses')->only(['edit', 'update']);
        $this->middleware('permission.spatie:delete-expenses')->only(['delete']);

        // ========================================
        // EXPENSES CATEGORIES PERMISSIONS
        // ========================================
        $this->middleware('permission.spatie:view-expense-categories')->only(['categories']);
        $this->middleware('permission.spatie:create-expense-categories')->only(['storeCategory']);
        $this->middleware('permission.spatie:edit-expense-categories')->only(['updateCategory']);
        $this->middleware('permission.spatie:delete-expense-categories')->only(['deleteCategory']);

        // ========================================
        // EXPENSES REPORTS PERMISSIONS
        // ========================================
        $this->middleware('permission.spatie:view-expenses-reports')->only(['report']);
    }

    /**
     * قائمة المصروفات مع Branch Filtering
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Build query with user branch access - forUser() scope already handles basic access
        $query = Expense::with(['category', 'paidBy', 'branch'])
            ->forUser($user);

        // Branch Filter - استخدام getFilterBranchId() من User Model
        $selectedBranchId = $user->getFilterBranchId($request->branch_id);

        // Apply branch filter if specific branch selected
        if ($selectedBranchId !== null) {
            $query->where('branch_id', $selectedBranchId);
        }
        // If null (Super Admin with no filter), query shows all accessible branches

        // Date Filters
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // Category Filter
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Payment Method Filter
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(20);

        // Categories للـ Filter (حسب الفرع)
        $categories = ExpenseCategory::active()
            ->forUser($user)
            ->get();

        // Branches للـ Filter - استخدام getAccessibleBranches()
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.expenses.index', compact('expenses', 'categories', 'branches'));
    }

    /**
     * صفحة إضافة مصروف
     */
    public function create()
    {
        $user = auth()->user();

        $categories = ExpenseCategory::active()
            ->forUser($user)
            ->get();

        // استخدام getAccessibleBranches()
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.expenses.create', compact('categories', 'branches'));
    }

    /**
     * حفظ مصروف جديد
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Validation with branch access check
        $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (!$user->canAccessBranch($value)) {
                        $fail('You do not have access to this branch.');
                    }
                },
            ],
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:CASH,VISA,MASTERCARD,MADA,ATM,BANK_TRANSFER',
            'description' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // إنشاء المصروف
            $expense = Expense::create([
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'currency' => 'QAR',
                'expense_date' => $request->expense_date,
                'payment_method' => $request->payment_method,
                'paid_by' => $user->id,
                'vendor_name' => $request->vendor_name,
                'receipt_number' => $request->receipt_number,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            // خصم من الكاشير (لو Cash ومطلوب)
            /*if (in_array($request->payment_method, ['CASH', 'VISA', 'MASTERCARD', 'ATM']) && $request->has('deduct_from_cashier')) {
                $cashierTransaction = CashierTransaction::create([
                    'transaction_type' => 'expense',
                    'payment_type' => strtolower($request->payment_method),
                    'amount' => -$request->amount, // سالب (مصروف)
                    'currency' => 'QAR',
                    'exchange_rate' => 1,
                    'invoice_id' => null,
                    'payment_id' => null,
                    'customer_id' => null,
                    'notes' => "مصروف: {$request->description}",
                    'cashier_id' => $user->id,
                    'transaction_date' => now(),
                ]);

                $expense->update([
                    'deducted_from_cashier' => true,
                    'cashier_transaction_id' => $cashierTransaction->id,
                ]);
            }*/

            //notify
            NotificationService::expenseCreated($expense);

            DB::commit();
            session()->flash('success', 'Expense added successfully!');
            return redirect()->route('dashboard.expenses.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to add expense: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * صفحة التعديل
     */
    public function edit($id)
    {
        $user = auth()->user();

        $expense = Expense::with(['category', 'branch'])
            ->forUser($user)
            ->findOrFail($id);

        // Check access using canAccessBranch()
        if (!$user->canAccessBranch($expense->branch_id)) {
            abort(403, 'You do not have access to this branch.');
        }

        $categories = ExpenseCategory::active()
            ->forUser($user)
            ->get();

        // استخدام getAccessibleBranches()
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.expenses.edit', compact('expense', 'categories', 'branches'));
    }

    /**
     * تحديث المصروف
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();

        $expense = Expense::forUser($user)->findOrFail($id);

        // Check access
        if (!$user->canAccessBranch($expense->branch_id)) {
            abort(403, 'You do not have access to this branch.');
        }

        $request->validate([
            'branch_id' => [
                'required',
                'exists:branches,id',
                function ($attribute, $value, $fail) use ($user) {
                    if (!$user->canAccessBranch($value)) {
                        $fail('You do not have access to this branch.');
                    }
                },
            ],
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'required|in:CASH,VISA,MASTERCARD,MADA,ATM,BANK_TRANSFER',
            'description' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // لو كان مخصوم من الكاشير وتغير المبلغ → حدث الكاشير
           /* if ($expense->deducted_from_cashier && $expense->amount != $request->amount) {
                if ($expense->cashierTransaction) {
                    $expense->cashierTransaction->update([
                        'amount' => -$request->amount,
                    ]);
                }
            }*/

            $expense->update([
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'payment_method' => $request->payment_method,
                'vendor_name' => $request->vendor_name,
                'receipt_number' => $request->receipt_number,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            DB::commit();
            session()->flash('success', 'Expense updated successfully!');
            return redirect()->route('dashboard.expenses.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update expense: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * حذف مصروف
     */
    public function delete($id)
    {
        $user = auth()->user();

        DB::beginTransaction();
        try {
            $expense = Expense::forUser($user)->findOrFail($id);

            // Check access
            if (!$user->canAccessBranch($expense->branch_id)) {
                abort(403, 'You do not have access to this branch.');
            }

            // لو مخصوم من الكاشير → احذف حركة الكاشير
            /*if ($expense->deducted_from_cashier && $expense->cashierTransaction) {
                $expense->cashierTransaction->delete();
            }*/

            $expense->delete();

            DB::commit();
            session()->flash('success', 'Expense deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete expense: ' . $e->getMessage());
        }

        return redirect()->back();
    }

    /**
     * تقرير المصروفات
     */
    public function report(Request $request)
    {
        $user = auth()->user();

        $date_from = $request->date_from ?? now()->startOfMonth()->format('Y-m-d');
        $date_to = $request->date_to ?? now()->endOfMonth()->format('Y-m-d');

        $start = Carbon::parse($date_from)->startOfDay();
        $end = Carbon::parse($date_to)->endOfDay();

        // Build query with branch filtering
        $query = Expense::with(['category', 'paidBy', 'branch'])
            ->forUser($user)
            ->whereBetween('expense_date', [$start, $end]);

        // Branch Filter - استخدام getFilterBranchId()
        $selectedBranchId = $user->getFilterBranchId($request->branch_id);

        if ($selectedBranchId !== null) {
            $query->where('branch_id', $selectedBranchId);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();

        // الإحصائيات
        $stats = [
            'total_expenses' => $expenses->sum('amount'),
            'cash_expenses' => $expenses->where('payment_method', 'CASH')->sum('amount'),
            'visa_expenses' => $expenses->where('payment_method', 'VISA')->sum('amount'),
            'mastercard_expenses' => $expenses->where('payment_method', 'MASTERCARD')->sum('amount'),
            'mada_expenses' => $expenses->where('payment_method', 'MADA')->sum('amount'),
            'atm_expenses' => $expenses->where('payment_method', 'ATM')->sum('amount'),
            'bank_expenses' => $expenses->where('payment_method', 'BANK_TRANSFER')->sum('amount'),
            'expenses_count' => $expenses->count(),
        ];

        // حسب الفئة
        $byCategory = $expenses->groupBy('category.name')
            ->map(function($items) {
                return [
                    'count' => $items->count(),
                    'total' => $items->sum('amount'),
                ];
            });

        // المبيعات في نفس الفترة (حسب الفرع)
        $salesQuery = CashierTransaction::where('transaction_type', 'sale')
            ->whereBetween('transaction_date', [$start, $end]);

        $refundsQuery = CashierTransaction::where('transaction_type', 'refund')
            ->whereBetween('transaction_date', [$start, $end]);

        // Apply branch filter if needed (إذا كان عندك branch_id في CashierTransaction)
        // if ($selectedBranchId !== null) {
        //     $salesQuery->where('branch_id', $selectedBranchId);
        //     $refundsQuery->where('branch_id', $selectedBranchId);
        // }

        $totalSales = $salesQuery->sum('amount');
        $totalRefunds = $refundsQuery->sum('amount');

        // الربح الصافي
        $netProfit = $totalSales + $totalRefunds - $stats['total_expenses'];

        $categories = ExpenseCategory::active()->forUser($user)->get();
        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.expenses.report', compact(
            'expenses',
            'stats',
            'byCategory',
            'totalSales',
            'totalRefunds',
            'netProfit',
            'date_from',
            'date_to',
            'categories',
            'branches'
        ));
    }

    /**
     * إدارة الفئات
     */
    public function categories()
    {
        $user = auth()->user();

        $categories = ExpenseCategory::with('branch')
            ->forUser($user)
            ->orderBy('name')
            ->get();

        $branches = $user->getAccessibleBranches();

        return view('dashboard.pages.expenses.categories', compact('categories', 'branches'));
    }

    /**
     * حفظ فئة جديدة
     */
    public function storeCategory(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name',
            'type' => 'required|in:fixed,variable',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Check branch access
        if ($request->branch_id && !$user->canAccessBranch($request->branch_id)) {
            return redirect()->back()->with('error', 'You do not have access to this branch.');
        }

        ExpenseCategory::create([
            'branch_id' => $request->branch_id, // null = Global category
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => true,
        ]);

        session()->flash('success', 'Category added successfully!');
        return redirect()->back();
    }

    /**
     * تحديث فئة
     */
    public function updateCategory(Request $request, $id)
    {
        $user = auth()->user();

        $category = ExpenseCategory::forUser($user)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:expense_categories,name,' . $id,
            'type' => 'required|in:fixed,variable',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        // Check branch access
        if ($request->branch_id && !$user->canAccessBranch($request->branch_id)) {
            return redirect()->back()->with('error', 'You do not have access to this branch.');
        }

        $category->update([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'name_ar' => $request->name_ar,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        session()->flash('success', 'Category updated successfully!');
        return redirect()->back();
    }

    /**
     * حذف فئة
     */
    public function deleteCategory($id)
    {
        $user = auth()->user();

        try {
            $category = ExpenseCategory::forUser($user)->findOrFail($id);

            // تحقق من عدم وجود مصروفات
            if ($category->expenses()->count() > 0) {
                session()->flash('error', 'Cannot delete category with existing expenses!');
                return redirect()->back();
            }

            $category->delete();
            session()->flash('success', 'Category deleted successfully!');

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete category: ' . $e->getMessage());
        }

        return redirect()->back();
    }
}
