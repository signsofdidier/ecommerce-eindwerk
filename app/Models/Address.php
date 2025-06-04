<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'street_address',
        'city',
        'state',
        'zip_code'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    // Zet voor en achternaam samen als 1 attribute
    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

    // app/Models/Product.php
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            // company_id NIET overschrijven als die er al is (voor veiligheid)
            if (blank($model->company_id) && filament('tenant')) {
                $model->company_id = filament('tenant')->id;
            }
        });
    }



}
