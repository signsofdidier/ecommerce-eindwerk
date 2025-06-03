<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetCurrentCompany
{
    /**
     * Handle an incoming request.
     *
     * Deze middleware kijkt of er een route-parameter 'company' is (de slug).
     * Als die bestaat, zoekt hij het bijbehorende Company-model op en bindt
     * dat object in de container als 'currentCompany'. Modellen met
     * BelongsToCompany gebruiken dit om automatisch te scopen.
     *
     * Als de slug niet gevonden wordt, geeft hij een 404.
     */
    public function handle(Request $request, Closure $next)
    {
        // Haal het {company}-segment uit de URL (bv. 'bedrijf-abc').
        $slug = $request->route('company');

        if (! $slug) {
            // Geen {company}-segment → geen tenant-context nodig (superadmin of root-routes).
            return $next($request);
        }

        // Zoek in de tabel 'companies' op basis van slug
        $company = Company::where('slug', $slug)->first();

        if (! $company) {
            // Slug niet gevonden → 404
            abort(404, 'Bedrijf niet gevonden');
        }

        // Bind het gevonden Company-object in de service container onder sleutel 'currentCompany'
        App::instance('currentCompany', $company);

        return $next($request);
    }
}
