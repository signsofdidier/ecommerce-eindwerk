<?php

namespace App\Filament\Tenant\Resources\SettingResource\Pages;

use App\Filament\Tenant\Resources\SettingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSetting extends CreateRecord
{
    protected static string $resource = SettingResource::class;
}
