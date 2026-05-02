<?php

// app/Helpers/PermissionHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user can manage another user
     *
     * @param \App\User $targetUser
     * @return bool
     */
    public static function canManageUser($targetUser)
    {
        $currentUser = Auth::user();

        // Super admin can manage everyone
        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        // Can't manage super admins
        if ($targetUser->isSuperAdmin()) {
            return false;
        }

        // Can't manage yourself for deletion
        if ($currentUser->id == $targetUser->id) {
            return false;
        }

        // Managers can manage users in their branch
        if ($currentUser->hasRole('manager') && $currentUser->branch_id == $targetUser->branch_id) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can access branch
     *
     * @param int $branchId
     * @return bool
     */
    public static function canAccessBranch($branchId)
    {
        $user = Auth::user();

        // Super admin can access all
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Check permission
        if ($user->can('access-all-branches')) {
            return true;
        }

        // Check own branch
        return $user->branch_id == $branchId;
    }

    /**
     * Get accessible branches for current user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAccessibleBranches()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin() || $user->can('access-all-branches')) {
            return \App\Branch::where('is_active', true)->get();
        }

        if ($user->hasBranch()) {
            return \App\Branch::where('id', $user->branch_id)
                ->where('is_active', true)
                ->get();
        }

        return collect();
    }

    /**
     * Check if user can approve transfers
     *
     * @return bool
     */
    public static function canApproveTransfers()
    {
        return Auth::user()->can('approve-transfers');
    }

    /**
     * Check if user can create invoices
     *
     * @return bool
     */
    public static function canCreateInvoices()
    {
        return Auth::user()->can('create-invoices');
    }

    /**
     * Get user role names
     *
     * @param \App\User $user
     * @return array
     */
    public static function getUserRoles($user)
    {
        return $user->roles->pluck('display_name')->toArray();
    }

    /**
     * Get permission display name
     *
     * @param string $permissionName
     * @return string
     */
    public static function getPermissionDisplayName($permissionName)
    {
        $permission = \Spatie\Permission\Models\Permission::where('name', $permissionName)->first();
        return $permission ? $permission->display_name : $permissionName;
    }

    /**
     * Check if user has any of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public static function hasAnyRole(array $roles)
    {
        return Auth::user()->hasAnyRole($roles);
    }

    /**
     * Check if user has all of the given roles
     *
     * @param array $roles
     * @return bool
     */
    public static function hasAllRoles(array $roles)
    {
        return Auth::user()->hasAllRoles($roles);
    }

    /**
     * Get user permissions by module
     *
     * @param string $module
     * @return array
     */
    public static function getPermissionsByModule($module)
    {
        return Auth::user()
            ->permissions
            ->where('module', $module)
            ->pluck('display_name', 'name')
            ->toArray();
    }

    /**
     * Check if user can perform action on branch stock
     *
     * @param int $branchId
     * @param string $action (add, reduce, adjust)
     * @return bool
     */
    public static function canManageBranchStock($branchId, $action = 'view')
    {
        $user = Auth::user();

        // Check branch access first
        if (!self::canAccessBranch($branchId)) {
            return false;
        }

        // Check specific permission
        $permissionMap = [
            'view' => 'view-stock',
            'add' => 'add-stock',
            'reduce' => 'reduce-stock',
            'adjust' => 'adjust-stock',
        ];

        $permission = $permissionMap[$action] ?? 'view-stock';
        return $user->can($permission);
    }

    /**
     * Log permission denial
     *
     * @param string $permission
     * @param string $resource
     * @return void
     */
    public static function logPermissionDenial($permission, $resource = null)
    {
        \Log::warning('Permission Denied', [
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'permission' => $permission,
            'resource' => $resource,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
        ]);
    }
}
