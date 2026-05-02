<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyClosingBalanceLog extends Model
{
    protected $fillable = [
        'daily_closing_id',
        'from_payment_type',
        'to_payment_type',
        'amount'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    public function dailyClosing()
    {
        return $this->belongsTo(DailyClosing::class);
    }
}
