<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['product_id', 'name', 'hex'];

    public function products(){
        return $this->belongsToMany(Product::class);
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
