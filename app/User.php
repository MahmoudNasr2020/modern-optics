<?php

namespace App;

use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements CanResetPassword
{
    use Notifiable, HasRoles;

    protected $appends = ['image_path', 'full_name'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'image',
        'password',
        'salary',
        'commission',
        'branch_id',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Relations
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function cashierTransactions()
    {
        return $this->hasMany(CashierTransaction::class, 'cashier_id');
    }

    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'user_id');
    }

    /**
     * Accessors
     */
    public function getImagePathAttribute()
    {
        return url('storage/uploads/images/users/' . $this->image);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Permission Helpers
     */
    public function isAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    public function hasBranch()
    {
        return $this->branch_id !== null;
    }

    public function canAccessBranch($branchId)
    {
        // Super Admin can access all branches
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Check if user has specific permission
        if ($this->can('access-all-branches')) {
            return true;
        }

        // Regular user can only access their branch
        return $this->branch_id == $branchId;
    }

    /**
     * ✨ NEW: Get list of branches user can access for dropdown/selection
     */
    public function getAccessibleBranches()
    {
        // Super Admin sees all branches — cached 10 min
        if ($this->isSuperAdmin() || $this->can('access-all-branches')) {
            return \Cache::remember('branches_all_active', 600, function () {
                return Branch::where('is_active', true)->get();
            });
        }

        // Regular user sees only their branch — cached per user 10 min
        if ($this->hasBranch()) {
            return \Cache::remember('user_branch_' . $this->id, 600, function () {
                return Branch::where('id', $this->branch_id)
                    ->where('is_active', true)
                    ->get();
            });
        }

        return collect();
    }

    /**
     * ✨ NEW: Get the branch to filter data by (for current page/request)
     * إذا كان المستخدم Super Admin أو له صلاحية access-all-branches:
     * - يرجع الـ branch_id المختار من الريكوست (من الفلتر)
     * - إذا مفيش branch مختار، يرجع null (يعني كل الفروع)
     *
     * إذا كان مستخدم عادي:
     * - يرجع branch_id الخاص بيه فقط (مايقدرش يغير)
     */
    public function getFilterBranchId($requestBranchId = null)
    {
        // Super Admin or users with access-all-branches permission
        if ($this->isSuperAdmin() || $this->can('access-all-branches')) {
            // Return the requested branch_id (from filter/dropdown)
            // If null, means "all branches"
            return $requestBranchId;
        }

        // Regular user - always return their own branch
        return $this->branch_id;
    }

    /**
     * ✨ NEW: Apply branch filter to any query
     * استخدامها في الكونترولر بسهولة:
     * $invoices = $user->applyBranchFilter(Invoice::query(), request('branch_id'))->get();
     */
    public function applyBranchFilter($query, $requestBranchId = null, $columnName = 'branch_id')
    {
        $branchId = $this->getFilterBranchId($requestBranchId);

        // If branchId is null (Super Admin with no filter), return all
        if ($branchId === null) {
            return $query;
        }

        // Apply branch filter
        return $query->where($columnName, $branchId);
    }

    /**
     * ✨ NEW: Check if user can see all branches (useful for UI)
     */
    public function canSeeAllBranches()
    {
        return $this->isSuperAdmin() || $this->can('access-all-branches');
    }

    /**
     * ✨ NEW: Get current selected branch for display
     */
    public function getCurrentBranch($requestBranchId = null)
    {
        $branchId = $this->getFilterBranchId($requestBranchId);

        if ($branchId === null) {
            return null; // All branches
        }

        return Branch::find($branchId);
    }

    public function canManageUser($targetUser)
    {
        // Super admin can manage everyone except other super admins
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Managers can manage users in their branch only
        if ($this->hasRole('manager') && $this->branch_id == $targetUser->branch_id) {
            return !$targetUser->isSuperAdmin();
        }

        return false;
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
        return $query->where('branch_id', $branchId);
    }

    /**
     * ✨ NEW: Scope for filtering by user's accessible branches
     */
    public function scopeAccessibleByUser($query, User $user, $requestBranchId = null)
    {
        return $user->applyBranchFilter($query, $requestBranchId);
    }

    /**
     * Update last login
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);
    }

    public function canBeDeleted()
    {
        if (!$this->isSuperAdmin()) {
            return true;
        }

        $superAdminsCount = User::role('super-admin')->count();

        return $superAdminsCount > 1;
    }


}
