<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Product extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'description',
        'price',
        'brand_id',
        'category_id',
        'stock',
        'images',       // als array/json opgeslagen
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale',
        'shipping_cost',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'images'      => 'array',
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
        'in_stock'    => 'boolean',
        'on_sale'     => 'boolean',
    ];

    // Relaties
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function colors()
    {
        // pivot-table met tenant_id in color_product
        return $this->belongsToMany(Color::class)
            ->withPivot('tenant_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
