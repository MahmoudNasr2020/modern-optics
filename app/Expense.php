<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'branch_id',
        'category_id',
        'amount',
        'currency',
        'expense_date',
        'payment_method',
        'paid_by',
        'vendor_name',
        'receipt_number',
        'description',
        'notes',
        'receipt_file',
        //'deducted_from_cashier',
        //'cashier_transaction_id',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
       // 'deducted_from_cashier' => 'boolean',
    ];

    /**
     * Relations
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /*public function cashierTransaction()
    {
        return $this->belongsTo(CashierTransaction::class, 'cashier_transaction_id');
    }*/

    /**
     * Scopes
     */
    public function scopeInBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForUser($query, User $user)
    {
        // Super Admin يشوف كل المصروفات
        if ($user->isSuperAdmin() || $user->can('access-all-branches')) {
            return $query;
        }

        // باقي المستخدمين يشوفوا مصروفات فرعهم فقط
        return $query->where('branch_id', $user->branch_id);
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('expense_date', [$from, $to]);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    /**
     * Accessors
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getPaymentMethodNameAttribute()
    {
        return strtoupper(str_replace('_', ' ', $this->payment_method));
    }
}
