<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    /**
     * Welke kolommen mass‐assignable zijn (name en slug).
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Relatie: een Company heeft meerdere Users (tenant‐gebruikers).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Relatie: een Company heeft meerdere Products.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relatie: een Company heeft meerdere Orders.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Helper‐methode om de URL‐prefix van deze company terug te geven.
     * Bijv. '/bedrijf-xyz'
     */
    public function path(): string
    {
        return '/' . $this->slug;
    }
}
