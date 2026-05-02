<?php
// app/DailyClosingPayment.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyClosingPayment extends Model
{
    protected $fillable = [
        'daily_closing_id',
        'payment_type',
        'system_amount',
        'entry_amount',
        'difference',
        'reason',
        'notes',
        'is_cleared'
    ];

    protected $casts = [
        'system_amount' => 'decimal:2',
        'entry_amount' => 'decimal:2',
        'difference' => 'decimal:2',
        'is_cleared' => 'boolean'
    ];

    public function dailyClosing()
    {
        return $this->belongsTo(DailyClosing::class);
    }

    public function hasUnresolvedDifference()
    {
        return $this->difference != 0
            && $this->reason !== 'ACTUAL_AVERAGE';
    }

    public function getStatusColorClass()
    {
        if ($this->difference == 0) {
            return 'success'; // أخضر
        }

        if ($this->reason === 'ACTUAL_AVERAGE') {
            return 'warning'; // أصفر
        }

        if ($this->difference != 0) {
            return 'danger'; // أحمر
        }

        return '';
    }
}
