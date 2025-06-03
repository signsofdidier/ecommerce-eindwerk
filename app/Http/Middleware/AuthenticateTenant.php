<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class AuthenticateTenant
{
    /**
     * Controleer of de ingelogde gebruiker in de 'tenant' guard zit
     * en of diens tenant_id overeenkomt met de huidige tenant.
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant = Filament::getTenant();

        if (! $tenant) {
            abort(404, 'Geen actieve tenant.');
        }

        if (
            ! Auth::guard('tenant')->check() ||
            Auth::guard('tenant')->user()->tenant_id !== $tenant->id
        ) {
            return redirect()->route('filament.tenant.auth.login', [
                'tenant' => $tenant->slug,
            ]);
        }

        return $next($request);
    }
}
