<?php

namespace App\Models;

use App\Services\TenantService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'image', 'is_active', 'company_id',];

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    //"Elke keer dat je een nieuw OrderItem maakt, zorg er automatisch voor dat het gekoppeld is aan de juiste company."
    //Dit voorkomt dat je vergeet om company_id te vullen en je database foutmeldingen geeft zoals "Field 'company_id' doesn't have a default value".
    /*protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->company_id)) {
                $brand->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope('company', function ($query) {
            $query->where('company_id', Filament::getTenant()?->id);
        });
    }*/

    protected static function booted()
    {
        static::creating(function ($brand) {
            if (empty($brand->company_id)) {
                if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                    $brand->company_id = \Filament\Facades\Filament::getTenant()->id;
                } elseif (TenantService::current()) {
                    $brand->company_id = TenantService::current()->id;
                }
            }
        });

        static::addGlobalScope('company', function ($query) {
            if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                $query->where('company_id', \Filament\Facades\Filament::getTenant()->id);
                return;
            }
            if (TenantService::current()) {
                $query->where('company_id', \App\Services\TenantService::current()->id);
            }
        });
    }

}
