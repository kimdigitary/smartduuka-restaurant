<?php

namespace App\Http\Middleware;

use App\Models\TenantUser;
use App\Tenancy\Tenancy;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $tenantId = $request->header('X-Tenant'); // Get tenant id from the header

        // Check if current user has access to this tenant
        $hasAccess = TenantUser::query()->where([
            'tenant_id' => $tenantId,
            'user_id' => Auth::id(),
        ])->exists();

        if (!$hasAccess) {
            Log::info("You have no access to this content!");
            throw new Exception('Unauthorized');
        }

        Tenancy::setTenantId($tenantId); // Set tenant context

        return $next($request);
    }
}