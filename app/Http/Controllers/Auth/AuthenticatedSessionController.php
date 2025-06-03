<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthenticatedSessionController extends Controller
{
    /**
     * Toon het login‐formulier voor superadmin.
     */
    public function create()
    {
        // Simpele view die we straks zal aanmaken in resources/views/auth/login.blade.php
        return view('auth.login');
    }

    /**
     * Verwerk het login‐formulier.
     */
    public function store(Request $request)
    {
        // Valideer e-mail en wachtwoord
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Probeer in te loggen met de “auth” guard (standaard)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Controleer of de ingelogde user wél superadmin is
            if (Auth::user()->is_super_admin) {
                // Redirect naar superadmin dashboard
                return redirect()->intended(route('superadmin.dashboard'));
            }

            // Als deze user geen superadmin is, log uit en laat 403 zien
            Auth::logout();
            return abort(403, 'Je hebt geen toegang tot de superadmin.');
        }

        // Als login fails, terug naar login met foutmelding
        return back()->withErrors([
            'email' => 'Deze inloggegevens komen niet overeen met onze gegevens.',
        ])->onlyInput('email');
    }

    /**
     * Verwerk het logout‐verzoek voor superadmin.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('superadmin.login'));
    }
}
