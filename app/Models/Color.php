<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    /**
     * Welke velden mogen mass-assignment gebruiken.
     */
    protected $fillable = [
        'name',
        'hex',
        'tenant_id',
    ];

    /**
     * Relatie: elke kleur behoort tot één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: één kleur kan via pivot aan meerdere producten horen.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Global scope: als er een actieve tenant is, filter op tenant_id.
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
     * Bij het aanmaken automatisch tenant_id invullen.
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
