<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
//        if ($request->hasHeader('x-api-key')) {
//            if ($request->header('x-api-key') == env('MIX_API_KEY')) {
//                return $next($request);
//            }
//        }
//        return response()->json(trans('all.message.invalid_api_key'), 400);
    }
}
