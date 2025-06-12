<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'slug', 'image', 'is_active'];

    protected $dates = ['deleted_at'];


    public function products(){
        return $this->hasMany(Product::class);
    }

    public function blogs()
    {
        return $this->belongsToMany(Blog::class, 'blog_category');
    }

}
