<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        // 1) Valideer invoer
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2) Probeer inloggen
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // 3) Controleer of deze ingelogde user écht superadmin is
            if (Auth::user()->is_super_admin) {
                // Ja, doorgaan naar dashboard
                return redirect()->intended(route('superadmin.dashboard'));
            }

            // Anders: niet superadmin (meld 403 en log direct uit)
            Auth::logout();
            abort(403, 'Je hebt geen toegang tot de superadmin.');
        }

        // 4) Als Auth::attempt false is, geef credentials mismatch terug
        return back()->withErrors([
            'email' => 'Deze inloggegevens komen niet overeen met onze gegevens.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('superadmin.login'));
    }
}
