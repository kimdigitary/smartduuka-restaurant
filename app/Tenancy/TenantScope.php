<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{

    // Ensure the TenantScope is correctly applied
    public function apply(Builder $builder, Model $model)
    {
        $tenantId = Tenancy::getTenantId();

        if ($tenantId) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        } else {
            \Log::warning("No Tenant ID found. Scope might not work as expected.");
        }
    }

}
