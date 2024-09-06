<?php
namespace App\Tenancy;
use Illuminate\Database\Eloquent\Model;

class TenantModel extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (TenantModel $model) {
            $model->tenant_id = Tenancy::getTenantId();
        });
    }
}