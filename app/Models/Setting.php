<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'free_shipping_threshold',
        'free_shipping_enabled',
        'company_id',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    //"Elke keer dat je een nieuw OrderItem maakt, zorg er automatisch voor dat het gekoppeld is aan de juiste company."
    //Dit voorkomt dat je vergeet om company_id te vullen en je database foutmeldingen geeft zoals "Field 'company_id' doesn't have a default value".
    /*protected static function booted()
    {
        static::creating(function ($setting) {
            if (empty($setting->company_id)) {
                $setting->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope('company', function ($query) {
            $query->where('company_id', Filament::getTenant()?->id);
        });
    }*/

    protected static function booted()
    {
        static::creating(function ($setting) {
            if (empty($setting->company_id)) {
                if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                    $setting->company_id = \Filament\Facades\Filament::getTenant()->id;
                } elseif (\App\Services\TenantService::current()) {
                    $setting->company_id = \App\Services\TenantService::current()->id;
                }
            }
        });

        static::addGlobalScope('company', function ($query) {
            if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                $query->where('company_id', \Filament\Facades\Filament::getTenant()->id);
                return;
            }
            if (\App\Services\TenantService::current()) {
                $query->where('company_id', \App\Services\TenantService::current()->id);
            }
        });
    }

}
