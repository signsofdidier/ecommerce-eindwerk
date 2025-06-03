<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string $view = 'filament.tenant.pages.dashboard';

    public static function getNavigationGroup(): ?string
    {
        return 'Dashboard';
    }

    public static function getSlug(): string
    {
        return 'dashboard';
    }

    public static function getRouteName(?string $panel = null): string
    {
        return 'filament.tenant.pages.dashboard';
    }


    public static function getRoutes(): \Closure
    {
        return fn () => [
            \Illuminate\Support\Facades\Route::get(static::getSlug(), static::class)->name(static::getRouteName()),
        ];
    }
}
