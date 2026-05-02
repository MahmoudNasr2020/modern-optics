<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LensLab extends Model
{
    protected $table = 'lens_labs';

    protected $fillable = ['name', 'phone', 'email', 'address', 'notes', 'is_active'];

    public function purchaseOrders()
    {
        return $this->hasMany(LensPurchaseOrder::class, 'lab_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
