<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class setCurrentCompany
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Dit leest het eerste segment van de URL (bvb company-1)
        $slug = $request->segment(1);

        // Dit zoekt een company met die slug
        $company = Company::where('slug', $slug)->first();

        // Geef 404 pagina als company niet gevonden wordt
        if (! $company) {
            abort(404, 'Company not found.');
        }

        // Als hij die vindt, bewaren we die als App::get('currentCompany') zodat je hem overal kan gebruiken in controllers/views
        App::instance('currentCompany', $company);

        return $next($request);
    }
}
