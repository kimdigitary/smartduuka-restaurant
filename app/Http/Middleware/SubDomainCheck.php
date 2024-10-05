<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class SubDomainCheck
{
    private $excludedSubdomains = ['www', 'app', 'api'];

    public function handle(Request $request, Closure $next)
    {
        $subdomain = explode('.', $request->getHost())[0];

        if (in_array($subdomain, $this->excludedSubdomains)) {
            return $next($request);
        }

        $tenant = $this->getTenant($subdomain);

        if (!$tenant) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Tenant not found'], 404);
            }
            // For non-API routes, let Vue handle 404
            return $next($request);
        }

        // Add tenant info to the response for Vue
        $request->merge(['tenant' => $tenant]);

        return $next($request);
    }
    

    private function getTenant($subdomain)
    {
        return Cache::remember("tenant.$subdomain", now()->addHours(24), function () use ($subdomain) {
            return Tenant::where('username', $subdomain)->first();
        });
    }
}