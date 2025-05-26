<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::create([
            'id' => 'tenant1',
            'data' => [
                'name' => 'Demo Bedrijf',
            ],
        ]);
    }
}
