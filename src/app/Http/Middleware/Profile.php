<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use Facades\App\Repositories\Contracts\SlugRepositoryInterface as SlugRepository;

class Profile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!SlugRepository::profile($request->route('url'))) abort(404, 'Page not found');

        return $next($request);
    }
}
