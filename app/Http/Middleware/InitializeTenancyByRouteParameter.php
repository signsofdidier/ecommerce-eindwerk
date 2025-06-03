<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

class InitializeTenancyByRouteParameter
{
    /**
     * Haal de {tenant}-slug uit de URL, vind de corresponderende Tenant
     * en stel die in Filament in.
     */
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('tenant');

        $tenant = Tenant::where('slug', $slug)->first();

        if (! $tenant) {
            abort(404, 'Tenant niet gevonden: ' . $slug);
        }

        Filament::setTenant($tenant);

        return $next($request);
    }
}
