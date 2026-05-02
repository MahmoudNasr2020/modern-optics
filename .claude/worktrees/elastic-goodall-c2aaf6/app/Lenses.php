<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lenses extends Model
{
    protected $table = 'lenses';

    protected $guarded = ['id'];

    protected $casts = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id','customer_id');
    }
}
