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

    // Deze kunnen inloggen in de admin
    public function canAccessPanel(Panel $panel): bool
    {
        // Alleen als user is gekoppeld aan ten minste één company of als superadmin
        return $this->email === 'superadmin@gmail.com'
            || $this->tenantCompanies()->exists();
    }


    public function companies()
    {
        return $this->hasMany(Company::class, 'owner_id');
    }

    public function tenantCompanies()
    {
        return $this->belongsToMany(Company::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    public function hasRole(string $role): bool
    {
        return $this->getRoleInCurrentCompanyAttribute() === $role;
    }


    // Rollen in eigen company
    public function getRoleInCurrentCompanyAttribute()
    {
        // Haal de eerste gekoppelde tenant company op dit model op
        $company = auth()->user()?->tenantCompanies()->first()
            ?? auth()->user()?->companies()->first();

        // Zoek in de eerder geladen relatie of er een match is
        return $this->tenantCompanies
            ->firstWhere('id', $company?->id)?->pivot?->role;
    }

    public function getPivotRoleAttribute(): ?string
    {
        $company = auth()->user()->tenantCompanies()->first()
            ?? auth()->user()->companies()->first();

        return $this->tenantCompanies
            ->firstWhere('id', $company?->id)?->pivot?->role;
    }



}
