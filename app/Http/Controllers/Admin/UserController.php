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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    /**
     * Display a listing of alumni users.
     */
    public function alumniIndex(Request $request)
    {
        $query = Alumni::with('user')->latest();
        
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        $alumni = $query->paginate(10);
        
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
        
        $questionnaireStats = StatusQuestionnaire::where('alumni_id', $alumni->id)
            ->with('category')
            ->get();
        
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
        $studyPrograms = ['Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
        return view('admin.views.users.alumni.edit', compact('alumni', 'studyPrograms'));
    }

    /**
     * Update the specified alumni in storage.
     */
    public function alumniUpdate(Request $request, Alumni $alumni)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($alumni->user->id),
            ],
            'nim' => 'required|string|max:20',
            'study_program' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'graduation_date' => 'required|date',
            'npwp' => 'nullable|string|max:50',
            'ranking' => 'nullable|integer|min:1',
            'points' => 'nullable|integer|min:0',
            'email_verified' => 'nullable|boolean',
        ]);

        $alumni->user->update([
            'email' => $validated['email'],
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
        ]);

        $alumni->update([
            'fullname' => $validated['fullname'],
            'nim' => $validated['nim'],
            'study_program' => $validated['study_program'],
            'phone' => $validated['phone'],
            'graduation_date' => $validated['graduation_date'],
            'npwp' => $validated['npwp'] ?? null,
            'ranking' => $validated['ranking'] ?? null,
            'points' => $validated['points'] ?? null,
        ]);

        return redirect()
            ->route('admin.views.users.alumni.show', $alumni)
            ->with('success', 'Data alumni berhasil diperbarui');
    }

    /**
     * Remove the specified alumni from storage.
     */
    public function alumniDestroy(Alumni $alumni)
    {
        try {
            DB::beginTransaction();
            
            AnswerQuestion::where('alumni_id', $alumni->id)->delete();
            
            StatusQuestionnaire::where('alumni_id', $alumni->id)->delete();
            
            AlumniAchievement::where('alumni_id', $alumni->id)->delete();
            
            $user = $alumni->user;
            $alumni->delete();
            
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
        $studyPrograms = ['Informatika', 'Sistem Informasi', 'Manajemen', 'Akuntansi'];
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

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make('password123'), 
            'role' => 'alumni',
            'email_verified_at' => now(), 
        ]);

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
        if (!Auth::user()->canCreateAdmin()) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Anda tidak memiliki izin untuk menambah admin');
        }
        
        return view('admin.views.users.admin.create');
    }

    /**
     * Store a newly created admin in storage.
     */
    public function adminStore(Request $request)
    {
        if (!Auth::user()->canCreateAdmin()) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Anda tidak memiliki izin untuk menambah admin');
        }
        
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'job_title' => 'required|string|max:100|in:System Administrator,Super Admin,Admin Akademik,Admin Keuangan,Admin Alumni,Staff',
        ]);

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

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
        if (!Auth::user()->canEditAdmin()) {
            return redirect()->route('admin.views.users.admin.show', $admin->id)
                ->with('error', 'Anda tidak memiliki izin untuk mengedit admin');
        }
        
        $admin->load('user');
        return view('admin.views.users.admin.edit', compact('admin'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function adminUpdate(Request $request, Admin $admin)
    {
        if (!Auth::user()->canEditAdmin()) {
            return redirect()->route('admin.views.users.admin.show', $admin->id)
                ->with('error', 'Anda tidak memiliki izin untuk mengedit admin');
        }
        
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->user_id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'job_title' => 'required|string|max:100|in:System Administrator,Super Admin,Admin Akademik,Admin Keuangan,Admin Alumni,Staff',
        ]);

        $userData = ['email' => $validated['email']];
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($validated['password']);
        }
        $admin->user->update($userData);

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
        if (!Auth::user()->canDeleteAdmin()) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Anda tidak memiliki izin untuk menghapus admin');
        }
        
        $adminCount = Admin::count();
        if ($adminCount <= 1) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Tidak dapat menghapus admin terakhir');
        }

        if ($admin->user_id === Auth::id()) {
            return redirect()->route('admin.views.users.admin.index')
                ->with('error', 'Tidak dapat menghapus akun sendiri');
        }

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
        $alumniCount = Alumni::count();
        $totalAnswers = AnswerQuestion::count();
        $totalPoints = AnswerQuestion::sum('points');
        
        $totalAlumniCompleted = StatusQuestionnaire::where('status', 'completed')->count();
        $totalAlumniInProgress = StatusQuestionnaire::where('status', 'in_progress')->count();
        $totalAlumniNotStarted = StatusQuestionnaire::where('status', 'not_started')->count();
        
        $totalAlumni = $alumniCount ?: 1;
        $avgCompletionRate = round(($totalAlumniCompleted / $totalAlumni) * 100);
        
        $categories = Category::withCount('questionnaires')->get();
        
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
        
        $multipleChoiceAnalysis = $this->analyzeMultipleChoiceQuestions();
        $scaleAnalysis = $this->analyzeScaleQuestions();
        $textAnalysis = $this->analyzeTextQuestions();
        
        $keywordAnalysis = $this->analyzeKeywords();
        
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
                    $options = json_decode($answer->selected_options, true);
                    return is_array($options) ? $options : [];
                });
            
            $answersArray = $answers->toArray();
            $counts = array_count_values($answersArray);
            arsort($counts);
            
            return [
                'question_id' => $question->id,
                'question_text' => $question->question_text,
                'total_answers' => $question->answers_count,
                'distribution' => array_slice($counts, 0, 5, true)
            ];
        })->toArray(); 
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
        
        $data['completionStatus'] = [
            StatusQuestionnaire::where('status', 'completed')->count(),
            StatusQuestionnaire::where('status', 'in_progress')->count(),
            StatusQuestionnaire::where('status', 'not_started')->count()
        ];
        
        $data['monthlyTrend'] = $this->getMonthlyTrend();
        
        $data['multipleChoiceCharts'] = $this->getMultipleChoiceCharts();
        
        $data['scaleCharts'] = $this->getScaleCharts();
        
        $data['categoryQuestionsCharts'] = $this->getCategoryQuestionsCharts();
        
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
        
        $todayAnswers = AnswerQuestion::whereDate('answered_at', today())->count();
        $weekAnswers = AnswerQuestion::where('answered_at', '>=', now()->subWeek())->count();
        $monthAnswers = AnswerQuestion::where('answered_at', '>=', now()->subMonth())->count();
        
        $recentActivitiesCount = AnswerQuestion::where('answered_at', '>=', now()->subHours(24))->count();
        
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
                    AnswerQuestion::where('alumni_id', $alumniId)->delete();
                    StatusQuestionnaire::where('alumni_id', $alumniId)->delete();
                    AlumniAchievement::where('alumni_id', $alumniId)->delete();
                    
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
            
            array_shift($csvData);
            
            foreach ($csvData as $row) {
                if (count($row) >= 6) {
                    $email = trim($row[2] ?? '');
                    $nim = trim($row[0] ?? '');
                    
                    $existingEmail = User::where('email', $email)->exists();
                    $existingNIM = Alumni::where('nim', $nim)->exists();
                    
                    if (!$existingEmail && !$existingNIM) {
                        $user = User::create([
                            'email' => $email,
                            'password' => Hash::make('password123'),
                            'role' => 'alumni',
                            'email_verified_at' => now(),
                        ]);
                        
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
                'data' => $this->getFallbackData() 
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
                if ($answer->scale_value !== null) {
                    $scale = (int)$answer->scale_value;
                    if ($scale >= 4) $relevanceData['Sangat Erat']++;
                    elseif ($scale == 3) $relevanceData['Erat']++;
                    elseif ($scale == 2) $relevanceData['Cukup Erat']++;
                    elseif ($scale == 1) $relevanceData['Kurang Erat']++;
                    else $relevanceData['Tidak Sama Sekali']++;
                }
                elseif ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    if (strpos($answerText, 'sangat erat') !== false) $relevanceData['Sangat Erat']++;
                    elseif (strpos($answerText, 'erat') !== false) $relevanceData['Erat']++;
                    elseif (strpos($answerText, 'cukup') !== false) $relevanceData['Cukup Erat']++;
                    elseif (strpos($answerText, 'kurang') !== false) $relevanceData['Kurang Erat']++;
                    elseif (strpos($answerText, 'tidak') !== false) $relevanceData['Tidak Sama Sekali']++;
                }
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
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%metode%pembelajaran%')
                ->orWhere('question_text', 'like', '%cara%belajar%')
                ->orWhere('question_text', 'like', '%teknik%pengajaran%');
            })
            ->where('question_type', 'likert_per_row')
            ->get();
        
        if ($questions->isEmpty()) {
            return null;
        }
        
        $methodsData = [];
        $scaleLabels = ['1', '2', '3', '4', '5']; 
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('answer')
                ->get();
            
            if ($answers->isEmpty()) {
                continue;
            }
            
            $scaleCounts = [
                '1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0
            ];
            
            foreach ($answers as $answer) {
                try {
                    $answerData = json_decode($answer->answer, true);
                    if (is_array($answerData)) {
                        foreach ($answerData as $method => $scale) {
                            if (isset($scaleCounts[$scale])) {
                                $scaleCounts[$scale]++;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
            
            if ($question->row_items && is_array($question->row_items)) {
                foreach ($question->row_items as $itemKey => $itemLabel) {
                    $methodData = [
                        'name' => $itemLabel,
                        'values' => array_values($scaleCounts)
                    ];
                    $methodsData[] = $methodData;
                }
            } else {
                $methodsData[] = [
                    'name' => Str::limit($question->question_text, 50),
                    'values' => array_values($scaleCounts)
                ];
            }
        }
        
        if (empty($methodsData)) {
            return null;
        }
        
        $totalResponses = array_sum($methodsData[0]['values']);
        if ($totalResponses > 0) {
            foreach ($methodsData as &$method) {
                $method['values'] = array_map(function($count) use ($totalResponses) {
                    return round(($count / $totalResponses) * 100, 2);
                }, $method['values']);
            }
        }
        
        return [
            'methods' => $methodsData,
            'scales' => $scaleLabels,
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Metode Pembelajaran)'
        ];
    }

    /**
     * Get competence at graduation data for Chart 3.1
     */
    private function getRealCompetenceData()
    {
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%kompetensi%')
                ->orWhere('question_text', 'like', '%kemampuan%')
                ->orWhere('question_text', 'like', '%keahlian%')
                ->orWhere('question_text', 'like', '%skill%');
            })
            ->where('question_type', 'likert_per_row')
            ->get();
        
        if ($questions->isEmpty()) {
            return null;
        }
        
        $competencies = [];
        
        foreach ($questions as $question) {
            $answers = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('answer')
                ->get();
            
            if ($answers->isEmpty()) {
                continue;
            }
            
            $rowItems = [];
            if ($question->row_items && is_array($question->row_items)) {
                $rowItems = $question->row_items;
            } else {
                $rowItems = [
                    'ethics' => 'Etika',
                    'english' => 'Bahasa Inggris',
                    'teamwork' => 'Kerja Tim',
                    'expertise' => 'Keahlian Bidang',
                    'it_skills' => 'Keterampilan IT',
                    'communication' => 'Komunikasi',
                    'self_development' => 'Pengembangan Diri'
                ];
            }
            
            foreach ($rowItems as $key => $label) {
                if (!isset($competencies[$label])) {
                    $competencies[$label] = [0, 0, 0, 0, 0]; 
                }
            }
            
            foreach ($answers as $answer) {
                try {
                    $answerData = json_decode($answer->answer, true);
                    if (is_array($answerData)) {
                        foreach ($answerData as $competenceKey => $scale) {
                            foreach ($rowItems as $key => $label) {
                                if ($key === $competenceKey && isset($competencies[$label])) {
                                    $scaleIndex = (int)$scale - 1; 
                                    if ($scaleIndex >= 0 && $scaleIndex <= 4) {
                                        $competencies[$label][$scaleIndex]++;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        
        if (empty($competencies)) {
            return null;
        }
        
        foreach ($competencies as $competence => &$counts) {
            $total = array_sum($counts);
            if ($total > 0) {
                $counts = array_map(function($count) use ($total) {
                    return round(($count / $total) * 100, 2);
                }, $counts);
            }
        }
        
        return [
            'competencies' => $competencies,
            'scales' => ['1', '2', '3', '4', '5'], 
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Kompetensi)'
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
            $categoryId = $request->get('category_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $action = $request->get('action', 'download'); 
            
            $reportData = $this->preparePDFReportData($categoryId, $startDate, $endDate);
            
            $pdf = PDF::loadView('admin.views.questionnaire.export-pdf', $reportData);
            
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('enable_php', true);
            $pdf->setOption('dpi', 150);
            
            $filename = 'laporan-tracer-study-' . date('Y-m-d') . '.pdf';
            
            if ($action === 'preview') {
                return $pdf->stream($filename);
            } else {
                return $pdf->download($filename);
            }
            
        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            
            if ($request->has('debug') || $request->get('action') === 'preview') {
                return response()->view('admin.views.questionnaire.export-error', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Preview PDF report (alternatif)
     */
    public function previewPDFReport(Request $request)
    {
        $categoryId = $request->get('category_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $reportData = $this->preparePDFReportData($categoryId, $startDate, $endDate);
        
        $reportData['is_preview'] = true;
        
        return view('admin.views.questionnaire.export-pdf-preview', $reportData);
    }
    
    /**
     * Prepare data for PDF report - DATA REAL DAN DINAMIS
     */
    private function preparePDFReportData($categoryId = null, $startDate = null, $endDate = null)
    {
        $totalAlumni = Alumni::count();
        $alumniWithAnswers = StatusQuestionnaire::distinct('alumni_id')->count();
        $totalAnswers = AnswerQuestion::count();
        $totalQuestions = Question::count();
        
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
        
        $alumni = $alumniQuery->limit(50)->get(); 
        
        $chartData = $this->getTracerChartsDataForPDF();
        
        $categories = Category::withCount(['questionnaires', 'alumniStatuses'])
            ->orderBy('order')
            ->get();
        
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
            'title' => 'Laporan Hasil Kuesioner Alumni',
            'subtitle' => 'Sistem Tracer Study',
            'date' => now()->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i:s'),
            'period' => $startDate && $endDate ? 
                $startDate . ' sampai ' . $endDate : 'Semua Periode',
            'category_filter' => $categoryId ? 
                Category::find($categoryId)->name ?? 'Semua Kategori' : 'Semua Kategori',
            
            'total_alumni' => $totalAlumni,
            'alumni_with_answers' => $alumniWithAnswers,
            'total_answers' => $totalAnswers,
            'total_questions' => $totalQuestions,
            'completion_rate' => $totalAlumni > 0 ? 
                round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0,
            'response_rate' => $totalAlumni > 0 ? 
                round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0,
            
            'alumni' => $alumni,
            'categories' => $categories,
            'top_questions' => $topQuestions,
            'top_alumni' => $topAlumni,
            
            'chart_data' => $chartData,
            
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
            return [
                'graduate_status' => $this->getRealGraduateStatusData(),
                'waiting_time' => $this->getRealWaitingTimeData(),
                'study_work_relevance' => $this->getRealStudyWorkRelevanceData(),
                'work_level' => $this->getRealWorkLevelData(),
                'salary_range' => $this->getRealSalaryRangeData(),
                'learning_methods' => $this->getRealLearningMethodData(),
                'competence' => $this->getRealCompetenceData(),
                'funding_source' => $this->getRealFundingSourceData(),
                
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_questionnaires' => Questionnaire::count(),
                    'total_questions' => Question::count(),
                    'total_alumni_respondents' => StatusQuestionnaire::distinct('alumni_id')->count(),
                    'latest_response' => AnswerQuestion::max('answered_at') ? 
                        Carbon::parse(AnswerQuestion::max('answered_at'))->format('d F Y') : '-',
                    'most_active_category' => $this->getMostActiveCategory(),
                    'avg_completion_rate' => $this->getAverageCompletionRate()
                ]
            ];
            
        } catch (\Exception $e) {
            return [
                'graduate_status' => [
                    'labels' => ['Bekerja', 'Belum Bekerja', 'Studi Lanjut', 'Wirausaha'],
                    'values' => [65, 15, 10, 10],
                    'conclusion' => 'Data sedang dimuat...'
                ],
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_alumni_respondents' => StatusQuestionnaire::distinct('alumni_id')->count()
                ]
            ];
        }
    }

    /**
     * Get most active category
     */
    private function getMostActiveCategory()
    {
        $category = Category::withCount('alumniStatuses')
            ->orderBy('alumni_statuses_count', 'desc')
            ->first();
        
        return $category ? [
            'name' => $category->name,
            'count' => $category->alumni_statuses_count
        ] : null;
    }

    /**
     * Get average completion rate
     */
    private function getAverageCompletionRate()
    {
        $totalAlumni = Alumni::count();
        $totalCompleted = StatusQuestionnaire::where('status', 'completed')->count();
        
        return $totalAlumni > 0 ? round(($totalCompleted / $totalAlumni) * 100, 1) : 0;
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
            $categoryId = $request->get('category_id');
            $questionnaireId = $request->get('questionnaire_id');
            $alumniId = $request->get('alumni_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $format = $request->get('format', 'detailed');
            $action = $request->get('action', 'download'); 
            
            Log::info('Complete Answers PDF Export Parameters:', [
                'category_id' => $categoryId,
                'questionnaire_id' => $questionnaireId,
                'alumni_id' => $alumniId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'format' => $format,
                'action' => $action,
                'all_params' => $request->all()
            ]);
            
            $reportData = $this->prepareCompleteAnswersData(
                $categoryId, 
                $questionnaireId, 
                $alumniId, 
                $startDate, 
                $endDate,
                $format
            );
            
            $reportData['is_complete_export'] = true;
            $reportData['action'] = $action;
            $reportData['generated_at'] = now()->format('d F Y H:i:s');
            
            $pdf = Pdf::loadView('admin.views.questionnaire.export-complete-answers', $reportData);
            
            $pdf->setPaper('A4', $format === 'summary' ? 'portrait' : 'landscape');
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('enable_php', true);
            $pdf->setOption('dpi', 150);
            
            $filename = 'laporan-lengkap-jawaban-alumni-' . date('Y-m-d-H-i') . '.pdf';
            
            Log::info('PDF Action Decision:', [
                'action' => $action,
                'filename' => $filename
            ]);
            
            if ($action === 'preview') {
                Log::info('Streaming PDF for preview');
                return $pdf->stream($filename);
            } else {
                Log::info('Downloading PDF');
                return $pdf->download($filename);
            }
            
        } catch (\Exception $e) {
            Log::error('Complete Answers PDF Export Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengexport PDF: ' . $e->getMessage()
                ], 500);
            }
            
            if ($request->get('action') === 'preview') {
                return response()->view('admin.views.errors.pdf-export-error', [
                    'error' => $e->getMessage(),
                    'title' => 'Export Complete Answers Error'
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal mengexport PDF: ' . $e->getMessage())
                ->withInput();
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
        $answersQuery = AnswerQuestion::with([
            'alumni.user',
            'question.questionnaire.category',
            'detailedAnswers'
        ])->orderBy('answered_at', 'desc');
        
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
        
        $allAnswers = $answersQuery->get();
        
        $answersByAlumni = $allAnswers->groupBy('alumni_id');
        
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
                
                foreach ($questions as $question) {
                    $answer = $data['answers']->firstWhere('question_id', $question->id);
                    $row['q_' . $question->id] = $this->formatAnswerForMatrix($answer);
                }
                
                $answerMatrix[] = $row;
            }
        }
        
        return [
            'title' => 'Laporan Lengkap Jawaban Alumni',
            'subtitle' => 'Detail Semua Jawaban Kuesioner',
            'date' => now()->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i:s'),
            'format' => $format,
            
            'alumni_data' => $alumniData,
            'questions' => $questions,
            'all_answers' => $allAnswers,
            'answer_matrix' => $answerMatrix,
            
            'summary_stats' => $summaryStats,
            
            'filters' => [
                'category_id' => $categoryId,
                'questionnaire_id' => $questionnaireId,
                'alumni_id' => $alumniId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'filter_info' => $filterInfo,
                'has_filters' => $categoryId || $questionnaireId || $alumniId || $startDate || $endDate
            ],
            
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
        try {
            $categories = Category::all();
            $questionnaires = Questionnaire::all();
            $alumni = Alumni::has('answers')->limit(100)->get();
            
            return view('admin.views.questionnaire.export-complete-answers-form', 
                compact('categories', 'questionnaires', 'alumni'));
                
        } catch (\Exception $e) {
            Log::error('Error loading complete answers form: ' . $e->getMessage());
            
            return redirect()->route('admin.views.dashboard')
                ->with('error', 'Gagal memuat halaman export: ' . $e->getMessage());
        }
    }
    
    /**
     * View PDF preview for complete answers
     */
    public function previewCompleteAnswersPDF(Request $request)
    {
        $categoryId = $request->get('category_id');
        $questionnaireId = $request->get('questionnaire_id');
        $alumniId = $request->get('alumni_id');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $format = $request->get('format', 'detailed');
        
        $reportData = $this->prepareCompleteAnswersData(
            $categoryId,
            $questionnaireId,
            $alumniId,
            $startDate,
            $endDate,
            $format
        );
        
        $reportData['is_preview'] = true;
        $reportData['is_complete_export'] = true;
        
        return view('admin.views.questionnaire.export-complete-answers-preview', $reportData);
    }

    /**
     * Upload profile photo for admin.
     */
    public function adminUploadPhoto(Request $request, Admin $admin)
    {
        if (!Auth::user()->canEditAdmin()) {
            return redirect()->route('admin.views.users.admin.show', $admin->id)
                ->with('error', 'Anda tidak memiliki izin untuk mengupload foto admin');
        }

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($admin->user->pp_url && Storage::exists($admin->user->pp_url)) {
            Storage::delete($admin->user->pp_url);
        }

        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        $admin->user->update([
            'pp_url' => $path,
        ]);

        return redirect()->route('admin.views.users.admin.edit', $admin->id)
            ->with('success', 'Foto profil berhasil diupload');
    }

    /**
     * Delete profile photo for admin.
     */
    public function adminDeletePhoto(Admin $admin)
    {
        if (!Auth::user()->canEditAdmin()) {
            return redirect()->route('admin.views.users.admin.show', $admin->id)
                ->with('error', 'Anda tidak memiliki izin untuk menghapus foto admin');
        }

        if ($admin->user->pp_url && Storage::exists($admin->user->pp_url)) {
            Storage::delete($admin->user->pp_url);
        }

        $admin->user->update([
            'pp_url' => null,
        ]);

        return redirect()->route('admin.views.users.admin.edit', $admin->id)
            ->with('success', 'Foto profil berhasil dihapus');
    }

    /**
     * Verify admin email manually.
     */
    public function adminVerifyEmail(Admin $admin)
    {
        if (!Auth::user()->canEditAdmin()) {
            return redirect()->route('admin.views.users.admin.show', $admin->id)
                ->with('error', 'Anda tidak memiliki izin untuk memverifikasi email admin');
        }
        
        $admin->user->update(['email_verified_at' => now()]);
        
        return redirect()->route('admin.views.users.admin.edit', $admin->id)
            ->with('success', 'Email admin berhasil diverifikasi');
    }
}