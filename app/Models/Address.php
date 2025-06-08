<?php

namespace App\Models;

use App\Scopes\TenantScope;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'street_address',
        'city',
        'state',
        'zip_code',
        'company_id',
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

    // Zet voor en achternaam samen als 1 attribute
    public function getFullNameAttribute(){
        return "{$this->first_name} {$this->last_name}";
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::creating(function ($address) {
            if (empty($address->company_id)) {
                $address->company_id = Filament::getTenant()?->id;
            }
        });

        // dit legt de relaties met de tenancy
        static::addGlobalScope(new TenantScope);
    }


}
