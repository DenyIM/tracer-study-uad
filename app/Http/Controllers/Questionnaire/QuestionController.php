<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\AnswerQuestion;
use App\Models\Questionnaire;
use App\Models\Category;
use App\Models\StatusQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    /**
     * Tampilkan pertanyaan tertentu
     */
    public function show($questionId)
    {
        $alumni = Auth::user()->alumni;
        $question = Question::with(['questionnaire.category'])->findOrFail($questionId);
        
        // Validasi apakah alumni boleh mengakses pertanyaan ini
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $question->questionnaire->category_id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.categories')
                ->with('error', 'Anda belum memilih kategori ini.');
        }
        
        // Ambil jawaban jika ada
        $answer = AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('question_id', $questionId)
            ->first();
        
        // Ambil pertanyaan sebelumnya dan berikutnya
        $prevQuestion = Question::where('questionnaire_id', $question->questionnaire_id)
            ->where('order', '<', $question->order)
            ->orderBy('order', 'desc')
            ->first();
        
        $nextQuestion = Question::where('questionnaire_id', $question->questionnaire_id)
            ->where('order', '>', $question->order)
            ->orderBy('order')
            ->first();
        
        return view('questionnaire.question.show', compact(
            'question',
            'answer',
            'prevQuestion',
            'nextQuestion',
            'statusQuestionnaire'
        ));
    }
    
    /**
     * Simpan jawaban untuk pertanyaan (API endpoint)
     */
    public function storeAnswer(Request $request, $questionId)
    {
        $question = Question::findOrFail($questionId);
        
        // Buat aturan validasi dinamis
        $rules = [
            'answer' => $question->getValidationRules(),
        ];
        
        $validator = Validator::make($request->all(), $rules, $question->getValidationMessages());
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $alumni = Auth::user()->alumni;
        $question = Question::with(['questionnaire.category'])->findOrFail($questionId);
        
        // Validasi khusus untuk likert_per_row
        if ($question->question_type === 'likert_per_row' && $question->is_required) {
            if ($request->has('scale_value')) {
                $answerValues = $request->scale_value;
                if (is_array($answerValues)) {
                    $rowItems = $question->row_items;
                    if (is_string($rowItems)) {
                        $rowItems = json_decode($rowItems, true) ?? [];
                    }
                    
                    // Cek apakah semua baris terisi
                    foreach ($rowItems as $key => $item) {
                        if (!isset($answerValues[$key]) || empty($answerValues[$key])) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Harap isi semua skala untuk pertanyaan ini.'
                            ], 422);
                        }
                    }
                }
            } elseif (!$request->boolean('is_skipped', false)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Harap isi semua skala untuk pertanyaan ini.'
                ], 422);
            }
        }
        
        // Validasi apakah alumni boleh menjawab pertanyaan ini
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $question->questionnaire->category_id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memilih kategori ini.'
            ], 403);
        }
        
        // Validasi pertanyaan terkunci
        if ($question->is_locked_by_default) {
            // Cek apakah pertanyaan sebelumnya sudah dijawab
            $prevQuestion = Question::where('questionnaire_id', $question->questionnaire_id)
                ->where('order', '<', $question->order)
                ->orderBy('order', 'desc')
                ->first();
            
            if ($prevQuestion) {
                $prevAnswer = AnswerQuestion::where('alumni_id', $alumni->id)
                    ->where('question_id', $prevQuestion->id)
                    ->where('is_skipped', false)
                    ->first();
                
                if (!$prevAnswer) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Harap jawab pertanyaan sebelumnya terlebih dahulu.'
                    ], 403);
                }
            }
        }
        
        // Validasi required question
        if ($question->is_required && $request->boolean('is_skipped', false)) {
            return response()->json([
                'success' => false,
                'message' => 'Pertanyaan ini wajib diisi.'
            ], 422);
        }
        
        // Proses jawaban berdasarkan tipe
        $answerData = $this->processAnswer($request, $question);
        
        // Validasi jika jawaban kosong untuk required question
        if ($question->is_required && $this->isAnswerEmpty($answerData, $question)) {
            return response()->json([
                'success' => false,
                'message' => 'Harap isi jawaban untuk pertanyaan ini.'
            ], 422);
        }
        
        // Simpan atau update jawaban
        $answer = AnswerQuestion::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'question_id' => $questionId,
            ],
            array_merge($answerData, [
                'is_skipped' => $request->boolean('is_skipped', false),
                'answered_at' => now(),
            ])
        );
        
        // Update progress
        $this->updateQuestionnaireProgress($alumni, $question->questionnaire);
        
        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan.',
            'data' => [
                'answer' => $answer,
                'question' => $question,
                'points_earned' => $question->points,
            ],
        ]);
    }
    
    /**
     * Skip pertanyaan
     */
    public function skipQuestion(Request $request, $questionId)
    {
        $alumni = Auth::user()->alumni;
        $question = Question::findOrFail($questionId);
        
        // Cek apakah pertanyaan required
        if ($question->is_required) {
            return response()->json([
                'success' => false,
                'message' => 'Pertanyaan wajib tidak bisa dilewati.'
            ], 422);
        }
        
        // Simpan sebagai skipped
        AnswerQuestion::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'question_id' => $questionId,
            ],
            [
                'is_skipped' => true,
                'answered_at' => now(),
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Pertanyaan berhasil dilewati.',
        ]);
    }
    
    /**
     * Clear jawaban untuk pertanyaan tertentu
     */
    public function clearAnswer($questionId)
    {
        $alumni = Auth::user()->alumni;
        
        $answer = AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('question_id', $questionId)
            ->first();
        
        if ($answer) {
            $answer->delete();
            
            // Update progress
            $question = Question::with('questionnaire')->find($questionId);
            if ($question) {
                $this->updateQuestionnaireProgress($alumni, $question->questionnaire);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil dihapus.',
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Jawaban tidak ditemukan.',
        ]);
    }
    
    /**
     * Get pertanyaan berikutnya
     */
    public function getNextQuestion($questionnaireId, $currentOrder)
    {
        $nextQuestion = Question::where('questionnaire_id', $questionnaireId)
            ->where('order', '>', $currentOrder)
            ->orderBy('order')
            ->first();
        
        if (!$nextQuestion) {
            // Cek apakah ada kuesioner berikutnya
            $questionnaire = Questionnaire::find($questionnaireId);
            $nextQuestionnaire = $questionnaire->nextQuestionnaire();
            
            if ($nextQuestionnaire) {
                $nextQuestion = $nextQuestionnaire->questions()
                    ->orderBy('order')
                    ->first();
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'next_question' => $nextQuestion,
                'has_next' => $nextQuestion !== null,
            ],
        ]);
    }
    
    /**
     * Get pertanyaan sebelumnya
     */
    public function getPrevQuestion($questionnaireId, $currentOrder)
    {
        $prevQuestion = Question::where('questionnaire_id', $questionnaireId)
            ->where('order', '<', $currentOrder)
            ->orderBy('order', 'desc')
            ->first();
        
        if (!$prevQuestion) {
            // Cek apakah ada kuesioner sebelumnya
            $questionnaire = Questionnaire::find($questionnaireId);
            $prevQuestionnaire = $questionnaire->previousQuestionnaire();
            
            if ($prevQuestionnaire) {
                $prevQuestion = $prevQuestionnaire->questions()
                    ->orderBy('order', 'desc')
                    ->first();
            }
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'prev_question' => $prevQuestion,
                'has_prev' => $prevQuestion !== null,
            ],
        ]);
    }
    
    /**
     * Validasi jawaban sebelum submit
     */
    public function validateAnswer(Request $request, $questionId)
    {
        $validator = Validator::make($request->all(), [
            'answer' => 'nullable',
            'selected_options' => 'nullable|array',
            'scale_value' => 'nullable|integer|min:1|max:5',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $question = Question::findOrFail($questionId);
        $answerData = $this->processAnswer($request, $question);
        
        // Validasi khusus berdasarkan tipe
        $validationErrors = $this->validateByQuestionType($request, $question);
        
        if (!empty($validationErrors)) {
            return response()->json([
                'success' => false,
                'errors' => $validationErrors
            ], 422);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Jawaban valid.',
            'data' => $answerData,
        ]);
    }
    
    /**
     * Process answer berdasarkan tipe pertanyaan
     */
    private function processAnswer(Request $request, Question $question): array
    {
        $answerData = [];
        
        switch ($question->question_type) {
            case 'text':
            case 'textarea':
            case 'date':
            case 'number':
                $answerData['answer'] = $request->answer;
                break;
                
            case 'dropdown':
                $answerValue = $request->answer;
                // Handle other option
                if ($question->has_other_option && 
                    ($answerValue === 'Lainnya, sebutkan!' || $answerValue === 'Lainnya') && 
                    $request->has('other_input')) {
                    $answerData['answer'] = 'Lainnya: ' . $request->other_input;
                } else {
                    $answerData['answer'] = $answerValue;
                }
                break;
                
            case 'radio':
            case 'radio_per_row':
                $answerValue = $request->answer;
                // Handle jawaban dengan input tambahan
                if (str_contains($answerValue, 'email') || 
                    (str_contains($answerValue, 'Ya,') && str_contains($answerValue, 'email'))) {
                    if ($request->has('email_input') && !empty($request->email_input)) {
                        $answerData['answer'] = $answerValue . ': ' . $request->email_input;
                    } else {
                        $answerData['answer'] = $answerValue;
                    }
                } elseif (str_contains($answerValue, 'WhatsApp') || 
                        str_contains($answerValue, 'nomor WhatsApp') ||
                        str_contains($answerValue, 'nomor WA')) {
                    if ($request->has('whatsapp_input') && !empty($request->whatsapp_input)) {
                        // FIXED: Pastikan format konsisten
                        $answerData['answer'] = $answerValue . ': ' . $request->whatsapp_input;
                    } else {
                        $answerData['answer'] = $answerValue;
                    }
                } elseif (($answerValue === 'Lainnya, sebutkan!' || $answerValue === 'Lainnya') && 
                        $request->has('other_input')) {
                    $answerData['answer'] = 'Lainnya: ' . $request->other_input;
                } else {
                    $answerData['answer'] = $answerValue;
                }
                break;
                
            case 'checkbox':
            case 'checkbox_per_row':
                $selectedOptions = $request->selected_options ?? [];
                $processedOptions = [];
                
                foreach ($selectedOptions as $option) {
                    // Handle other option
                    if (($option === 'Lainnya' || $option === 'Lainnya, sebutkan!') && 
                        $request->has("other_input")) {
                        $processedOptions[] = 'Lainnya: ' . $request->other_input;
                    }
                    // Handle email input
                    elseif ((str_contains($option, 'email') || 
                            (str_contains($option, 'Ya,') && str_contains($option, 'email'))) && 
                        $request->has("email_input")) {
                        $processedOptions[] = $option . ': ' . $request->email_input;
                    }
                    // Handle WhatsApp input - FIXED: format yang benar
                    elseif (str_contains($option, 'WhatsApp') || 
                        str_contains($option, 'nomor WhatsApp') ||
                        str_contains($option, 'nomor WA')) {
                        if ($request->has("whatsapp_input")) {
                            $processedOptions[] = $option . ': ' . $request->whatsapp_input;
                        } else {
                            $processedOptions[] = $option;
                        }
                    }
                    else {
                        $processedOptions[] = $option;
                    }
                }
                
                $answerData['selected_options'] = $processedOptions;
                break;
                
            case 'likert_scale':
            case 'competency_scale':
            case 'likert_per_row':
                $answerData['answer'] = json_encode($request->scale_value ?? []);
                break;
                
            default:
                $answerData['answer'] = $request->answer;
                break;
        }
        
        return $answerData;
    }
    
    /**
     * Validasi berdasarkan tipe pertanyaan
     */
    private function validateByQuestionType(Request $request, Question $question): array
    {
        $errors = [];
        
        switch ($question->question_type) {
            case 'number':
                if ($request->answer && !is_numeric($request->answer)) {
                    $errors['answer'] = ['Harap masukkan angka yang valid.'];
                }
                if ($question->min_value !== null && $request->answer < $question->min_value) {
                    $errors['answer'] = ['Nilai minimum adalah ' . $question->min_value . '.'];
                }
                if ($question->max_value !== null && $request->answer > $question->max_value) {
                    $errors['answer'] = ['Nilai maksimum adalah ' . $question->max_value . '.'];
                }
                break;
                
            case 'date':
                if ($request->answer && !strtotime($request->answer)) {
                    $errors['answer'] = ['Harap masukkan tanggal yang valid.'];
                }
                break;
                
            case 'checkbox':
                if ($question->max_selections && count($request->selected_options ?? []) > $question->max_selections) {
                    $errors['selected_options'] = ['Maksimal pilihan adalah ' . $question->max_selections . '.'];
                }
                break;
                
            case 'likert_scale':
            case 'competency_scale':
            case 'likert_per_row':
                // Untuk seeder, simpan sebagai JSON string
                if ($request->has('scale_value') && is_array($request->scale_value)) {
                    $answerData['answer'] = json_encode($request->scale_value);
                } else {
                    $answerData['answer'] = null;
                }
                break;
        }
        
        return $errors;
    }
    
    /**
     * Cek apakah jawaban kosong
     */
    private function isAnswerEmpty(array $answerData, Question $question): bool
    {
        if (isset($answerData['answer'])) {
            return empty(trim($answerData['answer']));
        }
        
        if (isset($answerData['selected_options'])) {
            return empty($answerData['selected_options']);
        }
        
        if (isset($answerData['scale_value'])) {
            return $answerData['scale_value'] === null;
        }
        
        return true;
    }
    
    // Di method storeAnswer(), setelah menyimpan jawaban:
    private function updateQuestionnaireProgress($alumni, $questionnaire): void
    {
        // Update questionnaire progress
        $totalQuestions = $questionnaire->questions()->count();
        $answeredQuestions = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereIn('question_id', $questionnaire->questions()->pluck('id'))
            ->where('is_skipped', false)
            ->count();
        
        // HITUNG TOTAL POINTS DARI ANSWER_QUESTIONS
        $totalPoints = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereIn('question_id', $questionnaire->questions()->pluck('id'))
            ->where('is_skipped', false)
            ->sum('points');
        
        $progressPercentage = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
        
        // Update questionnaire progress
        \App\Models\QuestionnaireProgress::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                'answered_count' => $answeredQuestions,
                'total_questions' => $totalQuestions,
                'progress_percentage' => $progressPercentage,
                'status' => $progressPercentage >= 100 ? 'completed' : 
                        ($progressPercentage > 0 ? 'in_progress' : 'not_started'),
            ]
        );
        
        // Update overall status dengan points
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $questionnaire->category_id)
            ->first();
        
        if ($statusQuestionnaire) {
            $totalCategoryQuestions = $questionnaire->category->total_questions;
            $totalAnsweredCategory = AnswerQuestion::where('alumni_id', $alumni->id)
                ->whereHas('question.questionnaire', function ($query) use ($questionnaire) {
                    $query->where('category_id', $questionnaire->category_id);
                })
                ->where('is_skipped', false)
                ->count();
            
            // HITUNG TOTAL POINTS UNTUK KATEGORI
            $totalCategoryPoints = AnswerQuestion::where('alumni_id', $alumni->id)
                ->whereHas('question.questionnaire', function ($query) use ($questionnaire) {
                    $query->where('category_id', $questionnaire->category_id);
                })
                ->where('is_skipped', false)
                ->sum('points');
            
            $categoryProgress = $totalCategoryQuestions > 0 ? 
                round(($totalAnsweredCategory / $totalCategoryQuestions) * 100) : 0;
            
            $statusQuestionnaire->update([
                'progress_percentage' => $categoryProgress,
                'total_points' => $totalCategoryPoints, // UPDATE TOTAL POINTS
                'status' => $categoryProgress >= 100 ? 'completed' : 
                        ($categoryProgress > 0 ? 'in_progress' : 'not_started'),
            ]);
        }
    }
    
    /**
     * Get detail pertanyaan untuk modal atau popup
     */
    public function getQuestionDetail($questionId)
    {
        $question = Question::with(['questionnaire.category'])->findOrFail($questionId);
        $alumni = Auth::user()->alumni;
        
        $answer = AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('question_id', $questionId)
            ->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'question' => $question,
                'answer' => $answer,
                'available_options' => $question->available_options,
                'scale_options' => $question->scale_options_with_labels,
                'row_items' => $question->formatted_row_items,
            ],
        ]);
    }

    private function processAnswerForSubmit(Request $request, Question $question)
    {
        switch ($question->question_type) {
            case 'likert_per_row':
                $answerValues = [];
                $rowItems = $question->row_items;
                if (is_string($rowItems)) {
                    $rowItems = json_decode($rowItems, true) ?? [];
                }
                
                foreach ($rowItems as $key => $item) {
                    if ($request->has("answers.{$question->id}.{$key}")) {
                        $answerValues[$key] = $request->input("answers.{$question->id}.{$key}");
                    }
                }
                return $answerValues;
                
            case 'checkbox':
            case 'checkbox_per_row':
                $selectedOptions = [];
                $answers = $request->input("answers.{$question->id}", []);
                foreach ($answers as $index => $value) {
                    $selectedOptions[] = $value;
                }
                return $selectedOptions;
                
            default:
                return $request->input("answers.{$question->id}");
        }
    }
}