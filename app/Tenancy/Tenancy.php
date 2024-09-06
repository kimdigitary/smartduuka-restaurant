<?php

namespace App\Tenancy;

class Tenancy
{
    public static ?string $tenantId = null;

    public static function setTenantId(string $tenantId): void
    {
        self::$tenantId = $tenantId;
    }

    public static function getTenantId(): ?int
    {
        return self::$tenantId;
    }
}