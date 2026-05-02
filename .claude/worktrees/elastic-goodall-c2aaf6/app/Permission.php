<?php

namespace App;

use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'guard_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Permission modules
     */
    public static $modules = [
        'branches' => 'Branches Management',
        'stock' => 'Stock Management',
        'transfers' => 'Stock Transfers',
        'invoices' => 'Invoices',
        'customers' => 'Customers',
        'products' => 'Products',
        'users' => 'Users Management',
        'roles' => 'Roles & Permissions',
        'reports' => 'Reports',
        'settings' => 'Settings',
    ];

    /**
     * Scope by module
     */
    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    /**
     * Scope for active permissions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get module display name
     */
    public function getModuleDisplayNameAttribute()
    {
        return self::$modules[$this->module] ?? $this->module;
    }
}
