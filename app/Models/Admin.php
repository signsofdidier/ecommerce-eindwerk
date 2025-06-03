<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model voor superadmin‐accounts.
 * Guard: 'superadmin' in config/auth.php
 */
class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';

    /**
     * Mass‐assignable velden.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        // eventueel: 'is_superadmin' of andere kolommen
    ];

    /**
     * Verborgen velden.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts (bv. timestamps).
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
