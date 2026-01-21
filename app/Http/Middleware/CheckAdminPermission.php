<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        switch ($permission) {
            case 'edit-admin':
                if (!$user->canEditAdmin()) {
                    return redirect()->back()
                        ->with('error', 'Anda tidak memiliki izin untuk mengedit data admin');
                }
                break;
                
            case 'delete-admin':
                if (!$user->canDeleteAdmin()) {
                    return redirect()->back()
                        ->with('error', 'Anda tidak memiliki izin untuk menghapus data admin');
                }
                break;
                
            case 'create-admin':
                if (!$user->canCreateAdmin()) {
                    return redirect()->back()
                        ->with('error', 'Anda tidak memiliki izin untuk menambah admin');
                }
                break;
        }
        
        return $next($request);
    }
}