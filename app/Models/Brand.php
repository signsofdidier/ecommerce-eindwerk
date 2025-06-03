<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    use BelongsToCompany;
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    public function products(){
        return $this->hasMany(Product::class);
    }
}
