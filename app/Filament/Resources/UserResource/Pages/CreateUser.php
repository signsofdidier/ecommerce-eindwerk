<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $company = auth()->user()->tenantCompanies()->first()
            ?? auth()->user()->companies()->first();

        if ($company) {
            $company->users()->syncWithoutDetaching([
                $user->id => ['role' => $data['pivot_role'] ?? 'viewer'],
            ]);
        }

        return $user;
    }


}
