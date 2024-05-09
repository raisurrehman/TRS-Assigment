<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PermissionMiddleware
{
    public function handle($request, Closure $next, $permission)
    {
        if (Auth::check() && Gate::allows($permission)) {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
