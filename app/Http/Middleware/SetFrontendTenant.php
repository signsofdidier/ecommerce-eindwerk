<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetFrontendTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Haal company uit de URL
        $companySlug = $request->route('company');

        if ($companySlug) {
            // Zoek de company op basis van de slug
            $company = Company::where('slug', $companySlug)->firstOrFail();

            // Sla de huidige company op in de applicatie
            app()->instance('current_company', $company);
        }

        return $next($request);
    }
}
