<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\StatusQuestionnaire;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class OnlyOneCategory
{
    public function handle(Request $request, Closure $next)
    {
        $alumni = Auth::user()->alumni;
        
        if (!$alumni) {
            return redirect()->route('login');
        }
        
        // Cek apakah alumni sudah memilih kategori
        $selectedCategory = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('status', '!=', 'not_started')
            ->first();
        
        // Jika sudah memilih kategori dan mencoba akses kategori lain
        $currentCategorySlug = $request->route('category');
        if ($selectedCategory && $currentCategorySlug) {
            $currentCategory = Category::where('slug', $currentCategorySlug)->first();
            
            if ($currentCategory && $selectedCategory->category_id != $currentCategory->id) {
                return redirect()->route('questionnaire.dashboard')
                    ->with('error', 'Anda sudah memilih kategori ' . $selectedCategory->category->name . 
                           '. Tidak bisa mengisi kategori lain.');
            }
        }
        
        return $next($request);
    }
}