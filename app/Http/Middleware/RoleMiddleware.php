<?php

namespace App\Http\Middleware;

use App\Exceptions\UnauthorizedException;
use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (Auth::guest()) {
            throw UnauthorizedException::notLoggedIn();
        }
        if($role === '*'){
            return $next($request);
        }
        $roles = explode('|', $role);
        if (!Auth::user()->hasRole($roles)) {
            throw UnauthorizedException::forRoles($roles);
        }
        return $next($request);
    }
}
