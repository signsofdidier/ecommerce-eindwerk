<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
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
