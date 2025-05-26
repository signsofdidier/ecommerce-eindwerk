<?php

namespace App\Multitenancy\Tasks;

use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class PrefixTableNamesTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        config(['database.connections.tenant.prefix' => 'tenant_']);
        DB::purge('tenant');
    }

    public function forgetCurrent(): void
    {
        config(['database.connections.tenant.prefix' => null]);
        DB::purge('tenant');
    }
}
