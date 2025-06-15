<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            if (! $request->routeIs('password.update') &&
                ! $request->routeIs('profile.edit') &&
                ! $request->routeIs('logout')) {
                return redirect()->route('profile.edit');
            }
        }

        return $next($request);
    }
}
