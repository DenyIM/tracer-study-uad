<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
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
        
        // Debug: Uncomment untuk melihat role user
        // \Log::info('User role: ' . $user->role);
        // dd($user->role);
        
        if ($user->role !== 'admin') {
            if ($user->role === 'alumni') {
                return redirect()->route('questionnaire.dashboard')
                    ->with('error', 'Akses ditolak. Halaman ini hanya untuk admin.');
            }
            
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman admin.');
        }

        return $next($request);
    }
}