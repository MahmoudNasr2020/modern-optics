<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'branch_id',
        'name',
        'name_ar',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relations
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'category_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInBranch($query, $branchId)
    {
        return $query->where(function($q) use ($branchId) {
            $q->where('branch_id', $branchId)
                ->orWhereNull('branch_id'); // الفئات العامة (Global)
        });
    }

    public function scopeForUser($query, User $user)
    {
        // Super Admin يشوف كل الفئات
        if ($user->isSuperAdmin() || $user->can('access-all-branches')) {
            return $query;
        }

        // باقي المستخدمين يشوفوا فئات فرعهم + الفئات العامة
        return $query->where(function($q) use ($user) {
            $q->where('branch_id', $user->branch_id)
                ->orWhereNull('branch_id');
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Accessors
     */
    public function getDisplayNameAttribute()
    {
        return $this->name . ' (' . ucfirst($this->type) . ')';
    }

    public function getIsGlobalAttribute()
    {
        return $this->branch_id === null;
    }
}
