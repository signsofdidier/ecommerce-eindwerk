<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sub_total',
        'grand_total',
        'tax_amount',
        'discount_amount',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
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
