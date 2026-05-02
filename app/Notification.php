<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'role',
        'type',
        'title',
        'message',
        'data',
        'created_by',
        'icon',
        'color',
        'is_read',
        'read_at',
        'action_url',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    protected $appends = ['time_ago'];


    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scopes
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }

    /**
     * Mark as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Mark as unread
     */
    public function markAsUnread()
    {
        $this->update([
            'is_read' => false,
            'read_at' => null,
        ]);
    }

    /**
     * Get time ago
     */
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get icon class
     */
    public function getIconClassAttribute()
    {
        $icons = [
            'invoice_created' => 'bi-receipt',
            'transfer_created' => 'bi-arrow-left-right',
            'transfer_approved' => 'bi-check-circle',
            'transfer_received' => 'bi-box-seam',
            'low_stock' => 'bi-exclamation-triangle',
            'out_of_stock' => 'bi-x-circle',
            'user_created' => 'bi-person-plus',
            'expense_created' => 'bi-cash-coin',
            'default' => 'bi-bell',
        ];

        return $icons[$this->type] ?? $icons['default'];
    }

    /**
     * Get color class
     */
    public function getColorClassAttribute()
    {
        $colors = [
            'success' => 'success',
            'info' => 'info',
            'warning' => 'warning',
            'danger' => 'danger',
            'primary' => 'primary',
        ];

        return $colors[$this->color] ?? 'info';
    }
}
