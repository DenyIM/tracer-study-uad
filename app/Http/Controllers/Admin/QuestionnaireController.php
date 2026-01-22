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
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:191',
                Rule::unique('categories', 'slug'),
            ],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'order' => 'nullable|integer',
            'is_active' => 'sometimes',
        ]);

        $data = [
            'name'       => $validated['name'],
            'slug'       => $validated['slug'] ?? Str::slug($validated['name']),
            'description'=> $validated['description'] ?? null,
            'icon'       => $validated['icon'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active'  => $request->filled('is_active'),
        ];
        
        try {
            Category::create($data);
            return redirect()
                ->route('admin.questionnaire.categories')
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
            'is_active' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'icon', 'order']);
        $data['is_active'] = $request->filled('is_active');
        
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
     * Hapus kategori (select delete)
     */
    public function destroyCategory(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->ids as $id) {
                $category = Category::findOrFail($id);
                
                // Hapus semua data terkait kategori
                foreach ($category->questionnaires as $questionnaire) {
                    // Hanya hapus kuesioner khusus (non-general)
                    if (!$questionnaire->is_general) {
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
                }
                
                // Hapus status questionnaire
                \App\Models\StatusQuestionnaire::where('category_id', $id)->delete();
                
                // Hapus kategori
                $category->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' kategori berhasil dihapus.',
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Tampilkan daftar kuesioner untuk kategori tertentu (non-general only)
     */
    public function questionnaires(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaires = $category->questionnaires()
            ->where('is_general', false) // Hanya tampilkan yang bukan umum
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
     * Simpan kuesioner baru (non-general only)
     */
    public function storeQuestionnaire(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,NULL,id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'order']);
        $data['category_id'] = $categoryId;
        $data['is_required'] = $request->filled('is_required');
        $data['is_general'] = false; // Selalu false untuk kuesioner spesifik
        $data['order'] = $data['order'] ?? 0;
        
        try {
            DB::transaction(function () use ($data, $category) {
                $questionnaire = Questionnaire::create($data);
                
                // Tambahkan ke sequence
                $maxOrder = QuestionnaireSequence::where('category_id', $category->id)
                    ->whereHas('questionnaire', function($q) {
                        $q->where('is_general', false);
                    })
                    ->max('order') ?? 1; // Mulai dari 2 karena umum di order 1
                
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
     * Update kuesioner (non-general only)
     */
    public function updateQuestionnaire(Request $request, $categoryId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Kuesioner umum tidak dapat diedit dari sini.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,' . $id . ',id,category_id,' . $categoryId,
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_required' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description', 'order']);
        $data['is_required'] = $request->filled('is_required');
        
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
     * Hapus kuesioner (select delete) - non-general only
     */
    public function destroyQuestionnaire(Request $request, $categoryId)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:questionnaires,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->ids as $id) {
                $questionnaire = Questionnaire::findOrFail($id);
                
                // Pastikan bukan kuesioner umum
                if ($questionnaire->is_general) {
                    continue;
                }
                
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
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' kuesioner berhasil dihapus.',
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kuesioner: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Tampilkan halaman manajemen kuesioner umum
     */
    public function generalQuestionnaires(Request $request)
    {
        // Ambil semua kategori yang aktif
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();
            
        // Ambil kuesioner umum untuk kategori pertama atau kategori yang dipilih
        $selectedCategoryId = $request->get('category_id', $categories->first()->id ?? null);
        $selectedCategory = Category::find($selectedCategoryId);
        
        $generalQuestionnaire = null;
        $questions = collect();
        
        if ($selectedCategory) {
            $generalQuestionnaire = Questionnaire::where('category_id', $selectedCategory->id)
                ->where('is_general', true)
                ->first();
                
            // Jika belum ada, buat otomatis
            if (!$generalQuestionnaire) {
                $generalQuestionnaire = Questionnaire::create([
                    'category_id' => $selectedCategory->id,
                    'name' => 'Bagian Umum',
                    'slug' => 'bagian-umum',
                    'description' => 'Wajib diisi oleh semua alumni',
                    'order' => 1,
                    'is_required' => true,
                    'is_general' => true,
                ]);
                
                // Tambahkan ke sequence
                QuestionnaireSequence::updateOrCreate(
                    [
                        'category_id' => $selectedCategory->id,
                        'questionnaire_id' => $generalQuestionnaire->id,
                    ],
                    [
                        'order' => 1,
                        'is_required' => true,
                        'unlocks_next' => true,
                    ]
                );
            }
            
            // Ambil pertanyaan untuk kuesioner umum
            $questions = $generalQuestionnaire->questions()
                ->orderBy('order')
                ->get();
        }
        
        return view('admin.views.questionnaire.general_questionnaires', compact(
            'categories',
            'selectedCategory',
            'generalQuestionnaire',
            'questions'
        ));
    }
    
    /**
     * Update kuesioner umum
     */
    public function updateGeneralQuestionnaire(Request $request, $id)
    {
        $questionnaire = Questionnaire::findOrFail($id);
        
        // Pastikan ini adalah kuesioner umum
        if (!$questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Hanya kuesioner umum yang dapat diedit dari sini.');
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:questionnaires,slug,' . $id . ',id,category_id,' . $questionnaire->category_id,
            'description' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $data = $request->only(['name', 'slug', 'description']);
        $data['is_required'] = $request->filled('is_required');
        
        try {
            $questionnaire->update($data);
            
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $questionnaire->category_id])
                ->with('success', 'Kuesioner umum berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui kuesioner umum: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Simpan pertanyaan untuk kuesioner umum
     */
    public function storeGeneralQuestion(Request $request, $questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        // Pastikan ini adalah kuesioner umum
        if (!$questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Hanya dapat menambahkan pertanyaan ke kuesioner umum.');
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'scale_information' => 'nullable|string',
            'is_required' => 'sometimes',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes',
            'has_none_option' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi khusus untuk likert_per_row
        if ($request->question_type === 'likert_per_row') {
            if (!$request->filled('scale_options')) {
                return redirect()->back()
                    ->with('error', 'Opsi skala wajib diisi untuk tipe Likert per Baris.')
                    ->withInput();
            }

            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            if (count($scaleOptions) > 5) {
                return redirect()->back()
                    ->with('error', 'Opsi skala maksimal 5 untuk tipe Likert per Baris.')
                    ->withInput();
            }

            // Validasi bahwa opsi skala harus angka 1-5
            foreach ($scaleOptions as $option) {
                if (!is_numeric($option) || $option < 1 || $option > 5) {
                    return redirect()->back()
                        ->with('error', 'Opsi skala harus berupa angka antara 1 sampai 5.')
                        ->withInput();
                }
            }
        }
        
        // Parse options dari textarea ke array
        $options = null;
        if ($request->filled('options')) {
            $options = array_filter(array_map('trim', explode("\n", $request->options)));
            $options = !empty($options) ? json_encode($options) : null;
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
            $rowItems = !empty($rowItems) ? json_encode($rowItems) : null;
        }
        
        // Parse scale_options
        $scaleOptions = null;
        if ($request->filled('scale_options')) {
            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            $scaleOptions = !empty($scaleOptions) ? json_encode($scaleOptions) : null;
        }

        // Parse scale_information (keterangan untuk setiap opsi skala)
        $scaleInformation = null;
        if ($request->filled('scale_information')) {
            $scaleInformation = [];
            $lines = array_filter(array_map('trim', explode("\n", $request->scale_information)));
            foreach ($lines as $line) {
                if (strpos($line, '|') !== false) {
                    list($key, $value) = explode('|', $line, 2);
                    $scaleInformation[trim($key)] = trim($value);
                } else {
                    $scaleInformation[$line] = $line;
                }
            }
            $scaleInformation = !empty($scaleInformation) ? json_encode($scaleInformation) : null;
        }
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text', 'scale_label_low', 'scale_label_high'
        ]);
        
        $data['questionnaire_id'] = $questionnaireId;
        $data['is_required'] = $request->filled('is_required');
        $data['has_other_option'] = $request->filled('has_other_option');
        $data['has_none_option'] = $request->filled('has_none_option');
        $data['order'] = $data['order'] ?? $questionnaire->questions()->count() + 1;
        $data['points'] = $data['points'] ?? 0;
        
        if ($options !== null) $data['options'] = $options;
        if ($rowItems !== null) $data['row_items'] = $rowItems;
        if ($scaleOptions !== null) $data['scale_options'] = $scaleOptions;
        if ($scaleInformation !== null) $data['scale_information'] = $scaleInformation;
        
        try {
            Question::create($data);
            
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $questionnaire->category_id])
                ->with('success', 'Pertanyaan berhasil ditambahkan ke kuesioner umum.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambahkan pertanyaan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Update pertanyaan untuk kuesioner umum
     */
    public function updateGeneralQuestion(Request $request, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        $questionnaire = $question->questionnaire;
        
        // Pastikan ini adalah kuesioner umum
        if (!$questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Hanya dapat mengedit pertanyaan di kuesioner umum.');
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'scale_information' => 'nullable|string',
            'is_required' => 'sometimes',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes',
            'has_none_option' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi khusus untuk likert_per_row
        if ($request->question_type === 'likert_per_row') {
            if (!$request->filled('scale_options')) {
                return redirect()->back()
                    ->with('error', 'Opsi skala wajib diisi untuk tipe Likert per Baris.')
                    ->withInput();
            }

            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            if (count($scaleOptions) > 5) {
                return redirect()->back()
                    ->with('error', 'Opsi skala maksimal 5 untuk tipe Likert per Baris.')
                    ->withInput();
            }

            // Validasi bahwa opsi skala harus angka 1-5
            foreach ($scaleOptions as $option) {
                if (!is_numeric($option) || $option < 1 || $option > 5) {
                    return redirect()->back()
                        ->with('error', 'Opsi skala harus berupa angka antara 1 sampai 5.')
                        ->withInput();
                }
            }
        }
        
        // Parse options dari textarea ke array
        $options = null;
        if ($request->filled('options')) {
            $options = array_filter(array_map('trim', explode("\n", $request->options)));
            $options = !empty($options) ? json_encode($options) : null;
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
            $rowItems = !empty($rowItems) ? json_encode($rowItems) : null;
        }
        
        // Parse scale_options
        $scaleOptions = null;
        if ($request->filled('scale_options')) {
            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            $scaleOptions = !empty($scaleOptions) ? json_encode($scaleOptions) : null;
        }

        // Parse scale_information (keterangan untuk setiap opsi skala)
        $scaleInformation = null;
        if ($request->filled('scale_information')) {
            $scaleInformation = [];
            $lines = array_filter(array_map('trim', explode("\n", $request->scale_information)));
            foreach ($lines as $line) {
                if (strpos($line, '|') !== false) {
                    list($key, $value) = explode('|', $line, 2);
                    $scaleInformation[trim($key)] = trim($value);
                } else {
                    $scaleInformation[$line] = $line;
                }
            }
            $scaleInformation = !empty($scaleInformation) ? json_encode($scaleInformation) : null;
        }
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text', 'scale_label_low', 'scale_label_high'
        ]);
        
        $data['is_required'] = $request->filled('is_required');
        $data['has_other_option'] = $request->filled('has_other_option');
        $data['has_none_option'] = $request->filled('has_none_option');
        
        if ($options !== null) {
            $data['options'] = $options;
        } elseif ($request->has('options')) {
            $data['options'] = null;
        }
        
        if ($rowItems !== null) {
            $data['row_items'] = $rowItems;
        } elseif ($request->has('row_items')) {
            $data['row_items'] = null;
        }
        
        if ($scaleOptions !== null) {
            $data['scale_options'] = $scaleOptions;
        } elseif ($request->has('scale_options')) {
            $data['scale_options'] = null;
        }

        if ($scaleInformation !== null) {
            $data['scale_information'] = $scaleInformation;
        } elseif ($request->has('scale_information')) {
            $data['scale_information'] = null;
        }
        
        try {
            $question->update($data);
            
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $questionnaire->category_id])
                ->with('success', 'Pertanyaan berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui pertanyaan: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Hapus pertanyaan kuesioner umum (select delete)
     */
    public function destroyGeneralQuestion(Request $request, $questionnaireId)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:questions,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->ids as $id) {
                $question = Question::findOrFail($id);
                
                // Hapus answers
                AnswerQuestion::where('question_id', $id)->delete();
                QuestionAnswer::whereHas('answerQuestion', function($q) use ($id) {
                    $q->where('question_id', $id);
                })->delete();
                
                // Hapus pertanyaan
                $question->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' pertanyaan berhasil dihapus.',
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pertanyaan: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Tampilkan pertanyaan untuk kuesioner tertentu (non-general only)
     */
    public function questions(Request $request, $categoryId, $questionnaireId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $categoryId])
                ->with('error', 'Kuesioner umum dikelola di halaman khusus.');
        }
        
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
     * Simpan pertanyaan baru (non-general only)
     */
    public function storeQuestion(Request $request, $categoryId, $questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menambahkan pertanyaan ke kuesioner umum dari sini.');
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'scale_information' => 'nullable|string',
            'is_required' => 'sometimes',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes',
            'has_none_option' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi khusus untuk likert_per_row
        if ($request->question_type === 'likert_per_row') {
            if (!$request->filled('scale_options')) {
                return redirect()->back()
                    ->with('error', 'Opsi skala wajib diisi untuk tipe Likert per Baris.')
                    ->withInput();
            }

            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            if (count($scaleOptions) > 5) {
                return redirect()->back()
                    ->with('error', 'Opsi skala maksimal 5 untuk tipe Likert per Baris.')
                    ->withInput();
            }

            // Validasi bahwa opsi skala harus angka 1-5
            foreach ($scaleOptions as $option) {
                if (!is_numeric($option) || $option < 1 || $option > 5) {
                    return redirect()->back()
                        ->with('error', 'Opsi skala harus berupa angka antara 1 sampai 5.')
                        ->withInput();
                }
            }
        }
        
        // Parse options dari textarea ke array
        $options = $this->formatOptions($request->options);
        $rowItems = $this->formatRowItems($request->row_items);
        $scaleOptions = $this->formatScaleOptions($request->scale_options);
        $scaleInformation = $this->formatScaleInformation($request->scale_information);
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text', 'scale_label_low', 'scale_label_high'
        ]);
        
        $data['questionnaire_id'] = $questionnaireId;
        $data['is_required'] = $request->filled('is_required');
        $data['has_other_option'] = $request->filled('has_other_option');
        $data['has_none_option'] = $request->filled('has_none_option');
        $data['order'] = $data['order'] ?? $questionnaire->questions()->count() + 1;
        $data['points'] = $data['points'] ?? 0;
        
        if ($options !== null) $data['options'] = $options;
        if ($rowItems !== null) $data['row_items'] = $rowItems;
        if ($scaleOptions !== null) $data['scale_options'] = $scaleOptions;
        if ($scaleInformation !== null) $data['scale_information'] = $scaleInformation;
        
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
     * Update pertanyaan (non-general only)
     */
    public function updateQuestion(Request $request, $categoryId, $questionnaireId, $id)
    {
        $question = Question::findOrFail($id);
        $questionnaire = $question->questionnaire;
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Tidak dapat mengedit pertanyaan kuesioner umum dari sini.');
        }
        
        $validator = Validator::make($request->all(), [
            'question_text' => 'required|string',
            'question_type' => 'required|in:radio,dropdown,text,textarea,date,number,checkbox,likert_per_row',
            'description' => 'nullable|string',
            'options' => 'nullable|string',
            'row_items' => 'nullable|string',
            'scale_options' => 'nullable|string',
            'scale_information' => 'nullable|string',
            'is_required' => 'sometimes',
            'order' => 'nullable|integer',
            'points' => 'nullable|integer|min:0',
            'placeholder' => 'nullable|string',
            'helper_text' => 'nullable|string',
            'has_other_option' => 'sometimes',
            'has_none_option' => 'sometimes',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi khusus untuk likert_per_row
        if ($request->question_type === 'likert_per_row') {
            if (!$request->filled('scale_options')) {
                return redirect()->back()
                    ->with('error', 'Opsi skala wajib diisi untuk tipe Likert per Baris.')
                    ->withInput();
            }

            $scaleOptions = array_filter(array_map('trim', explode(',', $request->scale_options)));
            if (count($scaleOptions) > 5) {
                return redirect()->back()
                    ->with('error', 'Opsi skala maksimal 5 untuk tipe Likert per Baris.')
                    ->withInput();
            }

            // Validasi bahwa opsi skala harus angka 1-5
            foreach ($scaleOptions as $option) {
                if (!is_numeric($option) || $option < 1 || $option > 5) {
                    return redirect()->back()
                        ->with('error', 'Opsi skala harus berupa angka antara 1 sampai 5.')
                        ->withInput();
                }
            }
        }
        
        // Parse options dari textarea ke array
        $options = $this->formatOptions($request->options);
        $rowItems = $this->formatRowItems($request->row_items);
        $scaleOptions = $this->formatScaleOptions($request->scale_options);
        $scaleInformation = $this->formatScaleInformation($request->scale_information);
        
        $data = $request->only([
            'question_text', 'question_type', 'description', 'order', 'points',
            'placeholder', 'helper_text', 'scale_label_low', 'scale_label_high'
        ]);
        
        $data['is_required'] = $request->filled('is_required');
        $data['has_other_option'] = $request->filled('has_other_option');
        $data['has_none_option'] = $request->filled('has_none_option');
        
        if ($options !== null) {
            $data['options'] = $options;
        } elseif ($request->has('options')) {
            $data['options'] = null;
        }
        
        if ($rowItems !== null) {
            $data['row_items'] = $rowItems;
        } elseif ($request->has('row_items')) {
            $data['row_items'] = null;
        }
        
        if ($scaleOptions !== null) {
            $data['scale_options'] = $scaleOptions;
        } elseif ($request->has('scale_options')) {
            $data['scale_options'] = null;
        }

        if ($scaleInformation !== null) {
            $data['scale_information'] = $scaleInformation;
        } elseif ($request->has('scale_information')) {
            $data['scale_information'] = null;
        }
        
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
     * Format options from textarea to array
     */
    private function formatOptions($optionsText)
    {
        if (empty($optionsText)) {
            return null;
        }
        
        $lines = array_filter(array_map('trim', explode("\n", $optionsText)));
        $formattedOptions = [];
        
        foreach ($lines as $line) {
            // Skip empty lines
            if (empty($line)) continue;
            
            // Handle format dengan pipe: value|text
            if (strpos($line, '|') !== false) {
                $parts = explode('|', $line, 2);
                $value = trim($parts[0]);
                $text = trim($parts[1] ?? $parts[0]);
                
                // Jika value dan text sama, simpan sebagai string
                // Jika berbeda, simpan sebagai array
                if ($value === $text) {
                    $formattedOptions[] = $text;
                } else {
                    $formattedOptions[] = ['text' => $text];
                }
            } else {
                // Format sederhana
                $formattedOptions[] = $line;
            }
        }
        
        return !empty($formattedOptions) ? json_encode($formattedOptions) : null;
    }

    /**
     * Format row items from textarea to array
     */
    private function formatRowItems($rowItemsText)
    {
        if (empty($rowItemsText)) {
            return null;
        }
        
        $lines = array_filter(array_map('trim', explode("\n", $rowItemsText)));
        $rowItems = [];
        
        foreach ($lines as $line) {
            if (strpos($line, '|') !== false) {
                $parts = explode('|', $line, 2);
                $key = trim($parts[0]);
                $value = trim($parts[1] ?? $parts[0]);
                $rowItems[$key] = $value;
            } else {
                $rowItems[$line] = $line;
            }
        }
        
        return !empty($rowItems) ? json_encode($rowItems) : null;
    }

    /**
     * Format scale options from textarea to array
     */
    private function formatScaleOptions($scaleOptionsText)
    {
        if (empty($scaleOptionsText)) {
            return null;
        }
        
        $options = array_filter(array_map('trim', explode(',', $scaleOptionsText)));
        return !empty($options) ? json_encode($options) : null;
    }

    /**
     * Format scale information from textarea to array
     */
    private function formatScaleInformation($scaleInformationText)
    {
        if (empty($scaleInformationText)) {
            return null;
        }
        
        $lines = array_filter(array_map('trim', explode("\n", $scaleInformationText)));
        $scaleInformation = [];
        
        foreach ($lines as $line) {
            if (strpos($line, '|') !== false) {
                $parts = explode('|', $line, 2);
                $key = trim($parts[0]);
                $value = trim($parts[1] ?? $parts[0]);
                $scaleInformation[$key] = $value;
            } else {
                $scaleInformation[$line] = $line;
            }
        }
        
        return !empty($scaleInformation) ? json_encode($scaleInformation) : null;
    }
    
    /**
     * Hapus pertanyaan (select delete) - non-general only
     */
    public function destroyQuestion(Request $request, $categoryId, $questionnaireId)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:questions,id',
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->ids as $id) {
                $question = Question::findOrFail($id);
                
                // Hapus answers
                AnswerQuestion::where('question_id', $id)->delete();
                QuestionAnswer::whereHas('answerQuestion', function($q) use ($id) {
                    $q->where('question_id', $id);
                })->delete();
                
                // Hapus pertanyaan
                $question->delete();
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => count($request->ids) . ' pertanyaan berhasil dihapus.',
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pertanyaan: ' . $e->getMessage(),
            ], 500);
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
                    $questionnaire = Questionnaire::find($item['id']);
                    // Skip kuesioner umum
                    if ($questionnaire && !$questionnaire->is_general) {
                        Questionnaire::where('id', $item['id'])
                            ->where('category_id', $categoryId)
                            ->update(['order' => $item['order']]);
                        
                        QuestionnaireSequence::where('questionnaire_id', $item['id'])
                            ->where('category_id', $categoryId)
                            ->update(['order' => $item['order'] + 1]); // +1 karena umum di order 1
                    }
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
     * Hapus kategori (select delete) - method alias
     */
    public function deleteSelectedCategories(Request $request)
    {
        return $this->destroyCategory($request);
    }

    /**
     * Hapus kuesioner (select delete) - method alias
     */
    public function deleteSelectedQuestionnaires(Request $request, $categoryId)
    {
        return $this->destroyQuestionnaire($request, $categoryId);
    }

    /**
     * Hapus pertanyaan (select delete) - method alias
     */
    public function deleteSelectedQuestions(Request $request, $categoryId, $questionnaireId)
    {
        return $this->destroyQuestion($request, $categoryId, $questionnaireId);
    }

    /**
     * Hapus pertanyaan kuesioner umum (select delete) - method alias
     */
    public function deleteSelectedGeneralQuestions(Request $request, $questionnaireId)
    {
        return $this->destroyGeneralQuestion($request, $questionnaireId);
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

    /**
     * Tampilkan form create pertanyaan (non-general)
     */
    public function createQuestion($categoryId, $questionnaireId)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $categoryId])
                ->with('error', 'Kuesioner umum dikelola di halaman khusus.');
        }
        
        $questions = $questionnaire->questions()->orderBy('order')->get();
        
        return view('admin.views.questionnaire.question_form', compact(
            'category',
            'questionnaire',
            'questions'
        ));
    }

    /**
     * Tampilkan form edit pertanyaan (non-general)
     */
    public function editQuestion($categoryId, $questionnaireId, $id)
    {
        $category = Category::findOrFail($categoryId);
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        $question = Question::findOrFail($id);
        
        // Pastikan bukan kuesioner umum
        if ($questionnaire->is_general) {
            return redirect()->route('admin.questionnaire.general-questionnaires', ['category_id' => $categoryId])
                ->with('error', 'Kuesioner umum dikelola di halaman khusus.');
        }
        
        $questions = $questionnaire->questions()->orderBy('order')->get();
        
        return view('admin.views.questionnaire.question_form', compact(
            'category',
            'questionnaire',
            'question',
            'questions'
        ));
    }

    /**
     * Tampilkan form create pertanyaan (general)
     */
    public function createGeneralQuestion($questionnaireId)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        // Pastikan ini adalah kuesioner umum
        if (!$questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Hanya dapat menambahkan pertanyaan ke kuesioner umum.');
        }
        
        $selectedCategory = $questionnaire->category;
        $questions = $questionnaire->questions()->orderBy('order')->get();
        
        return view('admin.views.questionnaire.general_question_form', compact(
            'questionnaire',
            'selectedCategory',
            'questions'
        ));
    }

    /**
     * Tampilkan form edit pertanyaan (general)
     */
    public function editGeneralQuestion($questionnaireId, $id)
    {
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        $question = Question::findOrFail($id);
        
        // Pastikan ini adalah kuesioner umum
        if (!$questionnaire->is_general) {
            return redirect()->back()
                ->with('error', 'Hanya dapat mengedit pertanyaan di kuesioner umum.');
        }
        
        $selectedCategory = $questionnaire->category;
        $questions = $questionnaire->questions()->orderBy('order')->get();
        
        return view('admin.views.questionnaire.general_question_form', compact(
            'questionnaire',
            'selectedCategory',
            'question',
            'questions'
        ));
    }
}