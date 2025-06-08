<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;

class IdentifyTenantFromUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Haal het eerste URL-segment op
        $slug = $request->segment(1);

        // Zoek de juiste company op basis van slug
        $company = Company::where('slug', $slug)->first();

        if (!$company) {
            // Geen geldige tenant = 404 fout
            abort(404, 'Company not found');
        }

        // Zet tenant beschikbaar via container
        app()->instance('currentTenant', $company);

        // Eventueel ook in config zetten
        config(['app.current_tenant' => $company]);

        return $next($request);
    }
}
