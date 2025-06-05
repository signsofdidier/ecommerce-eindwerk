<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'free_shipping_threshold',
        'free_shipping_enabled',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
