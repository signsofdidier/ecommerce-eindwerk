<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (Model $model) {
            if (empty($model->tenant_id) && app()->bound('currentTenant')) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function scopeForTenant($query, $tenantId = null)
    {
        $tenantId = $tenantId ?: (app()->bound('currentTenant') ? app('currentTenant')->id : null);

        if ($tenantId) {
            return $query->where($this->getTable() . '.tenant_id', $tenantId);
        }

        return $query;
    }
}
