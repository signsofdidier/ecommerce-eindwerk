<?php

namespace App\Services;

use App\Models\Company;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;

class TenantService
{
    public static function current()
    {
        return Filament::getTenant();
    }

    public static function id(): ?int
    {
        return self::current()?->id;
    }

    public static function slug(): ?string
    {
        // Probeer eerst via route
        $slug = request()->route('company');

        // Indien niet beschikbaar, probeer uit app container
        if (!$slug && app()->bound('currentCompany')) {
            $slug = app('currentCompany')?->slug;
        }

        // Indien nog steeds niet gevonden, gebruik fallback via Filament
        if (!$slug) {
            $slug = optional(self::current())->slug;
        }

        // Debug logging
        Log::debug('TenantService::slug()', [
            'route_company' => request()->route('company'),
            'currentCompany_bound' => app()->bound('currentCompany'),
            'currentCompany' => app()->bound('currentCompany') ? app('currentCompany') : null,
            'filamentTenant' => optional(self::current())->slug,
            'final_slug' => $slug,
        ]);

        return $slug;
    }
}
