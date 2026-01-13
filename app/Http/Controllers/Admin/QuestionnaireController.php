<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\QuestionnaireSequence;
use App\Models\AnswerQuestion;
use App\Models\QuestionAnswer;
use App\Models\QuestionnaireProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\QuestionnaireExport;

class QuestionnaireController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function categories(Request $request)
    {
        $categories = Category::withCount('questionnaires')
            ->orderBy('order')
            ->get();
            
        // Check if editing
        $category = null;
        if ($request->has('edit')) {
            $category = Category::find($request->edit);
        }
        
        return view('admin.views.questionnaire.categories', compact('categories', 'category'));
    }
    
    /**
     * Simpan kategori baru
     */
    public function storeCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'icon', 'order']);
        $data['is_active'] = $request->has('is_active');
        $data['order'] = $data['order'] ?? 0;
        
        try {
            Category::create($data);
            return redirect()->route('admin.questionnaire.categories')
                ->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kategori: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update kategori
     */
    public function updateCategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'icon', 'order']);
        $data['is_active'] = $request->has('is_active');
        
        try {
            $category->update($data);
            return redirect()->route('admin.questionnaire.categories')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kategori: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hapus kategori
     */
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Hapus semua data terkait kategori (cascade delete)
        try {
            DB::beginTransaction();
            
            // Hapus semua questionnaires dan relasinya
            foreach ($category->questionnaires as $questionnaire) {
                // Hapus semua questions dan relasinya
                foreach ($questionnaire->questions as $question) {
                    // Hapus answers
                    AnswerQuestion::where('question_id', $question->id)->delete();
                    QuestionAnswer::whereHas('answerQuestion', function($q) use ($question) {
                        $q->where('question_id', $question->id);
                    })->delete();
                    $question->delete();
                }
                
                // Hapus progress records
                QuestionnaireProgress::where('questionnaire_id', $questionnaire->id)->delete();
                
                // Hapus dari sequence
                QuestionnaireSequence::where('questionnaire_id', $questionnaire->id)->delete();
                
                $questionnaire->delete();
            }
            
            // Hapus status questionnaire
            \App\Models\StatusQuestionnaire::where('category_id', $id)->delete();
            
            // Hapus kategori
            $category->delete();
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.categories')
                ->with('success', 'Kategori berhasil dihapus beserta semua data terkait.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
    
    /**
     * Tampilkan daftar kuesioner untuk kategori tertentu
     */
    public function questionnaires(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaires = $category->questionnaires()
            ->withCount('questions')
            ->orderBy('order')
            ->get();
            
        // Check if editing
        $questionnaire = null;
        if ($request->has('edit')) {
            $questionnaire = Questionnaire::find($request->edit);
        }
        
        return view('admin.views.questionnaire.questionnaires', compact('category', 'questionnaires', 'questionnaire'));
    }
    
    /**
     * Simpan kuesioner baru
     */
    public function storeQuestionnaire(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,NULL,id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'sometimes|boolean',
            'is_general' => 'sometimes|boolean',
            'time_estimate' => 'nullable|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'order', 'time_estimate']);
        $data['category_id'] = $categoryId;
        $data['is_required'] = $request->has('is_required');
        $data['is_general'] = $request->has('is_general');
        $data['order'] = $data['order'] ?? 0;
        $data['time_estimate'] = $data['time_estimate'] ?? 5;
        
        try {
            DB::transaction(function () use ($data, $category) {
                $questionnaire = Questionnaire::create($data);
                
                // Tambahkan ke sequence
                $maxOrder = QuestionnaireSequence::where('category_id', $category->id)->max('order') ?? 0;
                QuestionnaireSequence::create([
                    'category_id' => $category->id,
                    'questionnaire_id' => $questionnaire->id,
                    'order' => $maxOrder + 1,
                    'is_required' => $data['is_required'],
                    'unlocks_next' => true,
                ]);
            });
            
            return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
                ->with('success', 'Kuesioner berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kuesioner: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update kuesioner
     */
    public function updateQuestionnaire(Request $request, $categoryId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,' . $id . ',id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'sometimes|boolean',
            'is_general' => 'sometimes|boolean',
            'time_estimate' => 'nullable|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'order', 'time_estimate']);
        $data['is_required'] = $request->has('is_required');
        $data['is_general'] = $request->has('is_general');
        
        try {
            $questionnaire->update($data);
            
            // Update sequence
            QuestionnaireSequence::where('questionnaire_id', $id)
                ->update(['is_required' => $data['is_required']]);
            
            return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
                ->with('success', 'Kuesioner berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kuesioner: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hapus kuesioner beserta semua pertanyaan
     */
    public function destroyQuestionnaire($categoryId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Hapus semua questions dan relasinya
            foreach ($questionnaire->questions as $question) {
                // Hapus answers
                AnswerQuestion::where('question_id', $question->id)->delete();
                QuestionAnswer::whereHas('answerQuestion', function($q) use ($question) {
                    $q->where('question_id', $question->id);
                })->delete();
                $question->delete();
            }
            
            // Hapus progress records
            QuestionnaireProgress::where('questionnaire_id', $id)->delete();
            
            // Hapus dari sequence
            QuestionnaireSequence::where('questionnaire_id', $id)->delete();
            
            // Hapus kuesioner
            $questionnaire->delete();
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
                ->with('success', 'Kuesioner berhasil dihapus beserta semua pertanyaan di dalamnya.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus kuesioner: ' . $e->getMessage());
        }
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
        
        try {
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
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Tampilkan pertanyaan untuk kuesioner tertentu
     */
    public function questions(Request $request, $categoryId, $questionnaireId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        $questions = $questionnaire->questions()
            ->orderBy('order')
            ->get();
        
        // Check if editing
        $question = null;
        if ($request->has('edit')) {
            $question = Question::find($request->edit);
        }
        
        return view('admin.views.questionnaire.questions', compact(
            'category',
            'questionnaire',
            'questions',
            'question'
        ));
    }
    
    /**
     * Simpan pertanyaan baru
     */
    public function storeQuestion(Request $request, $categoryId, $questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_scale,competency_scale,radio_per_row,checkbox_per_row,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'is_required' => 'sometimes|boolean',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes|boolean',
            'has_none_option' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Parse options dari textarea ke array
        $options = null;
        if ($request->filled('options')) {
            $options = array_filter(array_map('trim', explode("\n", $request->options)));
        }
        
        // Parse row_items dari textarea ke array
        $rowItems = null;
        if ($request->filled('row_items')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->row_items)));
            $rowItems = [];
            foreach ($lines as $line) {
                if (strpos($line, '|') !== false) {
                    list($key, $value) = explode('|', $line, 2);
                    $rowItems[trim($key)] = trim($value);
                } else {
                    $rowItems[$line] = $line;
                }
            }
        }
        
        // Parse scale_options
        $scaleOptions = null;
        if ($request->filled('scale_options')) {
            $scaleOptions = array_map('trim', explode(',', $request->scale_options));
        }
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text'
        ]);
        
        $data['questionnaire_id'] = $questionnaireId;
        $data['is_required'] = $request->has('is_required');
        $data['has_other_option'] = $request->has('has_other_option');
        $data['has_none_option'] = $request->has('has_none_option');
        $data['order'] = $data['order'] ?? $questionnaire->questions()->count() + 1;
        $data['points'] = $data['points'] ?? 0;
        
        if ($options) $data['options'] = json_encode($options);
        if ($rowItems) $data['row_items'] = json_encode($rowItems);
        if ($scaleOptions) $data['scale_options'] = json_encode($scaleOptions);
        
        try {
            Question::create($data);
            
            return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
                ->with('success', 'Pertanyaan berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update pertanyaan
     */
    public function updateQuestion(Request $request, $categoryId, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_scale,competency_scale,radio_per_row,checkbox_per_row,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'is_required' => 'sometimes|boolean',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes|boolean',
            'has_none_option' => 'sometimes|boolean',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Parse options dari textarea ke array
        $options = null;
        if ($request->filled('options')) {
            $options = array_filter(array_map('trim', explode("\n", $request->options)));
        }
        
        // Parse row_items dari textarea ke array
        $rowItems = null;
        if ($request->filled('row_items')) {
            $lines = array_filter(array_map('trim', explode("\n", $request->row_items)));
            $rowItems = [];
            foreach ($lines as $line) {
                if (strpos($line, '|') !== false) {
                    list($key, $value) = explode('|', $line, 2);
                    $rowItems[trim($key)] = trim($value);
                } else {
                    $rowItems[$line] = $line;
                }
            }
        }
        
        // Parse scale_options
        $scaleOptions = null;
        if ($request->filled('scale_options')) {
            $scaleOptions = array_map('trim', explode(',', $request->scale_options));
        }
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text'
        ]);
        
        $data['is_required'] = $request->has('is_required');
        $data['has_other_option'] = $request->has('has_other_option');
        $data['has_none_option'] = $request->has('has_none_option');
        
        if ($options !== null) $data['options'] = $options ? json_encode($options) : null;
        if ($rowItems !== null) $data['row_items'] = $rowItems ? json_encode($rowItems) : null;
        if ($scaleOptions !== null) $data['scale_options'] = $scaleOptions ? json_encode($scaleOptions) : null;
        
        try {
            $question->update($data);
            
            return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
                ->with('success', 'Pertanyaan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hapus pertanyaan
     */
    public function destroyQuestion($categoryId, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        
        try {
            DB::beginTransaction();
            
            // Hapus answers
            AnswerQuestion::where('question_id', $id)->delete();
            QuestionAnswer::whereHas('answerQuestion', function($q) use ($id) {
                $q->where('question_id', $id);
            })->delete();
            
            // Hapus pertanyaan
            $question->delete();
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
                ->with('success', 'Pertanyaan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus pertanyaan: ' . $e->getMessage());
        }
    }
    
    /**
     * Hapus semua pertanyaan dalam kuesioner
     */
    public function destroyAllQuestions($categoryId, $questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        try {
            DB::beginTransaction();
            
            foreach ($questionnaire->questions as $question) {
                // Hapus answers
                AnswerQuestion::where('question_id', $question->id)->delete();
                QuestionAnswer::whereHas('answerQuestion', function($q) use ($question) {
                    $q->where('question_id', $question->id);
                })->delete();
                $question->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.questions', [$categoryId, $questionnaireId])
                ->with('success', 'Semua pertanyaan berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus semua pertanyaan: ' . $e->getMessage());
        }
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
        
        try {
            foreach ($request->order as $item) {
                Question::where('id', $item['id'])
                    ->where('questionnaire_id', $questionnaireId)
                    ->update(['order' => $item['order']]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Urutan pertanyaan berhasil diperbarui.',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus semua kategori
     */
    public function destroyAllCategories()
    {
        try {
            DB::beginTransaction();
            
            $categories = Category::all();
            
            foreach ($categories as $category) {
                foreach ($category->questionnaires as $questionnaire) {
                    foreach ($questionnaire->questions as $question) {
                        AnswerQuestion::where('question_id', $question->id)->delete();
                        QuestionAnswer::whereHas('answerQuestion', function($q) use ($question) {
                            $q->where('question_id', $question->id);
                        })->delete();
                        $question->delete();
                    }
                    
                    QuestionnaireProgress::where('questionnaire_id', $questionnaire->id)->delete();
                    QuestionnaireSequence::where('questionnaire_id', $questionnaire->id)->delete();
                    $questionnaire->delete();
                }
                
                \App\Models\StatusQuestionnaire::where('category_id', $category->id)->delete();
                $category->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.categories')
                ->with('success', 'Semua kategori berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus semua kategori: ' . $e->getMessage());
        }
    }

    /**
     * Hapus semua kuesioner dalam kategori
     */
    public function destroyAllQuestionnaires($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        try {
            DB::beginTransaction();
            
            foreach ($category->questionnaires as $questionnaire) {
                foreach ($questionnaire->questions as $question) {
                    AnswerQuestion::where('question_id', $question->id)->delete();
                    QuestionAnswer::whereHas('answerQuestion', function($q) use ($question) {
                        $q->where('question_id', $question->id);
                    })->delete();
                    $question->delete();
                }
                
                QuestionnaireProgress::where('questionnaire_id', $questionnaire->id)->delete();
                QuestionnaireSequence::where('questionnaire_id', $questionnaire->id)->delete();
                $questionnaire->delete();
            }
            
            DB::commit();
            
            return redirect()->route('admin.questionnaire.questionnaires', $categoryId)
                ->with('success', 'Semua kuesioner berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus semua kuesioner: ' . $e->getMessage());
        }
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
        
        return view('admin.views.questionnaire.statistics', compact(
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
        try {
            if ($categoryId) {
                $category = Category::findOrFail($categoryId);
                $filename = 'data-kuesioner-' . $category->slug . '-' . date('Y-m-d') . '.xlsx';
                return Excel::download(new QuestionnaireExport($categoryId), $filename);
            } else {
                $filename = 'data-kuesioner-semua-kategori-' . date('Y-m-d') . '.xlsx';
                return Excel::download(new QuestionnaireExport(), $filename);
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }
}