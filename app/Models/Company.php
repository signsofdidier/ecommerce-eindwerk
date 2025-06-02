<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
    ];

    // company heeft meerdere users
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
