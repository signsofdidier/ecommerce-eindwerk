<?php

namespace App\Models;

use App\Services\TenantService;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

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
        'company_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    //"Elke keer dat je een nieuw OrderItem maakt, zorg er automatisch voor dat het gekoppeld is aan de juiste company."
    //Dit voorkomt dat je vergeet om company_id te vullen en je database foutmeldingen geeft zoals "Field 'company_id' doesn't have a default value".
    /*protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->company_id)) {
                $order->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope('company', function ($query) {
            $query->where('company_id', Filament::getTenant()?->id);
        });
    }*/

    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->company_id)) {
                if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                    $order->company_id = \Filament\Facades\Filament::getTenant()->id;
                } elseif (TenantService::current()) {
                    $order->company_id = TenantService::current()->id;
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
