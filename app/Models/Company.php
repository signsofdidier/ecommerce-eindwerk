<?php

namespace App\Models;

use Spatie\Multitenancy\Models\Tenant;

class Company extends Tenant
{
    protected $fillable = [
        'id',
        'name',
        'domain', // optioneel
    ];
}
