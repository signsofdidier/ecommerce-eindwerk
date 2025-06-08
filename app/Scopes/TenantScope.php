<?php

namespace App\Scopes;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenant = Filament::getTenant() ?? (app()->has('currentTenant') ? tenant() : null);

        if ($tenant) {
            $builder->where('company_id', $tenant->id);
        }

    }
}
