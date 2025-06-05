<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    protected $fillable = [
        'name'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function colors()
    {
        return $this->hasMany(Color::class);
    }

    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}
