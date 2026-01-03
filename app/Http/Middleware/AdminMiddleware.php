<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check() || strtolower(Auth::user()->role) !== strtolower($role)) {
            abort(403, 'Akses ditolak');
        }

        return $next($request);
    }
}