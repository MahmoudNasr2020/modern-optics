<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    protected $fillable = ['company_name','status'];

   public function categories(){
       return $this->belongsToMany(Category::class, 'insurance_company_category_discount',
           'insurance_company_id', 'category_id')->withPivot('discount_percent')->withTimestamps();
   }
}
