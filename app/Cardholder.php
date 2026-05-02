<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cardholder extends Model
{
    protected $fillable = ['cardholder_name','status'];

    public function categories(){
        return $this->belongsToMany(Category::class, 'cardholder_category_discount',
            'cardholder_id', 'category_id')->withPivot('discount_percent')->withTimestamps();
    }
}
