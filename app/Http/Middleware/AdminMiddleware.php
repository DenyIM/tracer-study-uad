<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check() || strtolower(auth()->user()->role) !== strtolower($role)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}