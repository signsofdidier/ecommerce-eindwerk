<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Address extends Model
{
    use HasFactory;
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'street',
        'city',
        'postal_code',
        'country',
        // etc. – wat je in jouw schema hebt
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
