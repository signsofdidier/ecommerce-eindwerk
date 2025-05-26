<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use Illuminate\Database\Seeder;

class SuperadminSeeder extends Seeder
{
    public function run(): void
    {
        // Maak superadmin user aan
        $user = User::firstOrCreate(
            ['email' => 'superadmin@gmail.com'],
            ['name' => 'Superadmin', 'password' => bcrypt('password')]
        );

        // Maak een test company aan met deze user als eigenaar
        $company = Company::firstOrCreate(
            ['slug' => 'company-1'],
            ['name' => 'Company 1', 'owner_id' => $user->id]
        );

        // Voeg deze gebruiker toe als beheerder van de company, met rol owner, zonder bestaande linken te overschrijven.”
        $company->users()->syncWithoutDetaching([
            $user->id => ['role' => 'owner'],
        ]);
    }
}

