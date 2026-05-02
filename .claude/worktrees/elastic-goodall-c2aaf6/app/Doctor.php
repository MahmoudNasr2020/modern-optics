<?php


namespace App;


use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';

    protected $guarded = ['id'];

    protected $casts = [];

    public function invoices()
    {
        return $this->hasMany(Invoice::class,'doctor_id','code');
    }



}
