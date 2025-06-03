<?php

namespace App\Filament\Tenant\Resources\ColorResource\Pages;

use App\Filament\Tenant\Resources\ColorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateColor extends CreateRecord
{
    protected static string $resource = ColorResource::class;

    protected function getRedirectUrl(): string{
        return ColorResource::getUrl('index');
    }
}
