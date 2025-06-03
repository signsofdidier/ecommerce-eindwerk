<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    /**
     * Handle an incoming request.
     * Bepaal aan de hand van het subdomein welke Tenant we moeten binden.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Haal de volledige host op, bijvoorbeeld "bedrijf1.localhost"
        $host = $request->getHost();
        // Split op de punten, zodat we het eerste deel (subdomein) weten
        $parts = explode('.', $host);

        // Hier definieer je wat jij als “subdomein” beschouwt.
        // Als er minstens 2 delen zijn (bijv. ['bedrijf1','localhost']), is $parts[0] ons subdomein.
        $subdomain = count($parts) > 1 ? $parts[0] : null;

        if ($subdomain) {
            // Zoek in de tenants-tabel naar een record met dit subdomein
            $tenant = Tenant::where('subdomain', $subdomain)->first();

            if ($tenant) {
                // Als we de juiste Tenant hebben gevonden, binden we die in de container
                app()->instance('currentTenant', $tenant);
            } else {
                // Subdomein óf tenant bestaat niet → 404
                abort(404, "Tenant met subdomein '{$subdomain}' niet gevonden.");
            }
        } else {
            // Er is géén subdomein (bijv. hoofddomein). Dat laten we wél toe,
            // want in dat geval is het superadmin-gebied. We binden geen currentTenant.
        }

        return $next($request);
    }
}
