<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    public function products(){
        return $this->hasMany(Product::class);
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
