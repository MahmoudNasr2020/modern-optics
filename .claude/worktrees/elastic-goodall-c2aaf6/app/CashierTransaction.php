<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashierTransaction extends Model
{
    protected $fillable = [
        'transaction_type',
        'payment_type',
        'amount',
        'currency',
        'exchange_rate',
        'amount_in_sar',
        'invoice_id',
        'payment_id',
        'customer_id',
        'bank',
        'card_number',
        'cheque_number',
        'notes',
        'cashier_id',
        'shift_id',
        'transaction_date',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'amount' => 'decimal:2',
        'amount_in_sar' => 'decimal:2',
    ];

    // Relations
    public function invoice() {
        return $this->belongsTo(Invoice::class);
    }

    public function payment() {
        return $this->belongsTo(Payments::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function cashier() {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Scopes للتقارير
    /*public function scopeInDateRange($query, $start, $end) {
        return $query->whereBetween('transaction_date', [$start, $end]);
    }*/

    /*public function scopeByType($query, $type) {
        return $query->where('transaction_type', $type);
    }*/

    /*public function scopeByPaymentType($query, $paymentType) {
        return $query->where('payment_type', $paymentType);
    }*/

    /*public function scopeByCashier($query, $cashierId) {
        return $query->where('cashier_id', $cashierId);
    }*/

    /**
     * ✅ NEW: Get branch through invoice
     * (For sale/refund transactions)
     */
    public function branch()
    {
        return $this->hasOneThrough(
            Branch::class,
            Invoice::class,
            'id',           // Foreign key on invoices table
            'id',           // Foreign key on branches table
            'invoice_id',   // Local key on cashier_transactions table
            'branch_id'     // Local key on invoices table
        );
    }

    /**
     * ✅ NEW: Get branch through cashier (user)
     * (Fallback for expense transactions or when invoice is null)
     */
    public function cashierBranch()
    {
        return $this->hasOneThrough(
            Branch::class,
            User::class,
            'id',           // Foreign key on users table
            'id',           // Foreign key on branches table
            'cashier_id',   // Local key on cashier_transactions table
            'branch_id'     // Local key on users table
        );
    }

    /**
     * ✅ NEW: Smart accessor to get branch from either invoice or cashier
     */
    public function getBranchAttribute()
    {
        // Try to get branch from invoice first
        if ($this->invoice && $this->invoice->branch_id) {
            return $this->invoice->branch;
        }

        // Fallback to cashier's branch
        if ($this->cashier && $this->cashier->branch_id) {
            return $this->cashier->branch;
        }

        return null;
    }

    /**
     * ✅ NEW: Get branch_id directly
     */
    public function getBranchIdAttribute()
    {
        // Try invoice first
        if ($this->invoice && $this->invoice->branch_id) {
            return $this->invoice->branch_id;
        }

        // Fallback to cashier's branch
        if ($this->cashier && $this->cashier->branch_id) {
            return $this->cashier->branch_id;
        }

        return null;
    }

    /**
     * ====================================================
     * SCOPES
     * ====================================================
     */

    public function scopeInDateRange($query, $start, $end)
    {
        return $query->whereBetween('transaction_date', [$start, $end]);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('transaction_type', $type);
    }

    public function scopeByPaymentType($query, $paymentType)
    {
        return $query->where('payment_type', $paymentType);
    }

    public function scopeByCashier($query, $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    /**
     * ✅ NEW: Scope for filtering by branch
     */
    public function scopeInBranch($query, $branchId)
    {
        return $query->where(function($q) use ($branchId) {
            // Filter by invoice's branch
            $q->whereHas('invoice', function($invoiceQuery) use ($branchId) {
                $invoiceQuery->where('branch_id', $branchId);
            })
                // OR filter by cashier's branch (for expenses without invoice)
                ->orWhereHas('cashier', function($cashierQuery) use ($branchId) {
                    $cashierQuery->where('branch_id', $branchId);
                });
        });
    }

    /**
     * ✅ NEW: Scope for user access (respects branch permissions)
     */
    public function scopeForUser($query, User $user)
    {
        // Super Admin sees all
        if ($user->isSuperAdmin() || $user->can('access-all-branches')) {
            return $query;
        }

        // Regular user sees only their branch transactions
        return $query->inBranch($user->branch_id);
    }
}
