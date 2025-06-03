<?php

namespace App\Models;

use App\Models\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    use BelongsToCompany;

    protected $fillable = [
        'free_shipping_threshold',
        'free_shipping_enabled',
    ];
}
