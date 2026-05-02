<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function catBrands() {
        return $this->hasMany(Brand::class, 'category_id');
    }

    public function catProducts() {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function insuranceCompanies() {
        return $this->belongsToMany(InsuranceCompany::class, 'insurance_company_category_discount',
            'category_id', 'insurance_company_id')->withPivot('discount_percent')->withTimestamps();
    }

    public function cardholders() {
        return $this->belongsToMany(Cardholder::class, 'cardholder_category_discount',
            'category_id', 'cardholder_id')->withPivot('discount_percent')->withTimestamps();
    }
}
