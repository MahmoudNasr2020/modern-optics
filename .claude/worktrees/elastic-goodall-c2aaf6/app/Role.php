<?php

namespace App;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'guard_name',
        'is_active',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_system' => 'boolean',
    ];

    /**
     * System roles that cannot be deleted
     */
    public static $systemRoles = [
        'super-admin',
        'manager',
        'cashier',
    ];

    /**
     * Check if role is system role
     */
    public function isSystemRole()
    {
        return $this->is_system || in_array($this->name, self::$systemRoles);
    }

    /**
     * Scope for active roles
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get users count
     */
    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }
}
