<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';

    protected $guarded = ['id'];

    protected $casts = [];

    protected $fillable = ['customer_id', 'customer_type', 'title', 'english_name', 'local_name', 'gender',
        'birth_date', 'prefered_language', 'nationality', 'national_id', 'age',
        'country', 'city', 'address', 'dial_code', 'mobile_number', 'email',
        'receive_notifications', 'office_number', 'total_spent', 'total_points', 'notes', 'moftah_club'];

    public function eyetests()
    {
        return $this->hasMany(Lenses::class, 'customer_id', 'customer_id');
    }

    public function customerinvoices() {
        return $this->hasMany(Invoice::class, 'customer_id', 'customer_id');
    }

    public function customerTotalInvoices(){
        $totalInvoices = $this->customerinvoices()->count();

       return $totalInvoices;
    }


    public function customerPayedInvoices(){
        $payedInvoices = $this->customerinvoices()->sum('paied');

       return $payedInvoices;
    }

    public static function booted()
    {
        static::deleting(function ($customer) {
            $customer->eyetests()->delete();
            $customer->customerinvoices()->delete();
        });
    }
 }
