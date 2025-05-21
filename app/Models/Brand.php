<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'image', 'is_active', 'company_id'];

    public function products(){
        return $this->hasMany(Product::class);
    }

    // tenant relatie
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
