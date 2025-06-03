<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    /**
     * Welke velden mogen mass-assignment gebruiken.
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relaties: een Tenant (bedrijf) heeft veel Users, Categories, Brands, Colors, Products, Orders en Settings.
     *
     * Je kunt op elk moment nog extra relaties toevoegen (bv. addresses, etc.) als je dat nodig hebt.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function colors()
    {
        return $this->hasMany(Color::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }
}
