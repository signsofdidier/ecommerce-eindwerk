<?php

namespace App\Providers;

use App\Filament\Tenant\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use App\Http\Middleware\InitializeTenancyByRouteParameter;
use App\Http\Middleware\AuthenticateTenant;

// Importeer je Filament‐resources voor het tenant-paneel:
use App\Filament\Tenant\Resources\UserResource;
use App\Filament\Tenant\Resources\CategoryResource;
use App\Filament\Tenant\Resources\BrandResource;
use App\Filament\Tenant\Resources\ColorResource;
use App\Filament\Tenant\Resources\ProductResource;
use App\Filament\Tenant\Resources\OrderResource;
use App\Filament\Tenant\Resources\SettingResource;

class TenantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // 1) Unieke ID die we in User::canAccessPanel(…) gebruiken:
            ->id('tenant')

            // 2) Prefix in de URL: /tenant/{tenant}/…
            ->path('tenant/{tenant}')

            // 3) Eigen login­pagina (maken we straks in Filament\Tenant\Pages):
            ->login(\App\Filament\Tenant\Pages\TenantLogin::class)
            /*->pages([
                \App\Filament\Tenant\Pages\Dashboard::class,
            ])*/


            // 4) Kleurenschema (optioneel)
            ->colors([
                'primary' => \Filament\Support\Colors\Color::Green,
            ])

            // 5) Filament-resources die in dit paneel moeten meedraaien:
            ->resources([
                UserResource::class,
                CategoryResource::class,
                BrandResource::class,
                ColorResource::class,
                ProductResource::class,
                OrderResource::class,
                SettingResource::class,
            ])

            // 6) Middleware: eerst writen-tenancy, daarna de standaard Filament middleware
            ->middleware([
                InitializeTenancyByRouteParameter::class,
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Filament\Http\Middleware\DispatchServingFilamentEvent::class,
            ])

            // 7) Authentica­tie­middleware voor tenant­­paneel:
            //    we geven de class direct, i.p.v. alias in Kernel.php
            ->authMiddleware([
                AuthenticateTenant::class,
            ])

            // 8) Geef aan dat dit panel “tenant-aware” is:
            ->tenant(\App\Models\Tenant::class);
    }
}
