<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\AnswerQuestion;
use App\Models\Question;
use App\Models\StatusQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnswerController extends Controller
{
    /**
     * Tampilkan hasil jawaban alumni
     */
    public function index()
    {
        $alumni = Auth::user()->alumni;
        
        $statusQuestionnaire = StatusQuestionnaire::with('category')
            ->where('alumni_id', $alumni->id)
            ->where('status', 'completed')
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.dashboard')
                ->with('info', 'Anda belum menyelesaikan kuesioner.');
        }
        
        return $this->showCategoryAnswers($statusQuestionnaire->category->slug);
    }
    
    /**
     * Tampilkan jawaban berdasarkan kategori
     */
    public function showCategoryAnswers($categorySlug)
    {
        $alumni = Auth::user()->alumni;
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Validasi apakah alumni mengisi kategori ini
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $category->id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.answers.index')
                ->with('error', 'Anda tidak mengisi kuesioner kategori ini.');
        }
        
        // Ambil semua jawaban untuk kategori ini
        $answers = AnswerQuestion::with(['question.questionnaire'])
            ->where('alumni_id', $alumni->id)
            ->whereHas('question.questionnaire', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_skipped', false)
            ->get()
            ->groupBy(function ($answer) {
                return $answer->question->questionnaire_id;
            });
        
        // Ambil semua bagian kuesioner untuk kategori ini
        $questionnaires = Questionnaire::where('category_id', $category->id)
            ->orderBy('order')
            ->get();
        
        return view('questionnaire.answers.index', compact(
            'category',
            'questionnaires',
            'answers',
            'statusQuestionnaire'
        ));
    }
    
    /**
     * Export jawaban ke PDF
     */
    public function exportPDF($categorySlug)
    {
        $alumni = Auth::user()->alumni;
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        
        // Validasi
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('category_id', $category->id)
            ->first();
        
        if (!$statusQuestionnaire) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        
        // Ambil data untuk PDF
        $questionnaires = Questionnaire::where('category_id', $category->id)
            ->orderBy('order')
            ->get();
        
        $answers = AnswerQuestion::with(['question'])
            ->where('alumni_id', $alumni->id)
            ->whereHas('question.questionnaire', function ($query) use ($category) {
                $query->where('category_id', $category->id);
            })
            ->where('is_skipped', false)
            ->get()
            ->keyBy('question_id');
        
        // Generate PDF (gunakan library seperti Dompdf atau mpdf)
        // $pdf = PDF::loadView('questionnaire.answers.pdf', compact('alumni', 'category', 'questionnaires', 'answers'));
        // return $pdf->download('hasil-kuesioner-' . $category->slug . '-' . now()->format('Y-m-d') . '.pdf');
        
        // Untuk sementara, tampilkan view
        return view('questionnaire.answers.pdf', compact(
            'alumni',
            'category',
            'questionnaires',
            'answers'
        ));
    }
    
    /**
     * API: Get jawaban untuk pertanyaan tertentu
     */
    public function getAnswer($questionId)
    {
        $alumni = Auth::user()->alumni;
        
        $answer = AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('question_id', $questionId)
            ->first();
        
        if (!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Jawaban tidak ditemukan.',
            ]);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'answer' => $answer->answer,
                'selected_options' => $answer->selected_options,
                'scale_value' => $answer->scale_value,
                'is_skipped' => $answer->is_skipped,
                'answered_at' => $answer->answered_at,
                'formatted_answer' => $answer->formatted_answer,
            ],
        ]);
    }
    
    /**
     * API: Get semua jawaban untuk bagian tertentu
     */
    public function getQuestionnaireAnswers($questionnaireId)
    {
        $alumni = Auth::user()->alumni;
        $questionnaire = Questionnaire::findOrFail($questionnaireId);
        
        $answers = AnswerQuestion::where('alumni_id', $alumni->id)
            ->whereHas('question', function ($query) use ($questionnaireId) {
                $query->where('questionnaire_id', $questionnaireId);
            })
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'questionnaire' => $questionnaire,
                'answers' => $answers,
            ],
        ]);
    }
}