<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(){
        return $this->hasMany(Order::class);
    }

    // Alleen user met deze email (admin) kan in de admin panel (backend) inloggen
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->email == "admin@gmail.com";
    }

    // app/Models/Product.php
    public function company()
    {
        return $this->belongsTo(Company::class);
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            // company_id NIET overschrijven als die er al is (voor veiligheid)
            if (blank($model->company_id) && filament('tenant')) {
                $model->company_id = filament('tenant')->id;
            }
        });
    }

}
