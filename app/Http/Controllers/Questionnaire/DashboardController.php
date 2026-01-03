<?php

namespace App\Http\Controllers\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\StatusQuestionnaire;
use App\Models\QuestionnaireProgress;
use App\Models\AlumniAchievement;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard utama alumni
     */
    public function index()
    {
        $alumni = Auth::user()->alumni;
        
        // Cek status kuesioner
        $statusQuestionnaire = StatusQuestionnaire::with(['category', 'currentQuestionnaire'])
            ->where('alumni_id', $alumni->id)
            ->first();
        
        // Jika belum memilih kategori, arahkan ke pemilihan
        if (!$statusQuestionnaire) {
            return redirect()->route('questionnaire.categories');
        }
        
        // Ambil progress detail
        $progressRecords = [];
        $totalPoints = 0;
        $achievements = [];
        
        if ($statusQuestionnaire) {
            $progressRecords = QuestionnaireProgress::with('questionnaire')
                ->where('alumni_id', $alumni->id)
                ->whereHas('questionnaire', function ($query) use ($statusQuestionnaire) {
                    $query->where('category_id', $statusQuestionnaire->category_id);
                })
                ->orderBy('questionnaire_id')
                ->get();
            
            // Hitung total points
            $totalPoints = $statusQuestionnaire->total_points;
            
            // Ambil achievements
            $achievements = AlumniAchievement::where('alumni_id', $alumni->id)
                ->orderBy('achieved_at', 'desc')
                ->take(5)
                ->get();
        }
        
        // Ambil kategori aktif lainnya
        $otherCategories = Category::where('is_active', true)
            ->where('id', '!=', $statusQuestionnaire?->category_id)
            ->orderBy('order')
            ->get();
        
        // Statistik
        $stats = [
            'categories_completed' => StatusQuestionnaire::where('alumni_id', $alumni->id)
                ->where('status', 'completed')
                ->count(),
            'total_questions_answered' => $this->getTotalAnsweredQuestions($alumni),
            'achievements_count' => AlumniAchievement::where('alumni_id', $alumni->id)->count(),
            'current_rank' => $this->calculateRank($alumni),
        ];
        
        return view('questionnaire.dashboard', compact(
            'statusQuestionnaire',
            'progressRecords',
            'totalPoints',
            'achievements',
            'otherCategories',
            'stats'
        ));
    }
    
    /**
     * Get total questions answered by alumni
     */
    private function getTotalAnsweredQuestions($alumni)
    {
        return \App\Models\AnswerQuestion::where('alumni_id', $alumni->id)
            ->where('is_skipped', false)
            ->count();
    }
    
    /**
     * Calculate alumni rank (simulasi)
     */
    private function calculateRank($alumni)
    {
        // Ini adalah simulasi sederhana
        // Dalam implementasi nyata, Anda mungkin ingin menghitung berdasarkan points atau achievements
        
        $totalPoints = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->sum('total_points');
        
        // Dummy rank calculation
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
    
    /**
     * Tampilkan fitur eksklusif yang terbuka
     */
    public function features()
    {
        $alumni = Auth::user()->alumni;
        
        // Cek status kuesioner untuk menentukan fitur yang terbuka
        $statusQuestionnaire = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->where('status', 'completed')
            ->first();
        
        $features = [
            'leaderboard' => [
                'name' => 'Leaderboard',
                'description' => 'Kumpulkan poin dan bersaing di papan peringkat alumni',
                'icon' => 'crown',
                'unlocked' => true, // Selalu terbuka
                'required_progress' => 0,
            ],
            'forum' => [
                'name' => 'Forum Diskusi',
                'description' => 'Akses event, seminar, dan diskusi eksklusif',
                'icon' => 'comments',
                'unlocked' => true, // Selalu terbuka
                'required_progress' => 0,
            ],
            'consultation' => [
                'name' => 'Konsultasi Karir',
                'description' => 'Konsultasi privat dengan mentor berpengalaman',
                'icon' => 'chalkboard-teacher',
                'unlocked' => $statusQuestionnaire ? true : false,
                'required_progress' => 50,
            ],
            'jobs' => [
                'name' => 'Lowongan Kerja',
                'description' => 'Rekomendasi lowongan eksklusif dari mitra UAD',
                'icon' => 'briefcase',
                'unlocked' => $statusQuestionnaire ? true : false,
                'required_progress' => 100,
            ],
            'networking' => [
                'name' => 'Jaringan Alumni',
                'description' => 'Terhubung dengan alumni UAD lainnya',
                'icon' => 'users',
                'unlocked' => true, // Selalu terbuka
                'required_progress' => 0,
            ],
        ];
        
        // Hitung progress pembukaan fitur
        $unlockedCount = collect($features)->where('unlocked', true)->count();
        $totalCount = count($features);
        $unlockProgress = $totalCount > 0 ? round(($unlockedCount / $totalCount) * 100) : 0;
        
        return view('questionnaire.features', compact('features', 'unlockProgress'));
    }
}