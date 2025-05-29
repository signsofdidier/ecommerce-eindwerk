<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['product_id', 'name', 'hex'];

    public function products(){
        return $this->belongsToMany(Product::class);
    }

}
