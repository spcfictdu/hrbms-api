<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OptionalAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if a bearer token is present in the request
        if ($request->bearerToken()) {
            // Attempt to authenticate the user using Sanctum
            $user = Auth::guard('sanctum')->user();
            // Log::info($user);

            if ($user) {
                // Set the authenticated user in the request context
                $request->setUserResolver(function () use ($user) {
                    return $user;
                });
            }
        }

        return $next($request);
    }
}
