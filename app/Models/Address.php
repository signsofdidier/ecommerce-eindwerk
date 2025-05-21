<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // tenant relatie
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }


}
