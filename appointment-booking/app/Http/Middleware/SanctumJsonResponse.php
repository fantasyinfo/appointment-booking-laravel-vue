<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SanctumJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth('sanctum')->check()) {
            $request['user'] = auth('sanctum')->user();
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Modify this to redirect to a different route if necessary
        return  response()->json(['message' => 'Unauthorized, please login', 'error' => true, ], 401) ;
    }
}
