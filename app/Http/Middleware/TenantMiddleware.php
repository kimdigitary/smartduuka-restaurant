<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use App\Models\TenantUser;
use App\Tenancy\Tenancy;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantMiddleware
{
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $tenantId = $request->header('X-Tenant'); // Get tenant id from the header

        // Check if the user has the SUPER_ADMIN role
        if (!Auth::user()->hasRole(Role::SUPER_ADMIN)) {
            // If not SUPER_ADMIN, check if the current user has access to this tenant
            $hasAccess = TenantUser::query()->where(column: [
                'tenant_id' => $tenantId,
                'user_id' => Auth::id(),
            ])->exists();

            if (!$hasAccess) {
                throw new Exception('Unauthorized');
            }
        }

        Tenancy::setTenantId($tenantId); // Set tenant context

        return $next($request);
    }
}