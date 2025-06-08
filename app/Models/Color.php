<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Color extends Model
{
    protected $fillable = ['product_id', 'name', 'hex', 'company_id',];

    public function products(){
        return $this->belongsToMany(Product::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::creating(function ($color) {
            if (empty($color->company_id)) {
                $color->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope(new TenantScope);
    }
}
