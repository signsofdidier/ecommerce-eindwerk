<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    /**
     * Welke velden mogen mass-assignment gebruiken.
     */
    protected $fillable = [
        'name',
        'slug',
        'image',
        'is_active',
        'tenant_id',
    ];

    /**
     * Relatie: elke brand behoort tot één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: één brand heeft meerdere producten.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Global scope: filter op tenant als Filament::getTenant() bestaat.
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
     * Vul tenant_id automatisch in bij aanmaken.
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
