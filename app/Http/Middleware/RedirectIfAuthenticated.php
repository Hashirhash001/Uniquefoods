<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if ($request->is('admin*')) {
                    if (Auth::user()->is_admin) {
                        // Already an admin → skip login, go to dashboard
                        return redirect()->route('admin.dashboard');
                    }

                    // Logged in but not admin → kick to frontend home
                    return redirect(RouteServiceProvider::HOME);
                }

                // Frontend route with guest middleware → go to home
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
