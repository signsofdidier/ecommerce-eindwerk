<?php

namespace App\Multitenancy\Tasks;

use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Contracts\Tenant;
use Spatie\Multitenancy\Tasks\SwitchTenantTask;

class PrefixTableNamesTask implements SwitchTenantTask
{
    public function makeCurrent(Tenant $tenant): void
    {
        DB::connection()->setTablePrefix('tenant_' . $tenant->id . '_');
    }

    public function forgetCurrent(): void
    {
        DB::connection()->setTablePrefix('');
    }
}
