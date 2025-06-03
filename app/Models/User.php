<?php

namespace App\Models;

use Filament\Facades\Filament;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * Welke velden mogen mass-assignment gebruiken.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tenant_id',
    ];

    /**
     * Velden die verborgen zijn in arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting van attributen.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relatie: elke user behoort tot (exact) één tenant (nullable).
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Relatie: een user kan meerdere orders hebben.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Global scope:
     *  - Als er een actieve tenant is (Filament::getTenant()),
     *    filter alle User-query’s op daaruit voortvloeiende tenant_id.
     *  - Als er geen actieve tenant is (Landlord-paneel), toon wél alle users.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function (Builder $query) {
            if ($tenant = Filament::getTenant()) {
                $query->where('tenant_id', $tenant->id);
            }
        });
    }

    /**
     * Automatisch tenant_id invullen bij aanmaken van een nieuwe User.
     *  - Als Filament::getTenant() bestaat (Tenant-paneel),
     *    zet user.tenant_id = actieve tenant.
     *  - Anders (Landlord-paneel) blijft tenant_id null.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            if ($tenant = Filament::getTenant()) {
                $user->tenant_id = $tenant->id;
            }
        });
    }

    /**
     * FilamentUser-interface: bepaal toegang per panel.
     *
     * @param  \Filament\Panel  $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // 1) Landlord-paneel (id = 'landlord'):
        //    enkel toelaten voor de “superadmin” met e-mail admin@gmail.com
        if ($panel->id === 'landlord') {
            return $this->email === 'admin@gmail.com';
        }

        // 2) Tenant-paneel (id = 'tenant'):
        //    enkel als user.tenant_id overeenkomt met Filament::getTenant()->id
        if ($panel->id === 'tenant') {
            return $this->tenant_id !== null
                && Filament::getTenant() !== null
                && $this->tenant_id === Filament::getTenant()->id;
        }

        // Andere panels: geen toegang
        return false;
    }
}
