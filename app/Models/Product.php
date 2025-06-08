<?php

namespace App\Models;

use App\Scopes\TenantScope;
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

    // "Elke keer dat je een nieuw Product maakt, koppel het automatisch aan de juiste company."
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->company_id)) {
                $product->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope(new TenantScope);
    }
}
