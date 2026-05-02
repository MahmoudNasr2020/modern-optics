<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    protected $table = 'payments';

    protected $guarded = ['id'];

    protected $casts = [];

    public function getBenficiary() {
        return $this->hasOne(User::class, 'id', 'beneficiary');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

}
