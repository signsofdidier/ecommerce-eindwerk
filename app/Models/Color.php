<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use BelongsToCompany;

    protected $fillable = ['product_id', 'name', 'hex'];

    public function products(){
        return $this->belongsToMany(Product::class);
    }

}
