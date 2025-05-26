<?php

namespace App\Filament\Resources\CompanyResource\Pages;

namespace App\Filament\Resources\CompanyResource\Pages;

use App\Filament\Resources\CompanyResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateCompany extends CreateRecord
{
    protected static string $resource = CompanyResource::class;

    // dit haalt de huidige company data op
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::firstOrCreate(
            ['email' => $data['owner_email']],
            ['name' => 'Default Owner', 'password' => bcrypt('password')]
        );

        $data['owner_id'] = $user->id;
        unset($data['owner_email']);

        return $data;
    }
}
