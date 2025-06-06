<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

class SetFrontendCompany
{
    public function handle(Request $request, Closure $next)
    {
        $companySlug = $request->route('company');

        // Zoek company op slug (of ID fallback)
        $company = Company::where('slug', $companySlug)
            ->orWhere('id', $companySlug)
            ->first();

        if (!$company) {
            throw new NotFoundHttpException('Company not found');
        }

        // Zet tenant company globaal beschikbaar
        app()->instance('currentCompany', $company);

        // Deel 'currentCompany' automatisch met alle views
        View::share('currentCompany', $company);

        return $next($request);
    }
}

