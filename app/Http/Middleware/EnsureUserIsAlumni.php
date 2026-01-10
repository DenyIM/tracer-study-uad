<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAlumni
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();
        
        if ($user->role !== 'alumni') {
            if ($user->role === 'admin') {
                return redirect()->route('admin.views.dashboard')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk alumni.');
            }
            
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman alumni.');
        }

        return $next($request);
    }
}