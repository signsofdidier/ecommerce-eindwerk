<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Velden die mass-assignment toestaan.
     */
    protected $fillable = [
        'user_id',
        'sub_total',
        'grand_total',
        'tax_amount',
        'discount_amount',
        'payment_method',
        'payment_status',
        'status',
        'currency',
        'shipping_amount',
        'shipping_method',
        'notes',
        'transaction_id',
        'tenant_id',
    ];

    /**
     * Relatie: elke order hoort bij één tenant.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: een order hoort bij één user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relatie: één order kan meerdere order-items hebben.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relatie: één order heeft één adres.
     */
    public function address()
    {
        return $this->hasOne(Address::class);
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
