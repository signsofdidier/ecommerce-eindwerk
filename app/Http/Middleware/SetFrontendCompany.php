<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SetFrontendCompany
{
    public function handle(Request $request, Closure $next)
    {
        // Haal de company slug/id op uit de URL
        $companySlug = $request->route('company');

        // Zoek de company (je kan zoeken op slug of id, afhankelijk van jouw setup)
        $company = Company::where('slug', $companySlug)->orWhere('id', $companySlug)->first();

        if (!$company) {
            // Company niet gevonden: 404 error
            throw new NotFoundHttpException('Company not found');
        }

        // Zet de company in een singleton, app container, session, of een helper class (je keuze!)
        // Bijvoorbeeld via de app container:
        app()->instance('currentCompany', $company);

        // (Optioneel) Je kan ook een global helper maken, zoals:
        // function currentCompany() { return app('currentCompany'); }

        return $next($request);
    }
}
