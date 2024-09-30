<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class TenantModel extends Model
{
    protected static function booted(): void
    {

        static::addGlobalScope(new TenantScope());

        static::creating(function (TenantModel $model) {
            $tenantId = Tenancy::getTenantId();
            $model->tenant_id = $tenantId;
        });

        static::retrieved(function (TenantModel $model) {
            Log::info("Model retrieved: " . get_class($model) . ", ID: {$model->id}, Tenant ID: {$model->tenant_id}");
        });
    }

    public static function query()
    {
        return parent::query();
    }
}