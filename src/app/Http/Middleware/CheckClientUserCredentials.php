<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientUserCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $one = request()->create('api/auth/verify-token', 'GET');

        $one->headers->set('Authorization', 'Bearer ' . $request->bearerToken);
        $one->headers->set('Accept', 'application/json');

        $response = app()->handle($one);

        if ($response->status() != 200) abort($response->status(), 'Invalid Credentials.');

        app(EnsureEmailIsVerified::class)->handle($request, function ($request) use ($next) {
            return $next($request);
        });

        app(VerifiedBroker::class)->handle($request, function ($request) use ($next) {
            return $next($request);
        });

        return $next($request);
    }
}
