<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_amount',
        'total_amount',
        'company_id',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    //"Elke keer dat je een nieuw OrderItem maakt, zorg er automatisch voor dat het gekoppeld is aan de juiste company."
    //Dit voorkomt dat je vergeet om company_id te vullen en je database foutmeldingen geeft zoals "Field 'company_id' doesn't have a default value".
    /*protected static function booted()
    {
        static::creating(function ($orderItem) {
            if (empty($orderItem->company_id)) {
                $orderItem->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope('company', function ($query) {
            $query->where('company_id', Filament::getTenant()?->id);
        });
    }*/

    protected static function booted()
    {
        static::creating(function ($orderItem) {
            if (empty($orderItem->company_id)) {
                if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                    $orderItem->company_id = \Filament\Facades\Filament::getTenant()->id;
                } elseif (function_exists('currentCompany') && currentCompany()) {
                    $orderItem->company_id = currentCompany()->id;
                }
            }
        });

        static::addGlobalScope('company', function ($query) {
            if (class_exists(\Filament\Facades\Filament::class) && \Filament\Facades\Filament::getTenant()) {
                $query->where('company_id', \Filament\Facades\Filament::getTenant()->id);
                return;
            }
            if (function_exists('currentCompany') && currentCompany()) {
                $query->where('company_id', currentCompany()->id);
            }
        });
    }

}
