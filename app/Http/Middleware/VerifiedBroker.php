<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedBroker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!optional(Auth::user()->brokerLicense)->is_license_verified) {
            abort(403, 'Your license number is not verified.');
        }

        if (optional(Auth::user()->brokerLicense)->is_license_expired) {
            abort(403, 'Your license number is expired.');
        }

        return $next($request);
    }
}
