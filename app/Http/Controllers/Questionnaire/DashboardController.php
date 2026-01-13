<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StatusQuestionnaire;
use App\Models\QuestionnaireProgress;
use App\Models\QuestionnaireSequence;
use App\Models\AlumniAchievement;
use App\Models\AnswerQuestion;
use App\Models\Alumni;
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
                    'current_rank_number' => 0,
                    'total_participants' => 0,
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
        
        // Hitung ranking
        $currentRank = $this->getCurrentRank($alumni);
        $totalParticipants = Alumni::count();
        
        $stats = [
            'categories_completed' => StatusQuestionnaire::where('alumni_id', $alumni->id)
                ->where('status', 'completed')
                ->count(),
            'total_questions_answered' => $totalAnswered,
            'total_questions' => $totalQuestions,
            'current_rank_number' => $currentRank,
            'total_participants' => $totalParticipants,
            'sections_completed' => $sectionsCompleted,
            'total_sections' => count($sequences),
            'progress_percentage' => $progressPercentage,
        ];
        
        // Hitung bagian mana yang sudah selesai
        $generalCompleted = false;
        $part1Completed = false;
        $part2Completed = false;
        $part3Completed = false;
        $part4Completed = false;

        foreach ($progressRecords as $record) {
            if ($record['is_general'] && $record['progress'] && $record['progress']->status === 'completed') {
                $generalCompleted = true;
            }
            
            if (!$record['is_general']) {
                switch ($record['section_number']) {
                    case 1:
                        $part1Completed = $record['progress'] && $record['progress']->status === 'completed';
                        break;
                    case 2:
                        $part2Completed = $record['progress'] && $record['progress']->status === 'completed';
                        break;
                    case 3:
                        $part3Completed = $record['progress'] && $record['progress']->status === 'completed';
                        break;
                    case 4:
                        $part4Completed = $record['progress'] && $record['progress']->status === 'completed';
                        break;
                }
            }
        }

        // Tambahkan ke array data yang dikirim ke view
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
            
            // Tambahkan variabel untuk fitur yang terbuka
            'generalCompleted' => $generalCompleted,
            'part1Completed' => $part1Completed,
            'part2Completed' => $part2Completed,
            'part3Completed' => $part3Completed,
            'part4Completed' => $part4Completed,
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
                'current_rank_number' => 0,
                'total_participants' => 0,
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
     * Get current ranking dari leaderboard
     */
    private function getCurrentRank($alumni)
    {
        // Logika untuk mendapatkan ranking dari leaderboard
        // Untuk implementasi nyata, Anda perlu query tabel leaderboard
        
        // Contoh: Ambil ranking berdasarkan total points
        $currentUserPoints = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->sum('total_points') ?? 0;
        
        // Hitung berapa banyak alumni yang memiliki points lebih tinggi
        $higherRanked = StatusQuestionnaire::select('alumni_id')
            ->selectRaw('SUM(total_points) as total_points_sum')
            ->groupBy('alumni_id')
            ->havingRaw('SUM(total_points) > ?', [$currentUserPoints])
            ->count();
        
        // Ranking adalah posisi user + 1
        $rank = $higherRanked + 1;
        
        // Jika belum ada points, beri ranking berdasarkan jumlah peserta
        if ($currentUserPoints <= 0) {
            $totalParticipants = Alumni::count();
            $rank = max(1, $totalParticipants); // Posisi terakhir
        }
        
        return $rank;
    }
    
    /**
     * Calculate alumni rank category (tetap untuk kompatibilitas jika diperlukan)
     */
    private function calculateRankCategory($alumni)
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