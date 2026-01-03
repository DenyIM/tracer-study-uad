<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\QuestionnaireProgress;
use App\Models\StatusQuestionnaire;
use App\Models\AnswerQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    /**
     * Tampilkan progress kuesioner
     */
    public function index()
    {
        $alumni = Auth::user()->alumni;
        
        $statusQuestionnaires = StatusQuestionnaire::with(['category', 'currentQuestionnaire'])
            ->where('alumni_id', $alumni->id)
            ->get();
        
        // Jika belum ada, redirect ke pemilihan kategori
        if ($statusQuestionnaires->isEmpty()) {
            return redirect()->route('questionnaire.categories');
        }
        
        // Ambil progress detail untuk setiap kategori
        $progressDetails = [];
        foreach ($statusQuestionnaires as $status) {
            $progressDetails[$status->category_id] = [
                'status' => $status,
                'progress' => QuestionnaireProgress::with('questionnaire')
                    ->where('alumni_id', $alumni->id)
                    ->whereHas('questionnaire', function ($query) use ($status) {
                        $query->where('category_id', $status->category_id);
                    })
                    ->get(),
                'total_questions' => $status->category->total_questions,
                'answered_questions' => AnswerQuestion::where('alumni_id', $alumni->id)
                    ->whereHas('question.questionnaire', function ($query) use ($status) {
                        $query->where('category_id', $status->category_id);
                    })
                    ->where('is_skipped', false)
                    ->count(),
            ];
        }
        
        return view('questionnaire.progress.index', compact('statusQuestionnaires', 'progressDetails'));
    }
    
    /**
     * API: Get progress real-time
     */
    public function getProgress()
    {
        $alumni = Auth::user()->alumni;
        
        $statusQuestionnaire = StatusQuestionnaire::with('category')
            ->where('alumni_id', $alumni->id)
            ->where('status', 'in_progress')
            ->first();
        
        if (!$statusQuestionnaire) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada kuesioner yang sedang dikerjakan.',
            ]);
        }
        
        $progressRecords = QuestionnaireProgress::with('questionnaire')
            ->where('alumni_id', $alumni->id)
            ->whereHas('questionnaire', function ($query) use ($statusQuestionnaire) {
                $query->where('category_id', $statusQuestionnaire->category_id);
            })
            ->get();
        
        $currentQuestionnaire = $progressRecords->where('status', 'in_progress')->first();
        
        return response()->json([
            'success' => true,
            'data' => [
                'category' => $statusQuestionnaire->category,
                'overall_progress' => $statusQuestionnaire->progress_percentage,
                'status' => $statusQuestionnaire->status,
                'current_questionnaire' => $currentQuestionnaire ? $currentQuestionnaire->questionnaire : null,
                'progress_records' => $progressRecords,
            ],
        ]);
    }
    
    /**
     * API: Update progress (dipanggil dari frontend)
     */
    public function updateProgress(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'questionnaire_id' => 'nullable|exists:questionnaires,id',
        ]);
        
        $alumni = Auth::user()->alumni;
        
        // Update overall progress
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $request->category_id)
            ->first();
        
        if ($statusQuestionnaire) {
            $totalQuestions = $statusQuestionnaire->category->total_questions;
            $answeredQuestions = AnswerQuestion::where('alumni_id', $alumni->id)
                ->whereHas('question.questionnaire', function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                })
                ->where('is_skipped', false)
                ->count();
            
            $progressPercentage = $totalQuestions > 0 ? 
                round(($answeredQuestions / $totalQuestions) * 100) : 0;
            
            $statusQuestionnaire->update([
                'progress_percentage' => $progressPercentage,
                'current_questionnaire_id' => $request->questionnaire_id,
                'status' => $progressPercentage >= 100 ? 'completed' : 
                           ($progressPercentage > 0 ? 'in_progress' : 'not_started'),
            ]);
        }
        
        // Update questionnaire-specific progress
        if ($request->questionnaire_id) {
            $questionnaireProgress = QuestionnaireProgress::where('alumni_id', $alumni->id)
                ->where('questionnaire_id', $request->questionnaire_id)
                ->first();
            
            if ($questionnaireProgress) {
                $totalQuestions = $questionnaireProgress->questionnaire->questions()->count();
                $answeredQuestions = AnswerQuestion::where('alumni_id', $alumni->id)
                    ->whereIn('question_id', $questionnaireProgress->questionnaire->questions()->pluck('id'))
                    ->where('is_skipped', false)
                    ->count();
                
                $progressPercentage = $totalQuestions > 0 ? 
                    round(($answeredQuestions / $totalQuestions) * 100) : 0;
                
                $questionnaireProgress->update([
                    'answered_count' => $answeredQuestions,
                    'total_questions' => $totalQuestions,
                    'progress_percentage' => $progressPercentage,
                    'status' => $progressPercentage >= 100 ? 'completed' : 
                               ($progressPercentage > 0 ? 'in_progress' : 'not_started'),
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully.',
            'progress_percentage' => $progressPercentage ?? 0,
        ]);
    }
    
    /**
     * Reset progress untuk kategori tertentu
     */
    public function resetProgress($categoryId)
    {
        $alumni = Auth::user()->alumni;
        $category = Category::findOrFail($categoryId);
        
        // Validasi
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $categoryId)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->back()->with('error', 'Kategori tidak ditemukan.');
        }
        
        // Hanya bisa reset jika belum completed
        if ($statusQuestionnaire->status === 'completed') {
            return redirect()->back()
                ->with('error', 'Tidak bisa mereset kuesioner yang sudah selesai.');
        }
        
        // Hapus semua jawaban untuk kategori ini
        AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereHas('question.questionnaire', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->delete();
        
        // Hapus progress records
        QuestionnaireProgress::where('alumni_id', $alumni->id)
            ->whereHas('questionnaire', function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->delete();
        
        // Reset status
        $statusQuestionnaire->update([
            'status' => 'not_started',
            'progress_percentage' => 0,
            'current_questionnaire_id' => null,
            'started_at' => null,
        ]);
        
        return redirect()->route('questionnaire.dashboard')
            ->with('success', 'Progress untuk kategori ' . $category->name . ' berhasil direset.');
    }
}