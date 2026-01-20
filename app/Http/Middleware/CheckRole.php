<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {

            Session::flash(
                'toast',
                'Session Anda telah berakhir. Silakan login kembali.'
            );

            return redirect()->route('login');
        }

        $user = $request->user();

        foreach ($roles as $role) {
            if ($user->role === $role) {
                return $next($request);
            }
        }

        return redirect()->route('public')
            ->with('toast', 'Anda tidak memiliki akses ke halaman ini.');
    }
}