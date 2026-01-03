<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StatusQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Tampilkan halaman pemilihan kategori
     */
    public function index()
    {
        $alumni = Auth::user()->alumni;
        
        // Cek apakah alumni sudah memilih kategori
        $selectedCategory = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('status', '!=', 'not_started')
            ->first();
        
        if ($selectedCategory) {
            return redirect()->route('questionnaire.dashboard');
        }
        
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('questionnaire.categories', compact('categories'));
    }
    
    /**
     * Simpan pilihan kategori alumni
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);
        
        $alumni = Auth::user()->alumni;
        $category = Category::findOrFail($request->category_id);
        
        // Cek apakah alumni sudah memilih kategori lain
        $existingCategory = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', '!=', $category->id)
            ->first();
        
        if ($existingCategory) {
            return redirect()->back()
                ->with('error', 'Anda sudah memilih kategori ' . $existingCategory->category->name);
        }
        
        // Buat atau update status kuesioner
        StatusQuestionnaire::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'category_id' => $category->id,
            ],
            [
                'status' => 'not_started',
                'progress_percentage' => 0,
                'total_points' => 0,
            ]
        );
        
        // Redirect ke halaman dashboard kuesioner
        return redirect()->route('questionnaire.dashboard')
            ->with('success', 'Kategori ' . $category->name . ' berhasil dipilih!');
    }
    
    /**
     * Update kategori yang dipilih
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
        ]);
        
        $alumni = Auth::user()->alumni;
        $category = Category::findOrFail($request->category_id);
        
        // Hanya bisa update jika belum mulai mengisi
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('id', $id)
            ->firstOrFail();
        
        if ($statusQuestionnaire->status !== 'not_started') {
            return redirect()->back()
                ->with('error', 'Tidak bisa mengubah kategori karena sudah mulai mengisi kuesioner.');
        }
        
        $statusQuestionnaire->update([
            'category_id' => $category->id,
        ]);
        
        return redirect()->route('questionnaire.dashboard')
            ->with('success', 'Kategori berhasil diubah menjadi ' . $category->name);
    }
}