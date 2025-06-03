<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Velden die mass-assignment gebruiken mogen.
     */
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
        'tenant_id',
    ];

    /**
     * Relatie: elk product behoort tot één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: een product behoort tot één category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relatie: een product behoort tot één brand.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relatie: een product kan meerdere kleuren hebben via pivot.
     */
    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    /**
     * Relatie: een product kan meerdere order-items hebben.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
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

    /**
     * Als je de “images” in JSON-array cast:
     */
    protected $casts = [
         'images' => 'array',
    ];
}
