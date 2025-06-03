<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static ?string $slug = 'dashboard'; // <- Belangrijk voor routing
    protected static string $view = 'filament.tenant.pages.dashboard'; // of laat dit weg als je standaardlayout gebruikt
}

