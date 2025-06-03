<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserIsSuperadmin
{
    public function handle(Request $request, Closure $next)
    {
        /*if (!auth()->check()) {
            return redirect()->route('filament.superadmin.auth.login');
        }*/

        /*if (!auth()->user()->is_superadmin) {
            auth()->logout();
            return redirect()->route('filament.superadmin.auth.login')
                ->withErrors(['email' => 'Only superadmins can access this panel.']);
        }*/

        return $next($request);
    }
}
