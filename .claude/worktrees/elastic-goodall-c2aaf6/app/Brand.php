<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function brandModels() {
        return $this->hasMany(glassModel::class, 'brand_id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
}
