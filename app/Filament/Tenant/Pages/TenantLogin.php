<?php

namespace App\Filament\Tenant\Pages;

use Filament\Pages\Page;

class TenantLogin extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.tenant-login';
}
