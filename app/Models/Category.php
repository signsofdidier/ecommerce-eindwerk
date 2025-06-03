<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Mass-assignment-velden.
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
        'tenant_id',
    ];

    /**
     * Relatie: elke category behoort tot één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: één category kan meerdere producten hebben.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Global scope: als er een actieve tenant is, filter op die tenant_id.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if ($tenant = Filament::getTenant()) {
                $query->where('tenant_id', $tenant->id);
            }
        });
    }

    /**
     * Bij het aanmaken van een nieuwe category automatisch tenant_id invullen.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if ($tenant = Filament::getTenant()) {
                $model->tenant_id = $tenant->id;
            }
        });
    }
}
