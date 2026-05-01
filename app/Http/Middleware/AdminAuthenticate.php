<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::check()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('admin.login');
        }

        if (!Auth::user()->is_admin) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Forbidden.'], 403)
                : redirect('/');
        }

        return $next($request);
    }
}
