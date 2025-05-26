<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $password = $data['password'];

        // check dat name en email bestaan
        if (empty($data['name']) || empty($data['email'])) {
            throw new \Exception('Naam en e-mail zijn verplicht');
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($password),
        ]);

        // koppel aan huidige company
        $company = auth()->user()->tenantCompanies()->first()
            ?? auth()->user()->companies()->first();

        if ($company) {
            $company->users()->attach($user->id, ['role' => 'admin']);
        }

        $this->redirect(UserResource::getUrl('index'));

        return [];
    }


}
