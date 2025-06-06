<?php

namespace App\Models;

use App\Services\TenantService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'images',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
        'category_id',
        'brand_id',
        'shipping_cost',
        'company_id',
    ];

    // hierdoor worden de images omgezet naar een array uit de JSON van images
    protected $casts = [
        'images' => 'array',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function colors(){
        return $this->belongsToMany(Color::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    //"Elke keer dat je een nieuw OrderItem maakt, zorg er automatisch voor dat het gekoppeld is aan de juiste company."
    //Dit voorkomt dat je vergeet om company_id te vullen en je database foutmeldingen geeft zoals "Field 'company_id' doesn't have a default value".
    /*protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->company_id)) {
                $product->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope('company', function ($query) {
            $query->where('company_id', Filament::getTenant()?->id);
        });
    }*/

    // Dit vult company_id automatisch bij het aanmaken
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->company_id)) {
                // Eerst: Filament admin context
                if (class_exists(\Filament\Facades\Filament::class) && Filament::getTenant()) {
                    $product->company_id = Filament::getTenant()->id;
                }
                // Dan: frontend context
                elseif (TenantService::current()) {
                    $product->company_id = TenantService::current()->id;
                }
            }
        });

        // De universele global scope
        static::addGlobalScope('company', function ($query) {
            // Admin context (Filament)
            if (class_exists(\Filament\Facades\Filament::class) && Filament::getTenant()) {
                $query->where('company_id', Filament::getTenant()->id);
                return;
            }
            // Frontend context
            if (TenantService::current()) {
                $query->where('company_id', \App\Services\TenantService::current()->id);
            }
        });
    }


}
