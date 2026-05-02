<?php
// app/DailyClosing.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyClosing extends Model
{
    protected $fillable = [
        'branch_id',
        'date',
        'status',
        'created_by',
        'closed_at'
    ];

    protected $casts = [
        'date' => 'date',
        'closed_at' => 'datetime'
    ];

    /**
     * Relations
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function payments()
    {
        return $this->hasMany(DailyClosingPayment::class);
    }

    public function balanceLogs()
    {
        return $this->hasMany(DailyClosingBalanceLog::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeForUser($query, User $user)
    {
        // Super Admin يشوف كل التقفيلات
        if ($user->isSuperAdmin() || $user->can('access-all-branches')) {
            return $query;
        }

        // باقي المستخدمين يشوفوا تقفيلات فرعهم فقط
        return $query->where('branch_id', $user->branch_id);
    }

    public function scopeInBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Helpers
     */
    public function isClosed()
    {
        return $this->status === 'closed';
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }
}
