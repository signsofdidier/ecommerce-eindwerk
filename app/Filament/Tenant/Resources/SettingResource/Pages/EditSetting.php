<?php

namespace App\Filament\Tenant\Resources\SettingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Tenant\Resources\SettingResource;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;
}
