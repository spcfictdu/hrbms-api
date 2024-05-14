<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LogRequests
{
    public function handle($request, Closure $next)
    {
        $user = Auth::guard('sanctum')->user();

        $this->logOriginalRequest($request, $user);
        // $this->notAllowedRequest($request, $user);

        return $next($request);
    }

    private function logOriginalRequest($request, $user)
    {
        Log::info('Incoming request:', [
            'user' => $user ? $user->first_name : null,
            'role' => $user ? $user->role : null,
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'parameters' => $request->all(),
        ]);
    }

    private function notAllowedRequest($request, $user)
    {
        $url = $request->url();
        $baseUrl = $request->getBaseUrl();

        // Enums
        $disallowedUrls = [

            // Consultation Form


            // 'api/resource2',
            // Add more URLs or routes as needed
        ];

        // Check if the requested URL is in the disallowed list and the request method is NOT GET
        if (!in_array($url, $disallowedUrls)) {

            Log::info('Incoming request:', [
                'user' => $user ? $user->first_name : null,
                'method' => $request->method(),
                'url' => $url,
                'ip_address' => $request->ip(),
                'parameters' => $request->all(),
            ]);
        } else {
            Log::info('Incoming request:', [
                'nope' => 'nope, enum request only',
            ]);
        }
    }
}
