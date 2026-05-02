<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceAdjustmentLog extends Model
{
    protected $fillable = [
        'type',
        'percent',
        'apply_to',
        'products_affected',
        'lenses_affected',
        'performed_by',
    ];

    protected $casts = [
        'apply_to' => 'array',
        'percent'  => 'float',
    ];

    public function performer()
    {
        return $this->belongsTo(\App\User::class, 'performed_by');
    }

    /**
     * Human-readable label for apply_to
     */
    public function getApplyToLabelAttribute(): string
    {
        $map = ['products' => 'Products', 'lenses' => 'Lenses'];
        return implode(' & ', array_map(fn($v) => $map[$v] ?? $v, $this->apply_to ?? []));
    }
}
