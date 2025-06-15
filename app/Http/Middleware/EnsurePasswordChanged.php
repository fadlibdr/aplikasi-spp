<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            if (! $request->routeIs('profile.edit') &&
                ! $request->routeIs('password.update') &&
                ! $request->routeIs('logout')) {
                return redirect()->route('profile.edit')
                    ->with('status', 'must-change-password');
            }
        }

        return $next($request);
    }
}
