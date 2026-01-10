<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\AnswerQuestion;
use App\Models\QuestionnaireProgress;
use App\Models\StatusQuestionnaire;
use App\Models\QuestionnaireSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    /**
     * Tampilkan halaman dashboard kuesioner (main-kuesioner)
     */
    public function dashboard()
    {
        $alumni = Auth::user()->alumni;
        
        // Ambil status kuesioner alumni
        $statusQuestionnaire = StatusQuestionnaire::with(['category', 'currentQuestionnaire'])
            ->where('alumni_id', $alumni->id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.categories');
        }
        
        // Ambil progress per bagian
        $progressRecords = QuestionnaireProgress::with('questionnaire')
            ->where('alumni_id', $alumni->id)
            ->whereHas('questionnaire', function ($query) use ($statusQuestionnaire) {
                $query->where('category_id', $statusQuestionnaire->category_id);
            })
            ->get();
        
        // Hitung total progress
        $totalAnswered = AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('is_skipped', false)
            ->whereHas('question.questionnaire', function ($query) use ($statusQuestionnaire) {
                $query->where('category_id', $statusQuestionnaire->category_id);
            })
            ->count();
        
        $totalQuestions = $statusQuestionnaire->category->total_questions;
        $progressPercentage = $totalQuestions > 0 ? round(($totalAnswered / $totalQuestions) * 100) : 0;
        
        // Update progress jika berbeda
        if ($statusQuestionnaire->progress_percentage != $progressPercentage) {
            $statusQuestionnaire->update([
                'progress_percentage' => $progressPercentage,
                'status' => $progressPercentage >= 100 ? 'completed' : 
                           ($progressPercentage > 0 ? 'in_progress' : 'not_started')
            ]);
        }
        
        // Ambil kategori lain untuk ditampilkan
        $otherCategories = Category::where('is_active', true)
            ->where('id', '!=', $statusQuestionnaire->category_id)
            ->orderBy('order')
            ->get();
        
        return view('questionnaire.dashboard.index', compact(
            'statusQuestionnaire',
            'progressRecords',
            'totalAnswered',
            'totalQuestions',
            'progressPercentage',
            'otherCategories'
        ));
    }
    
    /**
     * Tampilkan halaman untuk mengisi bagian tertentu
     */
    public function show($categorySlug, $questionnaireSlug = null)
    {
        $alumni = Auth::user()->alumni;
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Validasi apakah alumni boleh mengisi kategori ini
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $category->id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.categories')
                ->with('error', 'Anda belum memilih kategori ini.');
        }
        
        // Jika tidak ada questionnaireSlug, ambil bagian umum
        if (!$questionnaireSlug) {
            $questionnaire = $category->generalQuestionnaire;
            if (!$questionnaire) {
                return redirect()->route('questionnaire.dashboard')
                    ->with('error', 'Bagian umum tidak ditemukan.');
            }
        } else {
            $questionnaire = Questionnaire::where('category_id', $category->id)
                ->where('slug', $questionnaireSlug)
                ->firstOrFail();
        }
        
        // Validasi urutan pengerjaan menggunakan sequence
        if (!$this->validateQuestionnaireOrder($alumni, $category, $questionnaire)) {
            // Tentukan bagian yang harus diselesaikan terlebih dahulu
            $sequences = $category->sequences()->orderBy('order')->get();
            $currentSequence = $sequences->where('questionnaire_id', $questionnaire->id)->first();
            
            if ($currentSequence) {
                $prevSequence = $currentSequence->previous();
                if ($prevSequence) {
                    return redirect()->route('questionnaire.fill', [
                        'categorySlug' => $category->slug,
                        'questionnaireSlug' => $prevSequence->questionnaire->slug
                    ])->with('error', 'Harap selesaikan bagian sebelumnya terlebih dahulu.');
                }
            }
            
            return redirect()->route('questionnaire.dashboard')
                ->with('error', 'Harap selesaikan bagian sebelumnya terlebih dahulu.');
        }
        
        // Ambil semua pertanyaan untuk bagian ini
        $questions = $questionnaire->questions()
            ->orderBy('order')
            ->get();
        
        // Ambil jawaban yang sudah diisi
        $answers = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereIn('question_id', $questions->pluck('id'))
            ->get()
            ->keyBy('question_id');
        
        // Update atau buat progress record
        QuestionnaireProgress::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'questionnaire_id' => $questionnaire->id,
            ],
            [
                'status' => 'in_progress',
                'started_at' => now(),
            ]
        );
        
        // Update current questionnaire
        $statusQuestionnaire->update([
            'current_questionnaire_id' => $questionnaire->id,
            'status' => 'in_progress',
        ]);
        
        // Tentukan urutan sequence untuk navigasi
        $sequences = $category->sequences()->orderBy('order')->get();
        $currentSequence = $sequences->where('questionnaire_id', $questionnaire->id)->first();
        $nextSequence = $currentSequence ? $currentSequence->next() : null;
        $prevSequence = $currentSequence ? $currentSequence->previous() : null;
        
        return view('questionnaire.fill', compact(
            'category',
            'questionnaire',
            'questions',
            'answers',
            'statusQuestionnaire',
            'currentSequence',
            'nextSequence',
            'prevSequence'
        ));
    }
    
    /**
     * Simpan jawaban untuk pertanyaan tertentu
     */
    public function storeAnswer(Request $request, $questionId)
    {
        $request->validate([
            'answer' => 'nullable',
            'selected_options' => 'nullable|array',
            'scale_value' => 'nullable|integer|min:1|max:5',
            'is_skipped' => 'boolean',
        ]);
        
        $alumni = Auth::user()->alumni;
        $question = Question::findOrFail($questionId);
        
        // Validasi apakah alumni boleh mengisi pertanyaan ini
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $question->questionnaire->category_id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum memilih kategori ini.'
            ], 403);
        }
        
        // Proses jawaban
        $answerData = [
            'alumni_id' => $alumni->id,
            'question_id' => $questionId,
            'is_skipped' => $request->boolean('is_skipped', false),
            'answered_at' => now(),
        ];
        
        // Handle different answer types
        if ($question->question_type === 'textarea' || $question->question_type === 'text') {
            $answerData['answer'] = $request->answer;
        } elseif ($question->question_type === 'number') {
            $answerData['answer'] = $request->answer;
        } elseif ($question->question_type === 'date') {
            $answerData['answer'] = $request->answer;
        } elseif ($question->is_scale) {
            $answerData['scale_value'] = $request->scale_value;
        } elseif ($question->supports_multiple) {
            $answerData['selected_options'] = $request->selected_options;
        } else {
            $answerData['answer'] = $request->answer;
        }
        
        // Simpan atau update jawaban
        AnswerQuestion::updateOrCreate(
            [
                'alumni_id' => $alumni->id,
                'question_id' => $questionId,
            ],
            $answerData
        );
        
        // Update progress
        $this->updateProgress($alumni, $question->questionnaire);
        
        return response()->json([
            'success' => true,
            'message' => 'Jawaban berhasil disimpan.',
            'points' => $question->points,
        ]);
    }
    
    /**
     * Submit seluruh bagian kuesioner
     */
    public function submitQuestionnaire(Request $request, $questionnaireId)
        {
            $alumni = Auth::user()->alumni;
            $expectsJson = $request->expectsJson();

            $questionnaire = Questionnaire::with('category')->findOrFail($questionnaireId);

            // Cek status kategori
            $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
                ->where('category_id', $questionnaire->category_id)
                ->first();

            if (!$statusQuestionnaire) {
                if ($expectsJson) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda belum memilih kategori ini.'
                    ], 403);
                }

                return redirect()->route('questionnaire.categories')
                    ->with('error', 'Anda belum memilih kategori ini.');
            }

            // Validasi pertanyaan wajib
            $requiredQuestions = $questionnaire->requiredQuestions()->pluck('id');
            $answeredQuestions = AnswerQuestion::where('alumni_id', $alumni->id)
                ->whereIn('question_id', $requiredQuestions)
                ->where('is_skipped', false)
                ->count();

            if ($answeredQuestions < $requiredQuestions->count()) {
                if ($expectsJson) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Harap lengkapi semua pertanyaan wajib sebelum mengirim.'
                    ], 422);
                }

                return redirect()->back()
                    ->with('error', 'Harap lengkapi semua pertanyaan wajib sebelum mengirim.');
            }

            // Update progress questionnaire
            QuestionnaireProgress::updateOrCreate(
                [
                    'alumni_id' => $alumni->id,
                    'questionnaire_id' => $questionnaire->id,
                ],
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                    'progress_percentage' => 100,
                    'answered_count' => $answeredQuestions,
                    'total_questions' => $questionnaire->questions()->count(),
                ]
            );

            // Sequence logic
            $category = $questionnaire->category;
            $currentSequence = $category->sequences()
                ->where('questionnaire_id', $questionnaire->id)
                ->first();

            if ($currentSequence && $currentSequence->isLast()) {

                StatusQuestionnaire::where('alumni_id', $alumni->id)
                    ->where('category_id', $category->id)
                    ->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                        'progress_percentage' => 100,
                    ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Semua kuesioner telah diselesaikan.',
                    'redirect_url' => route('questionnaire.completed'),
                ]);
            }

            $nextSequence = $currentSequence?->next();

            if ($nextSequence) {
                $statusQuestionnaire->update([
                    'current_questionnaire_id' => $nextSequence->questionnaire_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Bagian berhasil disimpan.',
                    'redirect_url' => route('questionnaire.fill', [
                        'categorySlug' => $category->slug,
                        'questionnaireSlug' => $nextSequence->questionnaire->slug,
                    ]),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Kuesioner berhasil disimpan.',
                'redirect_url' => route('questionnaire.dashboard'),
            ]);
        }
    
    /**
     * Tampilkan halaman selesai
     */
    public function completed()
    {
        $alumni = Auth::user()->alumni;
        
        $statusQuestionnaire = StatusQuestionnaire::with('category')
            ->where('alumni_id', $alumni->id)
            ->where('status', 'completed')
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.dashboard');
        }
        
        // Hitung total points
        $totalPoints = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereHas('question.questionnaire', function ($query) use ($statusQuestionnaire) {
                $query->where('category_id', $statusQuestionnaire->category_id);
            })
            ->where('is_skipped', false)
            ->sum('points');
        
        // Update total points
        $statusQuestionnaire->update(['total_points' => $totalPoints]);
        
        return view('questionnaire.completed', compact(
            'statusQuestionnaire', 
            'totalPoints',
            'stats'
        ));
    }
    
    /**
     * Validasi urutan pengerjaan kuesioner
     */
    private function validateQuestionnaireOrder($alumni, $category, $questionnaire)
    {
        // Jika bagian umum, selalu boleh
        if ($questionnaire->is_general) {
            return true;
        }
        
        // Ambil semua sequence untuk kategori ini
        $sequences = $category->sequences()->orderBy('order')->get();
        $currentSequence = $sequences->where('questionnaire_id', $questionnaire->id)->first();
        
        if (!$currentSequence) {
            return false;
        }
        
        // Cek apakah ini sequence pertama setelah umum (order = 2)
        if ($currentSequence->order == 2) {
            // Cek apakah bagian umum sudah selesai
            $generalQuestionnaire = $category->generalQuestionnaire;
            if (!$generalQuestionnaire) {
                return false;
            }
            
            $generalProgress = QuestionnaireProgress::where('alumni_id', $alumni->id)
                ->where('questionnaire_id', $generalQuestionnaire->id)
                ->where('status', 'completed')
                ->exists();
            
            return $generalProgress;
        }
        
        // Cek apakah sequence sebelumnya sudah selesai
        $prevSequence = $currentSequence->previous();
        if ($prevSequence) {
            $prevProgress = QuestionnaireProgress::where('alumni_id', $alumni->id)
                ->where('questionnaire_id', $prevSequence->questionnaire_id)
                ->where('status', 'completed')
                ->exists();
            
            return $prevProgress;
        }
        
        return false;
    }
    
    /**
     * Update progress kuesioner
     */
    private function updateProgress($alumni, $questionnaire)
    {
        // Update questionnaire progress
        $totalQuestions = $questionnaire->questions()->count();
        $answeredQuestions = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereIn('question_id', $questionnaire->questions()->pluck('id'))
            ->where('is_skipped', false)
            ->count();
        
        $progressPercentage = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
        
        QuestionnaireProgress::updateOrCreate(
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
        
        // Update overall status
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
            
            $categoryProgress = $totalCategoryQuestions > 0 ? 
                round(($totalAnsweredCategory / $totalCategoryQuestions) * 100) : 0;
            
            $statusQuestionnaire->update([
                'progress_percentage' => $categoryProgress,
                'status' => $categoryProgress >= 100 ? 'completed' : 
                           ($categoryProgress > 0 ? 'in_progress' : 'not_started'),
            ]);
        }
    }
}