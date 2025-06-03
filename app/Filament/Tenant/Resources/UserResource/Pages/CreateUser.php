<?php

namespace App\Filament\Tenant\Resources\UserResource\Pages;

use App\Filament\Tenant\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
