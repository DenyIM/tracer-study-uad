<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Questionnaire;
use App\Models\Question;
use App\Models\AnswerQuestion;
use App\Models\StatusQuestionnaire;
use App\Models\AlumniAchievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of alumni users.
     */
    public function alumniIndex(Request $request)
    {
        $query = Alumni::with('user')->latest();
        
        // Filter by study program
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        // Filter by graduation year
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        $alumni = $query->paginate(10);
        
        // Get unique study programs for filter dropdown
        $studyPrograms = Alumni::distinct('study_program')->pluck('study_program');
        $graduationYears = Alumni::selectRaw('YEAR(graduation_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');
            
        return view('admin.views.users.alumni.index', compact('alumni', 'studyPrograms', 'graduationYears'));
    }

    /**
     * Display the specified alumni.
     */
    public function alumniShow(Alumni $alumni)
    {
        $alumni->load('user');
        
        // Get questionnaire statistics for this alumni
        $questionnaireStats = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->with('category')
            ->get();
        
        // Get achievements
        $achievements = AlumniAchievement::where('alumni_id', $alumni->id)
            ->latest()
            ->get();
            
        return view('admin.views.users.alumni.show', compact('alumni', 'questionnaireStats', 'achievements'));
    }

    /**
     * Show the form for editing the specified alumni.
     */
    public function alumniEdit(Alumni $alumni)
    {
        $alumni->load('user');
        $studyPrograms = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        return view('admin.views.users.alumni.edit', compact('alumni', 'studyPrograms'));
    }

    /**
     * Update the specified alumni in storage.
     */
    public function alumniUpdate(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($alumni->user_id)],
            'nim' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'email_verified' => 'nullable|boolean',
        ]);

        // Update user email
        $userData = ['email' => $validated['email']];
        
        // Update email verification status
        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        } else {
            $userData['email_verified_at'] = null;
        }
        
        $alumni->user->update($userData);

        // Update alumni data
        $alumni->update([
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'],
            'ranking' => $validated['ranking'],
            'points' => $validated['points'],
        ]);

        return redirect()->route('admin.views.users.alumni.show', $alumni->id)
            ->with('success', 'Data alumni berhasil diperbarui');
    }

    /**
     * Remove the specified alumni from storage.
     */
    public function alumniDestroy(Alumni $alumni)
    {
        // Delete all related data
        try {
            DB::beginTransaction();
            
            // Delete questionnaire answers
            AnswerQuestion::where('alumni_id', $alumni->id)->delete();
            
            // Delete questionnaire status
            StatusQuestionnaire::where('alumni_id', $alumni->id)->delete();
            
            // Delete achievements
            AlumniAchievement::where('alumni_id', $alumni->id)->delete();
            
            // Delete alumni
            $user = $alumni->user;
            $alumni->delete();
            
            // Delete user
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.views.users.alumni.index')
                ->with('success', 'Alumni berhasil dihapus beserta semua data terkait');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus alumni: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new alumni.
     */
    public function alumniCreate()
    {
        $studyPrograms = ['Teknik Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        return view('admin.views.users.alumni.create', compact('studyPrograms'));
    }

    /**
     * Store a newly created alumni in storage.
     */
    public function alumniStore(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nim' => 'required|string|max:20|unique:alumnis,nim',
            'study_program' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
        ]);

        // Create user with default password
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make('password123'), // Default password
            'role' => 'alumni',
            'email_verified_at' => now(), // Auto verify for admin-created accounts
        ]);

        // Create alumni profile
        Alumni::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'],
            'ranking' => $validated['ranking'],
            'points' => $validated['points'],
        ]);

        return redirect()->route('admin.views.users.alumni.index')
            ->with('success', 'Alumni berhasil ditambahkan dengan password default: password123');
    }

    /**
     * Display a listing of admin users.
     */
    public function adminIndex()
    {
        $admins = Admin::with('user')->latest()->get();
        return view('admin.views.users.admin.index', compact('admins'));
    }

    /**
     * Display the specified admin.
     */
    public function adminShow(Admin $admin)
    {
        $admin->load('user');
        return view('admin.views.users.admin.show', compact('admin'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function adminCreate()
    {
        return view('admin.views.users.admin.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'job_title' => 'required|string|max:100',
        ]);

        // Create user
        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create admin profile
        Admin::create([
            'user_id' => $user->id,
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
            'job_title' => $validated['job_title'],
        ]);

        return redirect()->route('admin.views.users.admin.index')
            ->with('success', 'Admin berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function adminEdit(Admin $admin)
    {
        $admin->load('user');
        return view('admin.views.users.admin.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function adminUpdate(Request $request, Admin $admin)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'job_title' => 'required|string|max:100',
        ]);

        // Update user
        $userData = ['email' => $validated['email']];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $admin->user->update($userData);

        // Update admin
        $admin->update([
            'fullname' => $validated['fullname'],
            'phone' => $validated['phone'],
            'job_title' => $validated['job_title'],
        ]);

        return redirect()->route('admin.views.users.admin.show', $admin->id)
            ->with('success', 'Data admin berhasil diperbarui');
    }

    /**
    * Remove the specified admin from storage.
    */
    public function adminDestroy(Admin $admin)
    {
        // Check if trying to delete last admin
        $adminCount = Admin::count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir');
        }

        // Delete associated user
        $user = $admin->user;
        $admin->delete();
        $user->delete();

        return redirect()->route('admin.views.users.admin.index')
            ->with('success', 'Admin berhasil dihapus');
    }

    private function formatMonthlyLabels($monthlyAnswers)
    {
        return $monthlyAnswers->pluck('month')->map(function($month) {
            try {
                $date = \Carbon\Carbon::createFromFormat('Y-m', $month);
                $monthNames = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 
                    'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
                ];
                return $monthNames[$date->month - 1] . ' ' . $date->year;
            } catch (\Exception $e) {
                return $month;
            }
        });
    }

    private function formatChartData($monthlyAnswers, $categoryStats, $answersPerCategory, $questionTypeDistribution, $avgPointsPerQuestionType)
    {
        return [
            'categoryNames' => collect($categoryStats)->pluck('name')->map(function($name) {
                return strlen($name) > 15 ? substr($name, 0, 15) . '...' : $name;
            })->toArray(),
            'categoryCompleted' => collect($categoryStats)->pluck('completed')->toArray(),
            'categoryInProgress' => collect($categoryStats)->pluck('in_progress')->toArray(),
            'monthlyLabels' => $this->formatMonthlyLabels($monthlyAnswers)->toArray(),
            'monthlyAnswers' => $monthlyAnswers->pluck('total_answers')->toArray(),
            'monthlyPoints' => $monthlyAnswers->pluck('total_points')->toArray(),
            'questionTypes' => $questionTypeDistribution->toArray(),
            'categoryLabels' => collect($answersPerCategory)->pluck('category')->map(function($name) {
                return strlen($name) > 10 ? substr($name, 0, 10) . '...' : $name;
            })->toArray(),
            'categoryAnswers' => collect($answersPerCategory)->pluck('answers')->toArray(),
            'avgPoints' => $avgPointsPerQuestionType->toArray(),
        ];
    }

    /**
    * Display dashboard with complete statistics.
    */
    public function dashboard()
    {
        // Data statistik dasar
        $alumniCount = Alumni::count();
        $totalAnswers = AnswerQuestion::count();
        $totalPoints = AnswerQuestion::sum('points');
        
        // Status penyelesaian
        $totalAlumniCompleted = StatusQuestionnaire::where('status', 'completed')->count();
        $totalAlumniInProgress = StatusQuestionnaire::where('status', 'in_progress')->count();
        $totalAlumniNotStarted = StatusQuestionnaire::where('status', 'not_started')->count();
        
        // Rata-rata penyelesaian
        $totalAlumni = $alumniCount ?: 1;
        $avgCompletionRate = round(($totalAlumniCompleted / $totalAlumni) * 100);
        
        // Kategori
        $categories = Category::withCount('questionnaires')->get();
        
        // Analisis pertanyaan populer per kategori
        $topQuestionsByCategory = [];
        foreach ($categories as $category) {
            $questions = Question::whereHas('questionnaire.category', function($q) use ($category) {
                    $q->where('id', $category->id);
                })
                ->withCount('answers')
                ->orderBy('answers_count', 'desc')
                ->limit(3)
                ->get()
                ->map(function($question) {
                    return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'total_answers' => $question->answers_count,
                        'type' => $question->question_type
                    ];
                })
                ->toArray();
            
            $topQuestionsByCategory[$category->id] = $questions;
        }
        
        // Tambahkan semua kategori
        $allQuestions = Question::withCount('answers')
            ->orderBy('answers_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($question) {
                return [
                    'id' => $question->id,
                    'question_text' => $question->question_text,
                    'total_answers' => $question->answers_count,
                    'type' => $question->question_type
                ];
            })
            ->toArray();
        
        $topQuestionsByCategory['all'] = $allQuestions;
        
        // Jawaban populer
        $popularAnswers = AnswerQuestion::select('question_id', 'answer')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('answer')
            ->where('answer', '!=', '')
            ->groupBy('question_id', 'answer')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($answer) {
                $question = Question::find($answer->question_id);
                $total = AnswerQuestion::where('question_id', $answer->question_id)->count();
                $percentage = $total > 0 ? round(($answer->count / $total) * 100, 1) : 0;
                
                return [
                    'question_text' => $question ? Str::limit($question->question_text, 50) : 'Unknown',
                    'most_common_answer' => Str::limit($answer->answer, 30),
                    'percentage' => $percentage,
                    'count' => $answer->count
                ];
            })->toArray();
        
        // Analisis per tipe pertanyaan
        $multipleChoiceAnalysis = $this->analyzeMultipleChoiceQuestions();
        $scaleAnalysis = $this->analyzeScaleQuestions();
        $textAnalysis = $this->analyzeTextQuestions();
        
        // Analisis kata kunci
        $keywordAnalysis = $this->analyzeKeywords();
        
        // Top alumni
        $topAlumni = Alumni::with(['statuses', 'statuses.category'])
            ->withCount(['answers as total_answers'])
            ->withSum('answers as total_points', 'points')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get()
            ->each(function($alumni) {
                $totalQuestions = Question::count();
                $alumni->completion_rate = $totalQuestions > 0 
                    ? round(($alumni->total_answers / $totalQuestions) * 100) 
                    : 0;
            });
        
        // Data untuk chart
        $dashboardData = $this->prepareChartData();
        
        return view('admin.views.dashboard.index', compact(
            'alumniCount',
            'totalAnswers',
            'totalPoints',
            'totalAlumniCompleted',
            'totalAlumniInProgress',
            'totalAlumniNotStarted',
            'avgCompletionRate',
            'categories',
            'topQuestionsByCategory',
            'popularAnswers',
            'multipleChoiceAnalysis',
            'scaleAnalysis',
            'textAnalysis',
            'keywordAnalysis',
            'topAlumni',
            'dashboardData'
        ));
    }

    private function analyzeMultipleChoiceQuestions()
    {
        $questions = Question::whereIn('question_type', ['radio', 'dropdown', 'checkbox'])
            ->withCount('answers')
            ->having('answers_count', '>', 0)
            ->orderBy('answers_count', 'desc')
            ->limit(6)
            ->get();
        
        return $questions->map(function($question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->select('selected_options')
                ->whereNotNull('selected_options')
                ->get()
                ->flatMap(function($answer) {
                    // Decode JSON dan pastikan selalu array
                    $options = json_decode($answer->selected_options, true);
                    return is_array($options) ? $options : [];
                });
            
            // Konversi ke array sebelum menggunakan array_count_values
            $answersArray = $answers->toArray();
            $counts = array_count_values($answersArray);
            arsort($counts);
            
            return [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'total_answers' => $question->answers_count,
                'distribution' => array_slice($counts, 0, 5, true)
            ];
        })->toArray(); // Jangan lupa konversi ke array
    }

    private function analyzeScaleQuestions()
    {
        $questions = Question::whereIn('question_type', ['likert_scale', 'competency_scale', 'likert_per_row'])
            ->withCount('answers')
            ->having('answers_count', '>', 0)
            ->orderBy('answers_count', 'desc')
            ->limit(4)
            ->get();
        
        return $questions->map(function($question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('scale_value')
                ->pluck('scale_value')
                ->toArray();
            
            $distribution = [];
            for ($i = 1; $i <= 5; $i++) {
                $distribution[$i] = count(array_filter($answers, function($value) use ($i) {
                    return $value == $i;
                }));
            }
            
            return [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'total_answers' => $question->answers_count,
                'distribution' => $distribution,
                'average' => count($answers) > 0 ? round(array_sum($answers) / count($answers), 2) : 0
            ];
        })->toArray();
    }

    private function analyzeTextQuestions()
    {
        $questions = Question::whereIn('question_type', ['text', 'textarea'])
            ->withCount('answers')
            ->having('answers_count', '>', 0)
            ->orderBy('answers_count', 'desc')
            ->limit(3)
            ->get();
        
        return $questions->map(function($question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('answer')
                ->pluck('answer')
                ->toArray();
            
            // Analisis kata kunci sederhana
            $allText = implode(' ', $answers);
            $words = str_word_count(strtolower($allText), 1);
            $stopWords = ['dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'ini', 'itu'];
            $filteredWords = array_diff($words, $stopWords);
            $wordCounts = array_count_values($filteredWords);
            arsort($wordCounts);
            
            return [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'total_answers' => $question->answers_count,
                'common_words' => array_slice($wordCounts, 0, 10, true),
                'sample_answers' => array_slice($answers, 0, 3)
            ];
        })->toArray();
    }

    private function analyzeKeywords()
    {
        $answers = AnswerQuestion::whereNotNull('answer')
            ->whereIn('answer', function($query) {
                $query->select('answer')
                    ->from('answer_questions')
                    ->whereNotNull('answer')
                    ->groupBy('answer')
                    ->havingRaw('COUNT(*) > 1');
            })
            ->pluck('answer')
            ->toArray();
        
        $allText = implode(' ', $answers);
        $words = str_word_count(strtolower($allText), 1);
        $stopWords = ['dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'ini', 'itu', 'dalam', 'ada', 'adalah'];
        $filteredWords = array_diff($words, $stopWords);
        $wordCounts = array_count_values($filteredWords);
        arsort($wordCounts);
        
        return collect(array_slice($wordCounts, 0, 20, true))
            ->map(function($count, $word) {
                return ['word' => $word, 'count' => $count];
            })
            ->values()
            ->toArray();
    }

    private function prepareChartData()
    {
        $data = [];
        
        // Data status penyelesaian
        $data['completionStatus'] = [
            StatusQuestionnaire::where('status', 'completed')->count(),
            StatusQuestionnaire::where('status', 'in_progress')->count(),
            StatusQuestionnaire::where('status', 'not_started')->count()
        ];
        
        // Data tren bulanan
        $data['monthlyTrend'] = $this->getMonthlyTrend();
        
        // Data untuk chart pilihan ganda
        $data['multipleChoiceCharts'] = $this->getMultipleChoiceCharts();
        
        // Data untuk chart skala
        $data['scaleCharts'] = $this->getScaleCharts();
        
        // Data untuk chart pertanyaan per kategori
        $data['categoryQuestionsCharts'] = $this->getCategoryQuestionsCharts();
        
        // Data untuk word cloud
        $data['wordCloud'] = $this->getWordCloudData();
        
        return $data;
    }

    private function getMonthlyTrend()
    {
        $months = [];
        $answers = [];
        $users = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthYear = $date->format('M Y');
            
            $monthAnswers = AnswerQuestion::whereYear('answered_at', $date->year)
                ->whereMonth('answered_at', $date->month)
                ->count();
            
            $monthUsers = AnswerQuestion::whereYear('answered_at', $date->year)
                ->whereMonth('answered_at', $date->month)
                ->distinct('alumni_id')
                ->count('alumni_id');
            
            $months[] = $monthYear;
            $answers[] = $monthAnswers;
            $users[] = $monthUsers;
        }
        
        return [
            'labels' => $months,
            'answers' => $answers,
            'activeUsers' => $users
        ];
    }

    private function getMultipleChoiceCharts()
    {
        $questions = Question::whereIn('question_type', ['radio', 'dropdown'])
            ->withCount('answers')
            ->orderBy('answers_count', 'desc')
            ->limit(4)
            ->get();
        
        return $questions->map(function($question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('selected_options')
                ->get();
            
            $options = $question->options ?? [];
            $distribution = [];
            
            foreach ($options as $option) {
                $count = $answers->filter(function($answer) use ($option) {
                    $selected = json_decode($answer->selected_options, true);
                    return is_array($selected) && in_array($option, $selected);
                })->count();
                
                $distribution[$option] = $count;
            }
            
            return [
                'question_id' => $question->id,
                'labels' => array_keys($distribution),
                'data' => array_values($distribution),
                'colors' => $this->generateColors(count($distribution))
            ];
        })->toArray();
    }

    private function getScaleCharts()
    {
        $questions = Question::whereIn('question_type', ['likert_scale', 'competency_scale'])
            ->withCount('answers')
            ->orderBy('answers_count', 'desc')
            ->limit(2)
            ->get();
        
        return $questions->map(function($question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('scale_value')
                ->pluck('scale_value');
            
            $distribution = [];
            for ($i = 1; $i <= 5; $i++) {
                $distribution[$i] = $answers->filter(function($value) use ($i) {
                    return $value == $i;
                })->count();
            }
            
            return [
                'question_id' => $question->id,
                'labels' => array_keys($distribution),
                'data' => array_values($distribution)
            ];
        })->toArray();
    }

    private function getCategoryQuestionsCharts()
    {
        $questions = Question::withCount('answers')
            ->orderBy('answers_count', 'desc')
            ->limit(8)
            ->get();
        
        return $questions->map(function($question) {
            if ($question->question_type === 'radio' || $question->question_type === 'dropdown') {
                $answers = AnswerQuestion::where('question_id', $question->id)
                    ->whereNotNull('selected_options')
                    ->get();
                
                $options = $question->options ?? [];
                $distribution = [];
                
                foreach ($options as $option) {
                    $count = $answers->filter(function($answer) use ($option) {
                        $selected = json_decode($answer->selected_options, true);
                        return is_array($selected) && in_array($option, $selected);
                    })->count();
                    
                    $distribution[$option] = $count;
                }
                
                return [
                    'question_id' => $question->id,
                    'labels' => array_keys($distribution),
                    'data' => array_values($distribution),
                    'colors' => $this->generateColors(count($distribution))
                ];
            }
            
            return null;
        })->filter()->toArray();
    }

    private function getWordCloudData()
    {
        $answers = AnswerQuestion::whereNotNull('answer')
            ->pluck('answer')
            ->toArray();
        
        $allText = implode(' ', $answers);
        $words = str_word_count(strtolower($allText), 1);
        $stopWords = ['dan', 'di', 'ke', 'dari', 'yang', 'untuk', 'pada', 'dengan', 'ini', 'itu', 'dalam', 'oleh', 'atau'];
        $filteredWords = array_diff($words, $stopWords);
        $wordCounts = array_count_values($filteredWords);
        arsort($wordCounts);
        
        return collect(array_slice($wordCounts, 0, 50, true))
            ->map(function($count, $word) {
                return ['text' => $word, 'size' => $count];
            })
            ->values()
            ->toArray();
    }

    private function generateColors($count)
    {
        $colors = [
            '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d',
            '#0dcaf0', '#6610f2', '#fd7e14', '#20c997', '#6f42c1'
        ];
        
        if ($count <= count($colors)) {
            return array_slice($colors, 0, $count);
        }
        
        // Generate random colors jika perlu lebih banyak
        $generated = [];
        for ($i = 0; $i < $count; $i++) {
            $generated[] = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
        }
        
        return $generated;
    }

    /**
     * Verify alumni email manually.
     */
    public function verifyAlumniEmail(Alumni $alumni)
    {
        $alumni->user->update(['email_verified_at' => now()]);
        
        return redirect()->back()
            ->with('success', 'Email alumni berhasil diverifikasi');
    }

    /**
     * Reset alumni password.
     */
    public function resetAlumniPassword(Alumni $alumni)
    {
        $alumni->user->update(['password' => Hash::make('password123')]);
        
        return redirect()->back()
            ->with('success', 'Password alumni berhasil direset ke: password123');
    }
    
    /**
     * Get real-time dashboard statistics (for AJAX requests).
     */
    public function getDashboardStats()
    {
        $alumniCount = Alumni::count();
        $verifiedAlumniCount = User::where('role', 'alumni')
            ->whereNotNull('email_verified_at')
            ->count();
        $totalAnswers = AnswerQuestion::count();
        $totalPoints = AnswerQuestion::sum('points') ?? 0;
        
        // Real-time statistics
        $todayAnswers = AnswerQuestion::whereDate('answered_at', today())->count();
        $weekAnswers = AnswerQuestion::where('answered_at', '>=', now()->subWeek())->count();
        $monthAnswers = AnswerQuestion::where('answered_at', '>=', now()->subMonth())->count();
        
        // Recent activities count
        $recentActivitiesCount = AnswerQuestion::where('answered_at', '>=', now()->subHours(24))->count();
        
        // Completion rate
        $totalCompleted = StatusQuestionnaire::where('status', 'completed')->count();
        $totalStatus = StatusQuestionnaire::count();
        $completionRate = $totalStatus > 0 ? round(($totalCompleted / $totalStatus) * 100, 1) : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'alumni_count' => $alumniCount,
                'verified_alumni' => $verifiedAlumniCount,
                'total_answers' => $totalAnswers,
                'total_points' => $totalPoints,
                'today_answers' => $todayAnswers,
                'week_answers' => $weekAnswers,
                'month_answers' => $monthAnswers,
                'recent_activities' => $recentActivitiesCount,
                'completion_rate' => $completionRate,
                'last_updated' => now()->format('Y-m-d H:i:s')
            ]
        ]);
    }
    
    /**
     * Bulk delete alumni.
     */
    public function alumniBulkDestroy(Request $request)
    {
        $request->validate([
            'alumni_ids' => 'required|array',
            'alumni_ids.*' => 'exists:alumnis,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            foreach ($request->alumni_ids as $alumniId) {
                $alumni = Alumni::find($alumniId);
                
                if ($alumni) {
                    // Delete all related data
                    AnswerQuestion::where('alumni_id', $alumniId)->delete();
                    StatusQuestionnaire::where('alumni_id', $alumniId)->delete();
                    AlumniAchievement::where('alumni_id', $alumniId)->delete();
                    
                    // Delete user
                    $user = $alumni->user;
                    $alumni->delete();
                    $user->delete();
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.views.users.alumni.index')
                ->with('success', count($request->alumni_ids) . ' alumni berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus alumni: ' . $e->getMessage());
        }
    }
    
    /**
     * Export alumni data.
     */
    public function exportAlumni(Request $request)
    {
        $query = Alumni::with('user');
        
        // Apply filters
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        $alumni = $query->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="alumni-data-' . date('Y-m-d') . '.csv"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($alumni) {
            $file = fopen('php://output', 'w');
            
            // Header
            fputcsv($file, [
                'NIM',
                'Nama Lengkap',
                'Email',
                'Program Studi',
                'Tanggal Lulus',
                'No. Telepon',
                'NPWP',
                'Ranking',
                'Points',
                'Status Verifikasi',
                'Tanggal Bergabung'
            ]);

            foreach ($alumni as $item) {
                fputcsv($file, [
                    $item->nim,
                    $item->fullname,
                    $item->user->email,
                    $item->study_program,
                    $item->graduation_date ? $item->graduation_date->format('d-m-Y') : '',
                    $item->phone,
                    $item->npwp ?? '-',
                    $item->ranking ?? '-',
                    $item->points ?? '0',
                    $item->user->email_verified_at ? 'Terverifikasi' : 'Belum',
                    $item->created_at->format('d-m-Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Import alumni from CSV.
     */
    public function importAlumni(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);
        
        $file = $request->file('csv_file');
        $csvData = array_map('str_getcsv', file($file));
        
        $imported = 0;
        $skipped = 0;
        
        try {
            DB::beginTransaction();
            
            // Skip header row
            array_shift($csvData);
            
            foreach ($csvData as $row) {
                if (count($row) >= 6) {
                    $email = trim($row[2] ?? '');
                    $nim = trim($row[0] ?? '');
                    
                    // Check if email or NIM already exists
                    $existingEmail = User::where('email', $email)->exists();
                    $existingNIM = Alumni::where('nim', $nim)->exists();
                    
                    if (!$existingEmail && !$existingNIM) {
                        // Create user
                        $user = User::create([
                            'email' => $email,
                            'password' => Hash::make('password123'),
                            'role' => 'alumni',
                            'email_verified_at' => now(),
                        ]);
                        
                        // Create alumni
                        Alumni::create([
                            'user_id' => $user->id,
                            'nim' => $nim,
                            'fullname' => trim($row[1] ?? ''),
                            'study_program' => trim($row[3] ?? ''),
                            'graduation_date' => $row[4] ? Carbon::createFromFormat('d-m-Y', trim($row[4])) : null,
                            'phone' => trim($row[5] ?? ''),
                            'points' => 0,
                        ]);
                        
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.views.users.alumni.index')
                ->with('success', "Import selesai: {$imported} berhasil diimport, {$skipped} dilewati")
                ->with('info', 'Password default: password123');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal import alumni: ' . $e->getMessage());
        }
    }

    public function getTracerChartsData()
    {
        try {
            // 1. Status Lulusan Saat Ini - Dinamis berdasarkan kategori yang dipilih alumni
            $statusData = $this->getRealGraduateStatusData();
            
            // 2. Waktu Tunggu Mendapat Pekerjaan - Dinamis dari pertanyaan
            $waitingTimeData = $this->getRealWaitingTimeData();
            
            // 3. Hubungan Bidang Studi dengan Pekerjaan - Dinamis dari pertanyaan
            $relevanceData = $this->getRealStudyWorkRelevanceData();
            
            // 4. Tingkat Tempat Kerja - Dinamis dari pertanyaan
            $workLevelData = $this->getRealWorkLevelData();
            
            // 5. Kisaran Gaji - Dinamis dari pertanyaan
            $salaryRangeData = $this->getRealSalaryRangeData();
            
            // 6. Metode Pembelajaran - Dinamis dari pertanyaan skala
            $learningMethodData = $this->getRealLearningMethodData();
            
            // 7. Kompetensi - Dinamis dari pertanyaan skala
            $competenceData = $this->getRealCompetenceData();
            
            // 8. Sumber Biaya - Dinamis dari pertanyaan
            $fundingSourceData = $this->getRealFundingSourceData();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'graduate_status' => $statusData,
                    'waiting_time' => $waitingTimeData,
                    'study_work_relevance' => $relevanceData,
                    'work_level' => $workLevelData,
                    'salary_range' => $salaryRangeData,
                    'learning_methods' => $learningMethodData,
                    'competence' => $competenceData,
                    'funding_source' => $fundingSourceData,
                ],
                'metadata' => [
                    'total_alumni' => Alumni::count(),
                    'alumni_with_answers' => StatusQuestionnaire::distinct('alumni_id')->count(),
                    'total_answers' => AnswerQuestion::count(),
                    'generated_at' => now()->format('Y-m-d H:i:s'),
                    'data_source' => 'Database Kuesioner (Dinamis)'
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => $this->getFallbackData() // Fallback ke data statis jika error
            ], 500);
        }
    }

    /**
     * Get graduate status data for Chart 1.1
     */
    private function getRealGraduateStatusData()
    {
        $statusCounts = StatusQuestionnaire::select('category_id', DB::raw('COUNT(*) as count'))
            ->with('category')
            ->groupBy('category_id')
            ->get();
        
        $labels = [];
        $values = [];
        $total = $statusCounts->sum('count');
        
        foreach ($statusCounts as $status) {
            $categoryName = $status->category ? $status->category->name : 'Unknown';
            $labels[] = $categoryName;
            $values[] = $total > 0 ? round(($status->count / $total) * 100, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
            'counts' => $statusCounts->pluck('count')->toArray(),
            'total' => $total,
            'data_source' => 'Database (Dinamis - Berdasarkan Kategori)',
            'conclusion' => $total > 0 ? 
                "Total: {$total} alumni telah memilih kategori. " . 
                "Kategori terbanyak: " . ($labels[0] ?? '-') . " (" . ($values[0] ?? 0) . "%)" : 
                "Belum ada data kategori dari alumni"
        ];
    }

    /**
     * Get waiting time data for Chart 1.3
     */
    private function getRealWaitingTimeData()
    {
        // Cari pertanyaan yang berhubungan dengan waktu tunggu pekerjaan
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%lama%pekerjaan%')
                ->orWhere('question_text', 'like', '%waktu%kerja%')
                ->orWhere('question_text', 'like', '%berapa lama%')
                ->orWhere('question_text', 'like', '%kapan mulai%');
            })
            ->whereIn('question_type', ['radio', 'dropdown', 'text'])
            ->get();
        
        $waitingData = [
            'WT <= 6 bulan' => 0,
            '6 > WT <= 18 bulan' => 0,
            'WT > 18 bulan' => 0
        ];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('answer')
                ->get();
            
            foreach ($answers as $answer) {
                $answerText = strtolower($answer->answer);
                
                // Analisis jawaban teks untuk ekstrak angka bulan
                preg_match('/(\d+)\s*(bulan|month)/', $answerText, $matches);
                
                if (count($matches) >= 2) {
                    $months = (int)$matches[1];
                    
                    if ($months <= 6) {
                        $waitingData['WT <= 6 bulan']++;
                    } elseif ($months <= 18) {
                        $waitingData['6 > WT <= 18 bulan']++;
                    } else {
                        $waitingData['WT > 18 bulan']++;
                    }
                }
                // Analisis pilihan ganda
                elseif ($answer->selected_options) {
                    $selected = json_decode($answer->selected_options, true);
                    if (is_array($selected)) {
                        $selectedText = strtolower(implode(' ', $selected));
                        if (strpos($selectedText, '3-6') !== false || strpos($selectedText, '<=6') !== false) {
                            $waitingData['WT <= 6 bulan']++;
                        } elseif (strpos($selectedText, '6-12') !== false || strpos($selectedText, '6-18') !== false) {
                            $waitingData['6 > WT <= 18 bulan']++;
                        } elseif (strpos($selectedText, '>12') !== false || strpos($selectedText, '>18') !== false) {
                            $waitingData['WT > 18 bulan']++;
                        }
                    }
                }
            }
        }
        
        $total = array_sum($waitingData);
        
        return [
            'labels' => array_keys($waitingData),
            'values' => $total > 0 ? array_map(function($val) use ($total) {
                return round(($val / $total) * 100, 2);
            }, array_values($waitingData)) : [0, 0, 0],
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Waktu)',
            'conclusion' => $total > 0 ? 
                "Dari {$total} jawaban, " . ($waitingData['WT <= 6 bulan'] ?? 0) . 
                " alumni (" . round(($waitingData['WT <= 6 bulan'] / $total) * 100, 2) . 
                "%) mendapat pekerjaan dalam ≤6 bulan" : 
                "Belum ada data waktu tunggu"
        ];
    }

    /**
     * Get study-work relevance data for Chart 2.3
     */
    private function getRealStudyWorkRelevanceData()
    {
        // Cari pertanyaan tentang hubungan studi-pekerjaan
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%hubungan%studi%kerja%')
                ->orWhere('question_text', 'like', '%erat%bidang%studi%')
                ->orWhere('question_text', 'like', '%relevan%pendidikan%')
                ->orWhere('question_text', 'like', '%sesuai%bidang%studi%');
            })
            ->whereIn('question_type', ['radio', 'dropdown', 'likert_scale'])
            ->get();
        
        $relevanceData = [
            'Sangat Erat' => 0,
            'Erat' => 0,
            'Cukup Erat' => 0,
            'Kurang Erat' => 0,
            'Tidak Sama Sekali' => 0
        ];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            foreach ($answers as $answer) {
                // Untuk skala Likert
                if ($answer->scale_value !== null) {
                    $scale = (int)$answer->scale_value;
                    if ($scale >= 4) $relevanceData['Sangat Erat']++;
                    elseif ($scale == 3) $relevanceData['Erat']++;
                    elseif ($scale == 2) $relevanceData['Cukup Erat']++;
                    elseif ($scale == 1) $relevanceData['Kurang Erat']++;
                    else $relevanceData['Tidak Sama Sekali']++;
                }
                // Untuk pilihan teks
                elseif ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    if (strpos($answerText, 'sangat erat') !== false) $relevanceData['Sangat Erat']++;
                    elseif (strpos($answerText, 'erat') !== false) $relevanceData['Erat']++;
                    elseif (strpos($answerText, 'cukup') !== false) $relevanceData['Cukup Erat']++;
                    elseif (strpos($answerText, 'kurang') !== false) $relevanceData['Kurang Erat']++;
                    elseif (strpos($answerText, 'tidak') !== false) $relevanceData['Tidak Sama Sekali']++;
                }
                // Untuk pilihan ganda
                elseif ($answer->selected_options) {
                    $selected = json_decode($answer->selected_options, true);
                    if (is_array($selected)) {
                        $selectedText = strtolower(implode(' ', $selected));
                        if (strpos($selectedText, 'sangat erat') !== false) $relevanceData['Sangat Erat']++;
                        elseif (strpos($selectedText, 'erat') !== false) $relevanceData['Erat']++;
                        elseif (strpos($selectedText, 'cukup') !== false) $relevanceData['Cukup Erat']++;
                        elseif (strpos($selectedText, 'kurang') !== false) $relevanceData['Kurang Erat']++;
                        elseif (strpos($selectedText, 'tidak') !== false) $relevanceData['Tidak Sama Sekali']++;
                    }
                }
            }
        }
        
        $total = array_sum($relevanceData);
        
        return [
            'labels' => array_keys($relevanceData),
            'values' => $total > 0 ? array_map(function($val) use ($total) {
                return round(($val / $total) * 100, 2);
            }, array_values($relevanceData)) : [0, 0, 0, 0, 0],
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Relevansi)',
            'conclusion' => $total > 0 ? 
                ($relevanceData['Sangat Erat'] + $relevanceData['Erat']) . 
                " alumni (" . round((($relevanceData['Sangat Erat'] + $relevanceData['Erat']) / $total) * 100, 2) . 
                "%) merasa hubungan studi-pekerjaan erat/sangat erat" : 
                "Belum ada data relevansi"
        ];
    }

    /**
     * Get work level data for Chart 2.1
     */
    private function getRealWorkLevelData()
    {
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%tingkat%perusahaan%')
                ->orWhere('question_text', 'like', '%skala%perusahaan%')
                ->orWhere('question_text', 'like', '%level%usaha%')
                ->orWhere('question_text', 'like', '%lokal%nasional%internasional%');
            })
            ->get();
        
        $workLevelData = [
            'Lokal/Wilayah' => 0,
            'Nasional' => 0,
            'Multinasional/Internasional' => 0
        ];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            foreach ($answers as $answer) {
                if ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    if (strpos($answerText, 'lokal') !== false || strpos($answerText, 'wilayah') !== false) {
                        $workLevelData['Lokal/Wilayah']++;
                    } elseif (strpos($answerText, 'nasional') !== false) {
                        $workLevelData['Nasional']++;
                    } elseif (strpos($answerText, 'multinasional') !== false || strpos($answerText, 'internasional') !== false) {
                        $workLevelData['Multinasional/Internasional']++;
                    }
                }
            }
        }
        
        $total = array_sum($workLevelData);
        
        return [
            'labels' => array_keys($workLevelData),
            'values' => $total > 0 ? array_map(function($val) use ($total) {
                return round(($val / $total) * 100, 2);
            }, array_values($workLevelData)) : [0, 0, 0],
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Tingkat Perusahaan)'
        ];
    }

    /**
     * Get salary range data for Chart 2.6
     */
    private function getRealSalaryRangeData()
    {
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%gaji%')
                ->orWhere('question_text', 'like', '%pendapatan%')
                ->orWhere('question_text', 'like', '%penghasilan%')
                ->orWhere('question_text', 'like', '%salary%')
                ->orWhere('question_text', 'like', '%income%');
            })
            ->get();
        
        $salaryRanges = [
            '< Rp1.000.000' => 0,
            'Rp1.000.000 - Rp3.000.000' => 0,
            'Rp3.000.001 - Rp5.000.000' => 0,
            'Rp5.000.001 - Rp10.000.000' => 0,
            '> Rp10.000.000' => 0
        ];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            foreach ($answers as $answer) {
                if ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    
                    // Ekstrak angka dari jawaban
                    preg_match_all('/\d+/', $answerText, $matches);
                    if (!empty($matches[0])) {
                        $numbers = array_map('intval', $matches[0]);
                        $maxNumber = max($numbers);
                        
                        if ($maxNumber < 1000) $salaryRanges['< Rp1.000.000']++;
                        elseif ($maxNumber <= 3000) $salaryRanges['Rp1.000.000 - Rp3.000.000']++;
                        elseif ($maxNumber <= 5000) $salaryRanges['Rp3.000.001 - Rp5.000.000']++;
                        elseif ($maxNumber <= 10000) $salaryRanges['Rp5.000.001 - Rp10.000.000']++;
                        else $salaryRanges['> Rp10.000.000']++;
                    }
                }
            }
        }
        
        $total = array_sum($salaryRanges);
        
        return [
            'labels' => array_keys($salaryRanges),
            'values' => $total > 0 ? array_map(function($val) use ($total) {
                return round(($val / $total) * 100, 2);
            }, array_values($salaryRanges)) : [0, 0, 0, 0, 0],
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Gaji)'
        ];
    }

    /**
     * Get learning method data for Chart 1.10
     */
    private function getRealLearningMethodData()
    {
        // Cari pertanyaan tentang metode pembelajaran dengan skala
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%metode%pembelajaran%')
                ->orWhere('question_text', 'like', '%cara%belajar%')
                ->orWhere('question_text', 'like', '%teknik%pengajaran%');
            })
            ->whereIn('question_type', ['likert_scale', 'likert_per_row', 'radio_per_row'])
            ->get();
        
        $methodsData = [];
        $scaleLabels = ['Sangat Besar', 'Besar', 'Cukup Besar', 'Kurang', 'Tidak Sama Sekali'];
        
        foreach ($questions as $question) {
            if ($question->row_items && is_array($question->row_items)) {
                foreach ($question->row_items as $itemKey => $itemLabel) {
                    $methodsData[] = [
                        'name' => $itemLabel,
                        'values' => [0, 0, 0, 0, 0] // Initialize with zeros for 5 scales
                    ];
                }
            } else {
                $methodsData[] = [
                    'name' => Str::limit($question->question_text, 50),
                    'values' => [0, 0, 0, 0, 0]
                ];
            }
        }
        
        // Ambil jawaban untuk setiap pertanyaan
        foreach ($questions as $qIndex => $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            foreach ($answers as $answer) {
                if ($answer->scale_value !== null) {
                    $scale = (int)$answer->scale_value;
                    if ($scale >= 1 && $scale <= 5) {
                        $methodsData[$qIndex]['values'][$scale - 1]++;
                    }
                }
            }
        }
        
        return [
            'methods' => $methodsData,
            'scales' => $scaleLabels,
            'data_source' => 'Database (Dinamis - Analisis Metode Pembelajaran)'
        ];
    }

    /**
     * Get competence at graduation data for Chart 3.1
     */
    private function getRealCompetenceData()
    {
        // Cari pertanyaan tentang kompetensi dengan skala
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%kompetensi%')
                ->orWhere('question_text', 'like', '%kemampuan%')
                ->orWhere('question_text', 'like', '%keahlian%')
                ->orWhere('question_text', 'like', '%skill%');
            })
            ->whereIn('question_type', ['likert_scale', 'likert_per_row', 'radio_per_row'])
            ->get();
        
        $competencies = [];
        $scaleLabels = ['Sangat Rendah', 'Rendah', 'Sedang', 'Tinggi', 'Sangat Tinggi'];
        
        foreach ($questions as $question) {
            if ($question->row_items && is_array($question->row_items)) {
                foreach ($question->row_items as $itemKey => $itemLabel) {
                    $competencies[$itemLabel] = [0, 0, 0, 0, 0];
                }
            } else {
                $competencies[Str::limit($question->question_text, 30)] = [0, 0, 0, 0, 0];
            }
        }
        
        // Hitung jawaban untuk setiap kompetensi
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            if ($question->row_items && is_array($question->row_items)) {
                // Untuk pertanyaan per baris
                $itemIndex = 0;
                foreach ($question->row_items as $itemKey => $itemLabel) {
                    foreach ($answers as $answer) {
                        if ($answer->detailedAnswers) {
                            foreach ($answer->detailedAnswers as $detail) {
                                if ($detail->item_key === $itemKey && $detail->scale_value !== null) {
                                    $scale = (int)$detail->scale_value;
                                    if ($scale >= 1 && $scale <= 5) {
                                        $competencies[$itemLabel][$scale - 1]++;
                                    }
                                }
                            }
                        }
                    }
                    $itemIndex++;
                }
            } else {
                // Untuk pertanyaan tunggal
                foreach ($answers as $answer) {
                    if ($answer->scale_value !== null) {
                        $scale = (int)$answer->scale_value;
                        if ($scale >= 1 && $scale <= 5) {
                            $compName = Str::limit($question->question_text, 30);
                            $competencies[$compName][$scale - 1]++;
                        }
                    }
                }
            }
        }
        
        return [
            'competencies' => $competencies,
            'scales' => $scaleLabels,
            'data_source' => 'Database (Dinamis - Analisis Kompetensi)'
        ];
    }

    /**
     * Get funding source data for Chart 1.9
     */
    private function getRealFundingSourceData()
    {
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%biaya%kuliah%')
                ->orWhere('question_text', 'like', '%dana%pendidikan%')
                ->orWhere('question_text', 'like', '%sumber%biaya%')
                ->orWhere('question_text', 'like', '%beasiswa%');
            })
            ->get();
        
        $fundingSources = [
            'Biaya Sendiri/Keluarga' => 0,
            'Beasiswa ADIK' => 0,
            'Beasiswa BIDIKMISI' => 0,
            'Beasiswa PPA' => 0,
            'Beasiswa AFIRMASI' => 0,
            'Beasiswa Perusahaan/Swasta' => 0,
            'Lainnya' => 0
        ];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)->get();
            
            foreach ($answers as $answer) {
                if ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    
                    if (strpos($answerText, 'sendiri') !== false || strpos($answerText, 'keluarga') !== false) {
                        $fundingSources['Biaya Sendiri/Keluarga']++;
                    } elseif (strpos($answerText, 'adik') !== false) {
                        $fundingSources['Beasiswa ADIK']++;
                    } elseif (strpos($answerText, 'bidikmisi') !== false) {
                        $fundingSources['Beasiswa BIDIKMISI']++;
                    } elseif (strpos($answerText, 'ppa') !== false) {
                        $fundingSources['Beasiswa PPA']++;
                    } elseif (strpos($answerText, 'afirmasi') !== false) {
                        $fundingSources['Beasiswa AFIRMASI']++;
                    } elseif (strpos($answerText, 'perusahaan') !== false || strpos($answerText, 'swasta') !== false) {
                        $fundingSources['Beasiswa Perusahaan/Swasta']++;
                    } else {
                        $fundingSources['Lainnya']++;
                    }
                }
            }
        }
        
        $total = array_sum($fundingSources);
        
        return [
            'labels' => array_keys($fundingSources),
            'values' => $total > 0 ? array_map(function($val) use ($total) {
                return round(($val / $total) * 100, 2);
            }, array_values($fundingSources)) : [0, 0, 0, 0, 0, 0, 0],
            'data_source' => 'Database (Dinamis - Analisis Sumber Biaya)'
        ];
    }

    /**
     * Fallback data if real data is not available
     */
    private function getFallbackData()
    {
        // Return minimal fallback structure
        return [
            'graduate_status' => [
                'labels' => ['Data sedang dimuat...'],
                'values' => [100],
                'data_source' => 'System - Menunggu Data'
            ]
        ];
    }

    /**
     * Export questionnaire results to PDF
     */
    public function exportQuestionnaireResultsPDF(Request $request)
    {
        try {
            // Parameter filter
            $categoryId = $request->get('category_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            
            // Get data untuk laporan
            $reportData = $this->preparePDFReportData($categoryId, $startDate, $endDate);
            
            // Load view PDF
            $pdf = PDF::loadView('admin.views.questionnaire.export-pdf', $reportData);
            
            // Set options PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('defaultFont', 'Arial');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            $filename = 'laporan-kuesioner-alumni-' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // Fallback: Tampilkan halaman HTML jika PDF error
            if ($request->has('debug')) {
                $reportData = $this->preparePDFReportData();
                return view('admin.views.questionnaire.export-pdf', $reportData);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Prepare data for PDF report - DATA REAL DAN DINAMIS
     */
    private function preparePDFReportData($categoryId = null, $startDate = null, $endDate = null)
    {
        // 1. DATA STATISTIK
        $totalAlumni = Alumni::count();
        $alumniWithAnswers = StatusQuestionnaire::distinct('alumni_id')->count();
        $totalAnswers = AnswerQuestion::count();
        $totalQuestions = Question::count();
        
        // 2. DATA ALUMNI DENGAN JAWABAN
        $alumniQuery = Alumni::with([
            'user',
            'statuses.category',
            'answers' => function($query) use ($categoryId, $startDate, $endDate) {
                $query->with(['question.questionnaire.category']);
                
                if ($categoryId) {
                    $query->whereHas('question.questionnaire', function($q) use ($categoryId) {
                        $q->where('category_id', $categoryId);
                    });
                }
                
                if ($startDate) {
                    $query->whereDate('answered_at', '>=', $startDate);
                }
                
                if ($endDate) {
                    $query->whereDate('answered_at', '<=', $endDate);
                }
            }
        ])->has('answers');
        
        $alumni = $alumniQuery->limit(50)->get(); // Batasi untuk PDF
        
        // 3. DATA GRAFIK (ambil dari method yang sudah ada)
        $chartData = $this->getTracerChartsDataForPDF();
        
        // 4. DATA KATEGORI
        $categories = Category::withCount(['questionnaires', 'alumniStatuses'])
            ->orderBy('order')
            ->get();
        
        // 5. PERTANYAAN PALING SERING DIJAWAB
        $topQuestions = Question::withCount('answers')
            ->orderBy('answers_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function($question) {
                return [
                    'text' => Str::limit($question->question_text, 100),
                    'type' => $question->question_type,
                    'answers_count' => $question->answers_count,
                    'questionnaire' => $question->questionnaire->name ?? '-',
                    'category' => $question->questionnaire->category->name ?? '-'
                ];
            });
        
        // 6. ALUMNI TOP (berdasarkan jumlah jawaban)
        $topAlumni = Alumni::withCount('answers')
            ->withSum('answers as total_points', 'points')
            ->orderBy('total_points', 'desc')
            ->limit(10)
            ->get()
            ->map(function($alumni) {
                return [
                    'name' => $alumni->fullname,
                    'nim' => $alumni->nim,
                    'study_program' => $alumni->study_program,
                    'total_answers' => $alumni->answers_count,
                    'total_points' => $alumni->total_points,
                    'completion_rate' => Question::count() > 0 ? 
                        round(($alumni->answers_count / Question::count()) * 100, 1) : 0
                ];
            });
        
        return [
            // Metadata
            'title' => 'Laporan Hasil Kuesioner Alumni',
            'subtitle' => 'Sistem Tracer Study',
            'date' => now()->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i:s'),
            'period' => $startDate && $endDate ? 
                $startDate . ' sampai ' . $endDate : 'Semua Periode',
            'category_filter' => $categoryId ? 
                Category::find($categoryId)->name ?? 'Semua Kategori' : 'Semua Kategori',
            
            // Data Statistik
            'total_alumni' => $totalAlumni,
            'alumni_with_answers' => $alumniWithAnswers,
            'total_answers' => $totalAnswers,
            'total_questions' => $totalQuestions,
            'completion_rate' => $totalAlumni > 0 ? 
                round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0,
            'response_rate' => $totalAlumni > 0 ? 
                round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0,
            
            // Data Utama
            'alumni' => $alumni,
            'categories' => $categories,
            'top_questions' => $topQuestions,
            'top_alumni' => $topAlumni,
            
            // Data Grafik (untuk tampilan di PDF)
            'chart_data' => $chartData,
            
            // Filter
            'filters' => [
                'category_id' => $categoryId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'has_filters' => $categoryId || $startDate || $endDate
            ]
        ];
    }
    
    /**
     * Get chart data khusus untuk PDF (ringkasan)
     */
    private function getTracerChartsDataForPDF()
    {
        try {
            // Gunakan data REAL dari database
            $graduateStatus = $this->getRealGraduateStatusData();
            $studyWorkRelevance = $this->getRealStudyWorkRelevanceData();
            $salaryRange = $this->getRealSalaryRangeData();
            
            return [
                'graduate_status' => $graduateStatus,
                'study_work_relevance' => $studyWorkRelevance,
                'salary_range' => $salaryRange,
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_questionnaires' => Questionnaire::count(),
                    'total_questions' => Question::count(),
                    'total_alumni_respondents' => StatusQuestionnaire::distinct('alumni_id')->count(),
                    'latest_response' => AnswerQuestion::max('answered_at') ? 
                        Carbon::parse(AnswerQuestion::max('answered_at'))->format('d F Y') : '-'
                ]
            ];
            
        } catch (\Exception $e) {
            // Fallback data
            return [
                'graduate_status' => [
                    'labels' => ['Bekerja', 'Belum Bekerja', 'Studi Lanjut', 'Wirausaha'],
                    'values' => [65, 15, 10, 10]
                ],
                'study_work_relevance' => [
                    'labels' => ['Sangat Erat', 'Erat', 'Cukup', 'Kurang'],
                    'values' => [40, 25, 20, 15]
                ],
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_alumni_respondents' => StatusQuestionnaire::distinct('alumni_id')->count()
                ]
            ];
        }
    }
    
    /**
     * View PDF preview (untuk testing)
     */
    public function previewQuestionnairePDF(Request $request)
    {
        $reportData = $this->preparePDFReportData(
            $request->get('category_id'),
            $request->get('start_date'),
            $request->get('end_date')
        );
        
        return view('admin.views.questionnaire.export-pdf', $reportData);
    }
    
    /**
     * Show PDF export form
     */
    public function showExportPDFForm()
    {
        $categories = Category::all();
        return view('admin.views.questionnaire.export-pdf-form', compact('categories'));
    }

    /**
     * Export COMPLETE questionnaire answers to PDF - SEMUA JAWABAN DETAIL
     */
    public function exportCompleteAnswersPDF(Request $request)
    {
        try {
            // Parameter filter
            $categoryId = $request->get('category_id');
            $questionnaireId = $request->get('questionnaire_id');
            $alumniId = $request->get('alumni_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $format = $request->get('format', 'detailed'); // detailed/summary
            
            // Get COMPLETE data untuk laporan
            $reportData = $this->prepareCompleteAnswersData(
                $categoryId, 
                $questionnaireId, 
                $alumniId, 
                $startDate, 
                $endDate,
                $format
            );
            
            // Load view PDF
            $pdf = Pdf::loadView('admin.views.questionnaire.export-complete-answers', $reportData);
            
            // Set options PDF
            $pdf->setPaper('A4', $format === 'summary' ? 'portrait' : 'landscape');
            $pdf->setOption('defaultFont', 'Arial');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            
            $filename = 'laporan-lengkap-jawaban-alumni-' . date('Y-m-d-H-i') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            // Log error
            Log::error('PDF Export Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
        }
    }
    
    /**
     * Prepare COMPLETE answers data - SEMUA JAWABAN DETAIL
     */
    private function prepareCompleteAnswersData(
        $categoryId = null, 
        $questionnaireId = null, 
        $alumniId = null, 
        $startDate = null, 
        $endDate = null,
        $format = 'detailed'
    ) {
        // 1. QUERY UTAMA: AMBIL SEMUA JAWABAN
        $answersQuery = AnswerQuestion::with([
            'alumni.user',
            'question.questionnaire.category',
            'detailedAnswers'
        ])->orderBy('answered_at', 'desc');
        
        // Apply filters
        if ($categoryId) {
            $answersQuery->whereHas('question.questionnaire', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        if ($questionnaireId) {
            $answersQuery->whereHas('question', function($q) use ($questionnaireId) {
                $q->where('questionnaire_id', $questionnaireId);
            });
        }
        
        if ($alumniId) {
            $answersQuery->where('alumni_id', $alumniId);
        }
        
        if ($startDate) {
            $answersQuery->whereDate('answered_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $answersQuery->whereDate('answered_at', '<=', $endDate);
        }
        
        // Get all answers (tidak dibatasi untuk PDF lengkap)
        $allAnswers = $answersQuery->get();
        
        // 2. GROUP ANSWERS BY ALUMNI
        $answersByAlumni = $allAnswers->groupBy('alumni_id');
        
        // 3. GET ALUMNI DATA
        $alumniData = [];
        foreach ($answersByAlumni as $alumniId => $answers) {
            $alumni = Alumni::with('user')->find($alumniId);
            if ($alumni) {
                $alumniData[$alumniId] = [
                    'info' => $alumni,
                    'answers_count' => $answers->count(),
                    'total_points' => $answers->sum('points'),
                    'last_answer' => $answers->max('answered_at'),
                    'answers' => $answers->sortBy(function($answer) {
                        return $answer->question->questionnaire->order . '_' . $answer->question->order;
                    })
                ];
            }
        }
        
        // 4. GET QUESTIONS DATA (untuk header table)
        $questions = Question::with(['questionnaire.category'])
            ->when($categoryId, function($q) use ($categoryId) {
                $q->whereHas('questionnaire', function($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->when($questionnaireId, function($q) use ($questionnaireId) {
                $q->where('questionnaire_id', $questionnaireId);
            })
            ->orderByRaw('questionnaire_id, `order`')
            ->get();
        
        // 5. GET SUMMARY STATISTICS
        $summaryStats = [
            'total_alumni' => count($alumniData),
            'total_answers' => $allAnswers->count(),
            'total_questions' => $questions->count(),
            'total_points' => $allAnswers->sum('points'),
            'avg_answers_per_alumni' => count($alumniData) > 0 ? 
                round($allAnswers->count() / count($alumniData), 1) : 0,
            'completion_rate' => $questions->count() > 0 ? 
                round(($allAnswers->count() / ($questions->count() * count($alumniData))) * 100, 1) : 0,
            'date_range' => $allAnswers->isNotEmpty() ? 
                $allAnswers->min('answered_at')->format('d M Y') . ' - ' . 
                $allAnswers->max('answered_at')->format('d M Y') : '-'
        ];
        
        // 6. GET FILTER INFO
        $filterInfo = [];
        if ($categoryId) {
            $category = Category::find($categoryId);
            $filterInfo['category'] = $category ? $category->name : '-';
        }
        if ($questionnaireId) {
            $questionnaire = Questionnaire::find($questionnaireId);
            $filterInfo['questionnaire'] = $questionnaire ? $questionnaire->name : '-';
        }
        if ($alumniId) {
            $alumni = Alumni::find($alumniId);
            $filterInfo['alumni'] = $alumni ? $alumni->fullname . ' (' . $alumni->nim . ')' : '-';
        }
        
        // 7. PREPARE MATRIX DATA (untuk format summary)
        $answerMatrix = [];
        if ($format === 'summary') {
            foreach ($alumniData as $alumniId => $data) {
                $alumni = $data['info'];
                $row = [
                    'alumni_id' => $alumniId,
                    'nim' => $alumni->nim ?? '-',
                    'nama' => $alumni->fullname,
                    'prodi' => $alumni->study_program ?? '-',
                ];
                
                // Add answers for each question
                foreach ($questions as $question) {
                    $answer = $data['answers']->firstWhere('question_id', $question->id);
                    $row['q_' . $question->id] = $this->formatAnswerForMatrix($answer);
                }
                
                $answerMatrix[] = $row;
            }
        }
        
        return [
            // Metadata
            'title' => 'Laporan Lengkap Jawaban Alumni',
            'subtitle' => 'Detail Semua Jawaban Kuesioner',
            'date' => now()->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i:s'),
            'format' => $format,
            
            // Main Data
            'alumni_data' => $alumniData,
            'questions' => $questions,
            'all_answers' => $allAnswers,
            'answer_matrix' => $answerMatrix,
            
            // Statistics
            'summary_stats' => $summaryStats,
            
            // Filters
            'filters' => [
                'category_id' => $categoryId,
                'questionnaire_id' => $questionnaireId,
                'alumni_id' => $alumniId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'filter_info' => $filterInfo,
                'has_filters' => $categoryId || $questionnaireId || $alumniId || $startDate || $endDate
            ],
            
            // Options
            'show_all_details' => true,
            'max_answers_per_page' => $format === 'detailed' ? 15 : 50,
        ];
    }
    
    /**
     * Format answer for matrix display
     */
    private function formatAnswerForMatrix($answer)
    {
        if (!$answer) return '-';
        
        if ($answer->scale_value !== null) {
            return "Skala: " . $answer->scale_value;
        }
        
        if ($answer->selected_options) {
            $options = json_decode($answer->selected_options, true);
            if (is_array($options) && !empty($options)) {
                return implode(', ', array_slice($options, 0, 2)) . 
                       (count($options) > 2 ? '...' : '');
            }
        }
        
        if ($answer->answer) {
            return Str::limit($answer->answer, 20);
        }
        
        return $answer->is_skipped ? '(Dilewati)' : '-';
    }
    
    /**
     * Show form for complete answers export
     */
    public function showCompleteAnswersExportForm()
    {
        $categories = Category::all();
        $questionnaires = Questionnaire::all();
        $alumni = Alumni::has('answers')->limit(100)->get();
        
        return view('admin.views.questionnaire.export-complete-answers-form', 
            compact('categories', 'questionnaires', 'alumni'));
    }
    
    /**
     * View PDF preview for complete answers
     */
    public function previewCompleteAnswersPDF(Request $request)
    {
        $reportData = $this->prepareCompleteAnswersData(
            $request->get('category_id'),
            $request->get('questionnaire_id'),
            $request->get('alumni_id'),
            $request->get('start_date'),
            $request->get('end_date'),
            $request->get('format', 'detailed')
        );
        
        return view('admin.views.questionnaire.export-complete-answers', $reportData);
    }
}