<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Velden die mass-assignment toestaan.
     */
    protected $fillable = [
        'free_shipping_threshold',
        'free_shipping_enabled',
        'tenant_id',
    ];

    /**
     * Relatie: elke setting hoort bij één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Global scope: filter op tenant_id als er een actieve tenant is.
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
