<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionnaireSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function categories()
    {
        $categories = Category::orderBy('order')->get();
        return view('admin.questionnaire.categories', compact('categories'));
    }
    
    /**
     * Simpan kategori baru
     */
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.questionnaire.categories')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }
    
    /**
     * Update kategori
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);
        
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'icon' => $request->icon,
            'order' => $request->order ?? $category->order,
            'is_active' => $request->boolean('is_active', true),
        ]);
        
        return redirect()->route('admin.questionnaire.categories')
            ->with('success', 'Kategori berhasil diperbarui.');
    }
    
    /**
     * Hapus kategori
     */
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori memiliki kuesioner
        if ($category->questionnaires()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus kategori yang memiliki kuesioner.');
        }
        
        $category->delete();
        
        return redirect()->route('admin.questionnaire.categories')
            ->with('success', 'Kategori berhasil dihapus.');
    }
    
    /**
     * Tampilkan daftar kuesioner untuk kategori tertentu
     */
    public function questionnaires($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaires = $category->questionnaires()->orderBy('order')->get();
        
        return view('admin.questionnaire.questionnaires', compact('category', 'questionnaires'));
    }
    
    /**
     * Simpan kuesioner baru
     */
    public function storeQuestionnaire(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,NULL,id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'boolean',
            'is_general' => 'boolean',
            'time_estimate' => 'nullable|integer|min:1',
        ]);
        
        DB::transaction(function () use ($request, $category) {
            $questionnaire = Questionnaire::create([
                'category_id' => $category->id,
                'name' => $request->name,
                'slug' => $request->slug,
                'description' => $request->description,
                'order' => $request->order ?? 0,
                'is_required' => $request->boolean('is_required', true),
                'is_general' => $request->boolean('is_general', false),
                'time_estimate' => $request->time_estimate,
            ]);
            
            // Tambahkan ke sequence
            $maxOrder = QuestionnaireSequence::where('category_id', $category->id)->max('order') ?? 0;
            QuestionnaireSequence::create([
                'category_id' => $category->id,
                'questionnaire_id' => $questionnaire->id,
                'order' => $maxOrder + 1,
                'is_required' => $request->boolean('is_required', true),
                'unlocks_next' => true,
            ]);
        });
        
        return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
            ->with('success', 'Kuesioner berhasil ditambahkan.');
    }
    
    /**
     * Update kuesioner
     */
    public function updateQuestionnaire(Request $request, $categoryId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,' . $id . ',id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'boolean',
            'time_estimate' => 'nullable|integer|min:1',
        ]);
        
        $questionnaire->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'order' => $request->order ?? $questionnaire->order,
            'is_required' => $request->boolean('is_required', true),
            'time_estimate' => $request->time_estimate,
        ]);
        
        return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
            ->with('success', 'Kuesioner berhasil diperbarui.');
    }
    
    /**
     * Hapus kuesioner
     */
    public function destroyQuestionnaire($categoryId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        // Cek apakah kuesioner memiliki pertanyaan
        if ($questionnaire->questions()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus kuesioner yang memiliki pertanyaan.');
        }
        
        // Hapus dari sequence
        QuestionnaireSequence::where('questionnaire_id', $id)->delete();
        
        $questionnaire->delete();
        
        return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
            ->with('success', 'Kuesioner berhasil dihapus.');
    }
    
    /**
     * Update urutan kuesioner
     */
    public function updateQuestionnaireOrder(Request $request, $categoryId)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:questionnaires,id',
            'order.*.order' => 'required|integer',
        ]);
        
        DB::transaction(function () use ($request, $categoryId) {
            foreach ($request->order as $item) {
                Questionnaire::where('id', $item['id'])
                    ->where('category_id', $categoryId)
                    ->update(['order' => $item['order']]);
                
                QuestionnaireSequence::where('questionnaire_id', $item['id'])
                    ->where('category_id', $categoryId)
                    ->update(['order' => $item['order']]);
            }
        });
        
        return response()->json([
            'success' => true,
            'message' => 'Urutan berhasil diperbarui.',
        ]);
    }
    
    /**
     * Tampilkan pertanyaan untuk kuesioner tertentu
     */
    public function questions($categoryId, $questionnaireId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        $questions = $questionnaire->questions()->orderBy('order')->get();
        
        return view('admin.questionnaire.questions', compact('category', 'questionnaire', 'questions'));
    }
    
    /**
     * Simpan pertanyaan baru
     */
    public function storeQuestion(Request $request, $categoryId, $questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_scale,competency_scale,radio_per_row,checkbox_per_row,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'row_items' => 'nullable|array',
            'scale_options' => 'nullable|array',
            'is_required' => 'boolean',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'boolean',
            'has_none_option' => 'boolean',
        ]);
        
        $question = Question::create([
            'questionnaire_id' => $questionnaireId,
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'description' => $request->description,
            'options' => $request->options ? json_encode($request->options) : null,
            'row_items' => $request->row_items ? json_encode($request->row_items) : null,
            'scale_options' => $request->scale_options ? json_encode($request->scale_options) : null,
            'is_required' => $request->boolean('is_required', true),
            'order' => $request->order ?? 0,
            'points' => $request->points ?? 0,
            'placeholder' => $request->placeholder,
            'helper_text' => $request->helper_text,
            'has_other_option' => $request->boolean('has_other_option', false),
            'has_none_option' => $request->boolean('has_none_option', false),
        ]);
        
        return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
    }
    
    /**
     * Update pertanyaan
     */
    public function updateQuestion(Request $request, $categoryId, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        
        $request->validate([
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_scale,competency_scale,radio_per_row,checkbox_per_row,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|array',
            'row_items' => 'nullable|array',
            'scale_options' => 'nullable|array',
            'is_required' => 'boolean',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'boolean',
            'has_none_option' => 'boolean',
        ]);
        
        $question->update([
            'question_text' => $request->question_text,
            'question_type' => $request->question_type,
            'description' => $request->description,
            'options' => $request->options ? json_encode($request->options) : null,
            'row_items' => $request->row_items ? json_encode($request->row_items) : null,
            'scale_options' => $request->scale_options ? json_encode($request->scale_options) : null,
            'is_required' => $request->boolean('is_required', true),
            'order' => $request->order ?? $question->order,
            'points' => $request->points ?? $question->points,
            'placeholder' => $request->placeholder,
            'helper_text' => $request->helper_text,
            'has_other_option' => $request->boolean('has_other_option', false),
            'has_none_option' => $request->boolean('has_none_option', false),
        ]);
        
        return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
            ->with('success', 'Pertanyaan berhasil diperbarui.');
    }
    
    /**
     * Hapus pertanyaan
     */
    public function destroyQuestion($categoryId, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        
        // Cek apakah pertanyaan memiliki jawaban
        if ($question->answers()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak bisa menghapus pertanyaan yang sudah memiliki jawaban.');
        }
        
        $question->delete();
        
        return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }
    
    /**
     * Update urutan pertanyaan
     */
    public function updateQuestionOrder(Request $request, $categoryId, $questionnaireId)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|exists:questions,id',
            'order.*.order' => 'required|integer',
        ]);
        
        foreach ($request->order as $item) {
            Question::where('id', $item['id'])
                ->where('questionnaire_id', $questionnaireId)
                ->update(['order' => $item['order']]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Urutan pertanyaan berhasil diperbarui.',
        ]);
    }
    
    /**
     * Tampilkan statistik kuesioner
     */
    public function statistics()
    {
        $categories = Category::withCount(['questionnaires', 'alumniStatuses'])
            ->orderBy('order')
            ->get();
        
        $totalAlumniCompleted = \App\Models\StatusQuestionnaire::where('status', 'completed')->count();
        $totalQuestions = Question::count();
        $totalAnswers = \App\Models\AnswerQuestion::count();
        
        return view('admin.questionnaire.statistics', compact(
            'categories',
            'totalAlumniCompleted',
            'totalQuestions',
            'totalAnswers'
        ));
    }
    
    /**
     * Export data kuesioner
     */
    public function exportData($categoryId = null)
    {
        // Logic untuk export data ke Excel atau CSV
        // Menggunakan library seperti Maatwebsite/Laravel-Excel
        
        return redirect()->back()
            ->with('info', 'Fitur export akan segera tersedia.');
    }
}