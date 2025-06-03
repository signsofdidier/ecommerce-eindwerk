<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTenantUserIsValid
{
    /**
     * Handle an incoming request.
     * Zorg ervoor dat een ingelogde gebruiker alleen data ziet voor de Tenant waartoe hij hoort.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Als er een ingelogde gebruiker is en die is NIET superadmin:
        if ($user && ! $user->isSuperAdmin()) {
            if (app()->bound('currentTenant')) {
                $tenant = app('currentTenant');
                // Vergelijk de tenant_id van de user met de huidige tenant
                if ($user->tenant_id !== $tenant->id) {
                    Auth::logout();
                    abort(403, 'Je hebt geen toegang tot deze tenant.');
                }
            } else {
                // Er is geen currentTenant (bijv. hoofddomein), maar wel een ingelogde user:
                Auth::logout();
                abort(403, 'Je moet inloggen onder een geldig tenant‐subdomein.');
            }
        }

        return $next($request);
    }
}
