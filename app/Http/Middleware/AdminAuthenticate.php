<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate;

class AdminAuthenticate extends Authenticate
{
    protected function redirectTo($request): ?string
    {
        if (!$request->expectsJson()) {
            return route('admin.login');  // ← redirect to admin login, not /login
        }
        return null;
    }
}
