<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StatusQuestionnaire;
use App\Models\QuestionnaireProgress;
use App\Models\QuestionnaireSequence;
use App\Models\AlumniAchievement;
use App\Models\AnswerQuestion;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama alumni
     */
    public function index()
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        // Cek status kuesioner
        $statusQuestionnaire = StatusQuestionnaire::with(['category', 'currentQuestionnaire'])
            ->where('alumni_id', $alumni->id)
            ->first();
        
        // JIKA BELUM MEMILIH KATEGORI
        if (!$statusQuestionnaire) {
            // TAMPILKAN VIEW DENGAN FORM PEMILIHAN KATEGORI
            $categories = Category::where('is_active', true)
                ->orderBy('order')
                ->get();
            
            return view('questionnaire.dashboard.index', [
                'statusQuestionnaire' => null,
                'showCategorySelection' => true,
                'categories' => $categories,
                'alumni' => $alumni,
                'stats' => [
                    'total_questions_answered' => 0,
                    'total_questions' => 0,
                    'sections_completed' => 0,
                    'total_sections' => 0,
                    'achievements_count' => 0,
                    'categories_completed' => 0,
                    'current_rank' => 'Beginner',
                ],
                'progressRecords' => [],
                'totalPoints' => 0,
                'achievements' => collect(),
                'otherCategories' => collect(),
                'activeQuestionnaire' => null,
                'sequences' => collect(),
            ]);
        }
        
        // JIKA SUDAH MEMILIH KATEGORI
        $category = $statusQuestionnaire->category;
        
        // Ambil semua sequence untuk kategori ini
        $sequences = QuestionnaireSequence::with(['questionnaire' => function($query) {
                $query->withCount('questions');
            }])
            ->where('category_id', $category->id)
            ->orderBy('order')
            ->get();
        
        // Ambil progress untuk setiap questionnaire dalam sequence
        $progressRecords = [];
        $totalPoints = 0;
        $achievements = [];
        $totalAnswered = 0;
        $totalQuestions = 0;
        $sectionsCompleted = 0;
        
        foreach ($sequences as $sequence) {
            $progress = QuestionnaireProgress::where('alumni_id', $alumni->id)
                ->where('questionnaire_id', $sequence->questionnaire_id)
                ->first();
            
            // Hitung pertanyaan yang sudah dijawab untuk questionnaire ini
            $questionnaireQuestions = $sequence->questionnaire->questions()->pluck('id');
            $answeredCount = AnswerQuestion::where('alumni_id', $alumni->id)
                ->whereIn('question_id', $questionnaireQuestions)
                ->where('is_skipped', false)
                ->count();
            
            $totalAnswered += $answeredCount;
            $totalQuestions += $sequence->questionnaire->questions_count;
            
            // Hitung sections yang sudah completed
            if ($progress && $progress->status === 'completed') {
                $sectionsCompleted++;
            }
            
            $progressRecords[] = [
                'sequence' => $sequence,
                'progress' => $progress,
                'questionnaire' => $sequence->questionnaire,
                'answered_count' => $answeredCount,
                'total_questions' => $sequence->questionnaire->questions_count,
                'is_general' => $sequence->order == 1,
                'section_number' => $sequence->order > 1 ? $sequence->order - 1 : null,
            ];
        }
        
        // Hitung total points
        $totalPoints = $statusQuestionnaire->total_points ?? 0;
        
        // Ambil achievements
        $achievements = AlumniAchievement::where('alumni_id', $alumni->id)
            ->orderBy('achieved_at', 'desc')
            ->take(5)
            ->get();
        
        // Ambil kategori aktif lainnya
        $otherCategories = Category::where('is_active', true)
            ->where('id', '!=', $category->id)
            ->orderBy('order')
            ->get();
        
        // Tentukan questionnaire aktif untuk lanjutkan
        $activeQuestionnaire = null;
        foreach ($progressRecords as $record) {
            if (!$record['progress'] || $record['progress']->status !== 'completed') {
                $activeQuestionnaire = $record['questionnaire'];
                break;
            }
        }
        
        // Jika semua sudah selesai, activeQuestionnaire adalah null
        if ($sectionsCompleted === count($sequences)) {
            $activeQuestionnaire = null;
        }
        
        // Statistik
        $progressPercentage = $totalQuestions > 0 ? round(($totalAnswered / $totalQuestions) * 100) : 0;
        $stats = [
            'categories_completed' => StatusQuestionnaire::where('alumni_id', $alumni->id)
                ->where('status', 'completed')
                ->count(),
            'total_questions_answered' => $totalAnswered,
            'total_questions' => $totalQuestions,
            'achievements_count' => $achievements->count(),
            'current_rank' => $this->calculateRank($alumni),
            'sections_completed' => $sectionsCompleted,
            'total_sections' => count($sequences),
            'progress_percentage' => $progressPercentage,
        ];
        
        return view('questionnaire.dashboard.index', [
            'statusQuestionnaire' => $statusQuestionnaire,
            'category' => $category,
            'progressRecords' => $progressRecords,
            'totalPoints' => $totalPoints,
            'achievements' => $achievements,
            'otherCategories' => $otherCategories,
            'stats' => $stats,
            'activeQuestionnaire' => $activeQuestionnaire,
            'sequences' => $sequences,
            'showCategorySelection' => false,
            'categories' => collect(),
            'alumni' => $alumni,
        ]);
    }
    
    /**
     * Halaman pemilihan kategori
     */
    public function showCategories()
    {
        $alumni = Auth::user()->alumni;
        
        // Cek apakah sudah memilih kategori
        $existing = StatusQuestionnaire::where('alumni_id', $alumni->id)->first();
        if ($existing) {
            return redirect()->route('questionnaire.dashboard')
                ->with('info', 'Anda sudah memilih kategori sebelumnya.');
        }
        
        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();
        
        return view('questionnaire.dashboard.index', [
            'statusQuestionnaire' => null,
            'showCategorySelection' => true,
            'categories' => $categories,
            'alumni' => $alumni,
            'stats' => [
                'total_questions_answered' => 0,
                'total_questions' => 0,
                'sections_completed' => 0,
                'total_sections' => 0,
                'achievements_count' => 0,
                'categories_completed' => 0,
                'current_rank' => 'Beginner',
            ],
            'progressRecords' => [],
            'totalPoints' => 0,
            'achievements' => collect(),
            'otherCategories' => collect(),
            'activeQuestionnaire' => null,
            'sequences' => collect(),
        ]);
    }
    
    /**
     * Calculate alumni rank
     */
    private function calculateRank($alumni)
    {
        $totalPoints = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->sum('total_points');
        
        if ($totalPoints >= 100) {
            return 'Gold';
        } elseif ($totalPoints >= 50) {
            return 'Silver';
        } elseif ($totalPoints >= 20) {
            return 'Bronze';
        } else {
            return 'Beginner';
        }
    }
}