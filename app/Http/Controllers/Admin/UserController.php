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
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminsExport;
use App\Exports\AlumniExport;


class UserController extends Controller
{
    /**
     * Display a listing of alumni users.
     */
    public function alumniIndex(Request $request)
    {
        $query = Alumni::with('user');
        
        // Filter search by name or NIM
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                ->orWhere('nim', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', '%' . $search . '%');
                });
            });
        }
        
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        // Filter by email status
        if ($request->has('email_status') && $request->email_status) {
            if ($request->email_status == 'verified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNotNull('email_verified_at');
                });
            } elseif ($request->email_status == 'unverified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNull('email_verified_at');
                });
            }
        }
        
        // Filter by points
        if ($request->has('points_filter') && $request->points_filter) {
            if ($request->points_filter == 'has_points') {
                $query->where('points', '>', 0);
            } elseif ($request->points_filter == 'no_points') {
                $query->where('points', 0)->orWhereNull('points');
            } elseif ($request->points_filter == 'high_points') {
                $query->where('points', '>', 100);
            }
        }
        
        $alumni = $query->latest()->paginate(10);
        
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
    public function adminIndex(Request $request)
    {
        $query = Admin::with('user');
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', '%' . $search . '%');
                });
            });
        }
        
        if ($request->has('job_title') && $request->job_title) {
            $query->where('job_title', $request->job_title);
        }
        
        if ($request->has('status') && $request->status) {
            if ($request->status == 'verified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNotNull('email_verified_at');
                });
            } elseif ($request->status == 'unverified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNull('email_verified_at');
                });
            }
        }
        
        $admins = $query->latest()->paginate(10);
        
        return view('admin.views.users.admin.index', compact('admins'));
    }

    /**
     * Export admin data.
     */
    public function exportAdmins(Request $request)
    {
        $query = Admin::with('user');
        
        // Apply filters
        if ($request->has('job_title') && $request->job_title) {
            $query->where('job_title', $request->job_title);
        }
        
        if ($request->has('status') && $request->status) {
            if ($request->status == 'verified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNotNull('email_verified_at');
                });
            } elseif ($request->status == 'unverified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNull('email_verified_at');
                });
            }
        }
        
        $admins = $query->get();
        
        $format = $request->get('format', 'csv');
        
        if ($format === 'pdf') {
            // Export to PDF
            $pdf = PDF::loadView('admin.views.users.exports.admins-pdf', compact('admins'));
            $pdf->setPaper('A4', 'landscape'); // Tambahkan ini
            return $pdf->download('data-admin-' . date('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            // Export to Excel
            return Excel::download(new AdminsExport($admins), 'data-admin-' . date('Y-m-d') . '.xlsx');
        } else {
            // Export to CSV (default)
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="data-admin-' . date('Y-m-d') . '.csv"',
            ];
            
            $callback = function() use ($admins) {
                $file = fopen('php://output', 'w');
                
                // CSV Header
                fputcsv($file, [
                    'Nama Lengkap',
                    'Email',
                    'Jabatan',
                    'No. Telepon',
                    'Status Verifikasi',
                    'Tanggal Bergabung',
                    'Terakhir Login'
                ]);
                
                // CSV Data
                foreach ($admins as $admin) {
                    fputcsv($file, [
                        $admin->fullname,
                        $admin->user->email,
                        $admin->job_title,
                        $admin->phone ?? '-',
                        $admin->user->email_verified_at ? 'Terverifikasi' : 'Belum Verifikasi',
                        $admin->created_at->format('d-m-Y'),
                        $admin->user->last_login_at ? $admin->user->last_login_at->format('d-m-Y H:i') : '-'
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
        }
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
        
        // Apply filters from modal
        if ($request->has('study_program') && $request->study_program) {
            $query->where('study_program', $request->study_program);
        }
        
        if ($request->has('graduation_year') && $request->graduation_year) {
            $query->whereYear('graduation_date', $request->graduation_year);
        }
        
        if ($request->has('email_status') && $request->email_status) {
            if ($request->email_status == 'verified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNotNull('email_verified_at');
                });
            } elseif ($request->email_status == 'unverified') {
                $query->whereHas('user', function($userQuery) {
                    $userQuery->whereNull('email_verified_at');
                });
            }
        }
        
        // Also apply filters from search form if exists
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                ->orWhere('nim', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('email', 'like', '%' . $search . '%');
                });
            });
        }
        
        if ($request->has('points_filter') && $request->points_filter) {
            if ($request->points_filter == 'has_points') {
                $query->where('points', '>', 0);
            } elseif ($request->points_filter == 'no_points') {
                $query->where('points', 0)->orWhereNull('points');
            } elseif ($request->points_filter == 'high_points') {
                $query->where('points', '>', 100);
            }
        }
        
        $alumni = $query->get();
        
        $format = $request->get('format', 'csv');
        
        if ($format === 'pdf') {
            // Export to PDF
            $pdf = PDF::loadView('admin.views.users.exports.alumni-pdf', compact('alumni'));
            $pdf->setPaper('A4', 'landscape'); // Tambahkan ini
            return $pdf->download('data-alumni-' . date('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            // Export to Excel
            return Excel::download(new AlumniExport($alumni), 'data-alumni-' . date('Y-m-d') . '.xlsx');
        } else {
            // Export to CSV (default)
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="data-alumni-' . date('Y-m-d') . '.csv"',
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
                    'Tanggal Bergabung',
                    'Terakhir Login'
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
                        $item->created_at->format('d-m-Y H:i:s'),
                        $item->user->last_login_at ? $item->user->last_login_at->format('d-m-Y H:i') : '-'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
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
            // 1. Status Lulusan Saat Ini
            $statusData = $this->getRealGraduateStatusData();
            
            // 2. Waktu Tunggu Mendapat Pekerjaan
            $waitingTimeData = $this->getRealWaitingTimeData();
            
            // 3. Hubungan Bidang Studi dengan Pekerjaan - DENGAN VALIDASI
            $relevanceData = $this->getRealStudyWorkRelevanceData();
            if (!isset($relevanceData['labels']) || !isset($relevanceData['values'])) {
                $relevanceData = [
                    'labels' => ['Sangat Erat', 'Erat', 'Cukup Erat', 'Kurang Erat', 'Tidak Sama Sekali'],
                    'values' => [0, 0, 0, 0, 0],
                    'conclusion' => 'Data sedang dimuat...'
                ];
            }
            
            // 4. Tingkat Tempat Kerja
            $workLevelData = $this->getRealWorkLevelData();
            
            // 5. Kisaran Gaji - DENGAN VALIDASI
            $salaryRangeData = $this->getRealSalaryRangeData();
            if (!isset($salaryRangeData['labels']) || !isset($salaryRangeData['values'])) {
                $salaryRangeData = [
                    'labels' => ['< Rp1.000.000', 'Rp1.000.000 - Rp3.000.000', 'Rp3.000.001 - Rp5.000.000', 'Rp5.000.001 - Rp10.000.000', '> Rp10.000.000'],
                    'values' => [0, 0, 0, 0, 0],
                    'conclusion' => 'Data sedang dimuat...'
                ];
            }
            
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
            // Return fallback data yang AMAN
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'data' => $this->getSafeFallbackData()
            ], 500);
        }
    }

    /**
     * Safe fallback data dengan struktur yang konsisten
     */
    private function getSafeFallbackData()
    {
        return [
            'graduate_status' => [
                'labels' => ['Data sedang dimuat...'],
                'values' => [100],
                'conclusion' => 'System - Menunggu Data'
            ],
            'study_work_relevance' => [
                'labels' => ['Sangat Erat', 'Erat', 'Cukup Erat', 'Kurang Erat', 'Tidak Sama Sekali'],
                'values' => [0, 0, 0, 0, 0],
                'conclusion' => 'Data sedang dimuat...'
            ],
            'salary_range' => [
                'labels' => ['< Rp1.000.000', 'Rp1.000.000 - Rp3.000.000', 'Rp3.000.001 - Rp5.000.000', 'Rp5.000.001 - Rp10.000.000', '> Rp10.000.000'],
                'values' => [0, 0, 0, 0, 0],
                'conclusion' => 'Data sedang dimuat...'
            ]
        ];
    }

    /**
     * Get graduate status data for Chart 1.1
     */
    private function getRealGraduateStatusData($startDate = null, $endDate = null)
    {
        $query = StatusQuestionnaire::select('category_id', DB::raw('COUNT(*) as count'))
            ->with('category');
        
        // Filter berdasarkan tanggal jawaban
        if ($startDate || $endDate) {
            $query->whereHas('alumni.answers', function($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('answered_at', '>=', $startDate);
                }
                if ($endDate) {
                    $q->whereDate('answered_at', '<=', $endDate);
                }
            });
        }
        
        $statusCounts = $query->groupBy('category_id')->get();
        
        $labels = [];
        $values = [];
        $total = $statusCounts->sum('count');
        
        foreach ($statusCounts as $status) {
            $categoryName = $status->category ? $status->category->name : 'Unknown';
            $labels[] = $categoryName;
            $values[] = $total > 0 ? round(($status->count / $total) * 100, 2) : 0;
        }
        
        $conclusion = $total > 0 ? 
        "Total: {$total} alumni telah memilih kategori pada periode ini. " : 
        "Belum ada data kategori dari alumni";
    
        if ($startDate || $endDate) {
            $conclusion .= " (Periode: " . $this->formatDateRange($startDate, $endDate) . ")";
        }
        
        return [
            'labels' => $labels,
            'values' => $values,
            'data_source' => 'Database (Dinamis - Berdasarkan Kategori)',
            'conclusion' => $conclusion,
            'date_filtered' => !empty($startDate) || !empty($endDate)
        ];
    }

    /**
     * Get waiting time data for Chart 1.3
     */
    private function getRealWaitingTimeData($startDate = null, $endDate = null)
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
            $answersQuery = AnswerQuestion::where('question_id', $question->id)
                ->whereNotNull('answer');
            
            // FILTER TANGGAL
            if ($startDate) {
                $answersQuery->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $answersQuery->whereDate('answered_at', '<=', $endDate);
            }
            
            $answers = $answersQuery->get();
            
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
    private function getRealStudyWorkRelevanceData($startDate = null, $endDate = null)
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
        
        // Inisialisasi dengan SEMUA kemungkinan nilai (walaupun 0)
        $relevanceData = [
            'Sangat Erat' => 0,
            'Erat' => 0,
            'Cukup Erat' => 0,
            'Kurang Erat' => 0,
            'Tidak Sama Sekali' => 0
        ];
        
        $totalAnswers = 0;
        
        foreach ($questions as $question) {
            $answersQuery = AnswerQuestion::where('question_id', $question->id);
            
            // Filter tanggal jika ada
            if ($startDate) {
                $answersQuery->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $answersQuery->whereDate('answered_at', '<=', $endDate);
            }
            
            $answers = $answersQuery->get();
            
            foreach ($answers as $answer) {
                // Untuk skala Likert
                if ($answer->scale_value !== null) {
                    $scale = (int)$answer->scale_value;
                    if ($scale >= 4) {
                        $relevanceData['Sangat Erat']++;
                    } elseif ($scale == 3) {
                        $relevanceData['Erat']++;
                    } elseif ($scale == 2) {
                        $relevanceData['Cukup Erat']++;
                    } elseif ($scale == 1) {
                        $relevanceData['Kurang Erat']++;
                    } else {
                        $relevanceData['Tidak Sama Sekali']++;
                    }
                    $totalAnswers++;
                }
                // Untuk pilihan teks
                elseif ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    if (strpos($answerText, 'sangat erat') !== false) {
                        $relevanceData['Sangat Erat']++;
                    } elseif (strpos($answerText, 'erat') !== false) {
                        $relevanceData['Erat']++;
                    } elseif (strpos($answerText, 'cukup') !== false) {
                        $relevanceData['Cukup Erat']++;
                    } elseif (strpos($answerText, 'kurang') !== false) {
                        $relevanceData['Kurang Erat']++;
                    } elseif (strpos($answerText, 'tidak') !== false) {
                        $relevanceData['Tidak Sama Sekali']++;
                    } else {
                        // Jika tidak dikenali, masukkan ke "Cukup Erat" sebagai default
                        $relevanceData['Cukup Erat']++;
                    }
                    $totalAnswers++;
                }
                // Untuk pilihan ganda
                elseif ($answer->selected_options) {
                    $selected = json_decode($answer->selected_options, true);
                    if (is_array($selected)) {
                        $selectedText = strtolower(implode(' ', $selected));
                        if (strpos($selectedText, 'sangat erat') !== false) {
                            $relevanceData['Sangat Erat']++;
                        } elseif (strpos($selectedText, 'erat') !== false) {
                            $relevanceData['Erat']++;
                        } elseif (strpos($selectedText, 'cukup') !== false) {
                            $relevanceData['Cukup Erat']++;
                        } elseif (strpos($selectedText, 'kurang') !== false) {
                            $relevanceData['Kurang Erat']++;
                        } elseif (strpos($selectedText, 'tidak') !== false) {
                            $relevanceData['Tidak Sama Sekali']++;
                        } else {
                            $relevanceData['Cukup Erat']++;
                        }
                        $totalAnswers++;
                    }
                }
            }
        }
        
        // Hitung persentase
        $values = [];
        if ($totalAnswers > 0) {
            foreach ($relevanceData as $key => $count) {
                $values[] = round(($count / $totalAnswers) * 100, 2);
            }
        } else {
            // Jika tidak ada data, set semua ke 0
            $values = [0, 0, 0, 0, 0];
        }
        
        // Buat kesimpulan
        $conclusion = '';
        if ($totalAnswers > 0) {
            $eratCount = $relevanceData['Sangat Erat'] + $relevanceData['Erat'];
            $eratPercentage = round(($eratCount / $totalAnswers) * 100, 2);
            $conclusion = "Dari {$totalAnswers} jawaban, {$eratCount} alumni ({$eratPercentage}%) " .
                        "merasa hubungan studi-pekerjaan erat/sangat erat";
        } else {
            $conclusion = "Belum ada data relevansi untuk periode ini";
        }
        
        // Tambahkan info tanggal filter jika ada
        if ($startDate || $endDate) {
            $dateRange = $this->formatDateRange($startDate, $endDate);
            $conclusion .= " (Periode: {$dateRange})";
        }
        
        return [
            'labels' => array_keys($relevanceData),
            'values' => $values,
            'counts' => array_values($relevanceData),
            'total' => $totalAnswers,
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Relevansi)',
            'conclusion' => $conclusion,
            'has_data' => $totalAnswers > 0
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
    private function getRealSalaryRangeData($startDate = null, $endDate = null)
    {
        $questions = Question::where(function($q) {
                $q->where('question_text', 'like', '%gaji%')
                ->orWhere('question_text', 'like', '%pendapatan%')
                ->orWhere('question_text', 'like', '%penghasilan%')
                ->orWhere('question_text', 'like', '%salary%')
                ->orWhere('question_text', 'like', '%income%');
            })
            ->get();
        
        // Inisialisasi SEMUA range (walaupun 0)
        $salaryRanges = [
            '< Rp1.000.000' => 0,
            'Rp1.000.000 - Rp3.000.000' => 0,
            'Rp3.000.001 - Rp5.000.000' => 0,
            'Rp5.000.001 - Rp10.000.000' => 0,
            '> Rp10.000.000' => 0
        ];
        
        $totalAnswers = 0;
        
        foreach ($questions as $question) {
            $answersQuery = AnswerQuestion::where('question_id', $question->id);
            
            if ($startDate) {
                $answersQuery->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $answersQuery->whereDate('answered_at', '<=', $endDate);
            }
            
            $answers = $answersQuery->get();
            
            foreach ($answers as $answer) {
                if ($answer->answer) {
                    $answerText = strtolower($answer->answer);
                    
                    // Ekstrak angka dari jawaban
                    preg_match_all('/\d+/', $answerText, $matches);
                    if (!empty($matches[0])) {
                        $numbers = array_map('intval', $matches[0]);
                        $maxNumber = max($numbers);
                        
                        if ($maxNumber < 1000) {
                            $salaryRanges['< Rp1.000.000']++;
                        } elseif ($maxNumber <= 3000) {
                            $salaryRanges['Rp1.000.000 - Rp3.000.000']++;
                        } elseif ($maxNumber <= 5000) {
                            $salaryRanges['Rp3.000.001 - Rp5.000.000']++;
                        } elseif ($maxNumber <= 10000) {
                            $salaryRanges['Rp5.000.001 - Rp10.000.000']++;
                        } else {
                            $salaryRanges['> Rp10.000.000']++;
                        }
                        $totalAnswers++;
                    }
                }
            }
        }
        
        // Hitung persentase
        $values = [];
        if ($totalAnswers > 0) {
            foreach ($salaryRanges as $range => $count) {
                $values[] = round(($count / $totalAnswers) * 100, 2);
            }
        } else {
            $values = [0, 0, 0, 0, 0];
        }
        
        $conclusion = $totalAnswers > 0 ? 
            "Dari {$totalAnswers} jawaban tentang gaji" :
            "Belum ada data gaji untuk periode ini";
        
        if ($startDate || $endDate) {
            $dateRange = $this->formatDateRange($startDate, $endDate);
            $conclusion .= " (Periode: {$dateRange})";
        }
        
        return [
            'labels' => array_keys($salaryRanges),
            'values' => $values,
            'counts' => array_values($salaryRanges),
            'total' => $totalAnswers,
            'data_source' => 'Database (Dinamis - Analisis Pertanyaan Gaji)',
            'conclusion' => $conclusion,
            'has_data' => $totalAnswers > 0
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
     * Validasi tanggal di controller
     */
    private function validateDateRange($startDate, $endDate)
    {
        if ($startDate && $endDate) {
            $start = strtotime($startDate);
            $end = strtotime($endDate);
            
            if ($start > $end) {
                throw new \Exception('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
            }
            
            $today = strtotime(date('Y-m-d'));
            if ($start > $today) {
                Log::warning('Start date is in future: ' . $startDate);
            }
        }
        
        return true;
    }

    /**
     * Export questionnaire results to PDF
     */
    public function exportQuestionnaireResultsPDF(Request $request)
    {
        try {
            // DEBUG: Log parameter yang diterima
            Log::info('PDF Export Parameters:', [
                'category_id' => $request->get('category_id'),
                'start_date' => $request->get('start_date'),
                'end_date' => $request->get('end_date'),
                'action' => $request->get('action'), // PASTIKAN INI ADA!
                'all_params' => $request->all()
            ]);
            
            // Parameter filter
            $categoryId = $request->get('category_id');
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $action = $request->get('action', 'download'); // Default ke download
            
            // Validasi action
            if (!in_array($action, ['preview', 'download'])) {
                $action = 'download';
            }
            
            // Get data untuk laporan
            $reportData = $this->preparePDFReportData($categoryId, $startDate, $endDate);
            
            // Load view PDF
            $pdf = PDF::loadView('admin.views.questionnaire.export-pdf', $reportData);
            
            // Set options PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('defaultFont', 'DejaVu Sans');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('enable_php', true);
            $pdf->setOption('dpi', 150);
            
            $filename = 'laporan-kuesioner-alumni-' . date('Y-m-d') . '.pdf';
            
            // DEBUG: Log action decision
            Log::info('PDF Action Decision:', [
                'action' => $action,
                'filename' => $filename
            ]);
            
            if ($action === 'preview') {
                // Tampilkan preview di browser (STREAM)
                return $pdf->stream($filename);
            } else {
                // Download file
                return $pdf->download($filename);
            }
            
        } catch (\Exception $e) {
            Log::error('PDF Export Error: ' . $e->getMessage());
            
            // Jika preview, tampilkan error page
            if ($request->get('action') === 'preview') {
                return response()->view('admin.views.errors.pdf-export-error', [
                    'error' => $e->getMessage(),
                    'title' => 'Export PDF Error'
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
     * Prepare data for PDF report
     */
    private function preparePDFReportData($categoryId = null, $startDate = null, $endDate = null)
    {
        // 1. DATA STATISTIK dengan filter tanggal
        $totalAlumni = Alumni::count();
        
        // Query untuk alumni dengan jawaban dengan filter tanggal
        $alumniWithAnswersQuery = StatusQuestionnaire::distinct('alumni_id');
        
        if ($startDate || $endDate) {
            $alumniWithAnswersQuery->whereHas('alumni.answers', function($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('answered_at', '>=', $startDate);
                }
                if ($endDate) {
                    $q->whereDate('answered_at', '<=', $endDate);
                }
            });
        }
        
        $alumniWithAnswers = $alumniWithAnswersQuery->count();
        
        // Total answers dengan filter tanggal
        $answersQuery = AnswerQuestion::query();
        if ($startDate) {
            $answersQuery->whereDate('answered_at', '>=', $startDate);
        }
        if ($endDate) {
            $answersQuery->whereDate('answered_at', '<=', $endDate);
        }
        $totalAnswers = $answersQuery->count();
        
        $totalQuestions = Question::count();
        
        // 2. DATA ALUMNI DENGAN JAWABAN dengan filter
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
        $chartData = $this->getTracerChartsDataForPDF($startDate, $endDate);
        
        // 4. DATA KATEGORI
        $categories = Category::withCount(['questionnaires', 'alumniStatuses'])
            ->orderBy('order')
            ->get();
        
        // 5. PERTANYAAN PALING SERING DIJAWAB dengan filter tanggal
        $topQuestionsQuery = Question::withCount(['answers' => function($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('answered_at', '<=', $endDate);
            }
        }])
        ->orderBy('answers_count', 'desc')
        ->limit(10);
        
        $topQuestions = $topQuestionsQuery->get()
            ->map(function($question) {
                return [
                    'text' => Str::limit($question->question_text, 100),
                    'type' => $question->question_type,
                    'answers_count' => $question->answers_count,
                    'questionnaire' => $question->questionnaire->name ?? '-',
                    'category' => $question->questionnaire->category->name ?? '-'
                ];
            });
        
        // 6. ALUMNI TOP - PERBAIKAN QUERY DISINI!
        $topAlumniQuery = Alumni::withCount(['answers' => function($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('answered_at', '<=', $endDate);
            }
        }])
        ->withSum(['answers' => function($query) use ($startDate, $endDate) {
            if ($startDate) {
                $query->whereDate('answered_at', '>=', $startDate);
            }
            if ($endDate) {
                $query->whereDate('answered_at', '<=', $endDate);
            }
        }], 'points')
        ->orderBy('answers_sum_points', 'desc') // PERBAIKAN: gunakan 'answers_sum_points' bukan 'total_points'
        ->limit(10);
        
        $topAlumni = $topAlumniQuery->get()
            ->map(function($alumni) use ($totalQuestions) {
                return [
                    'name' => $alumni->fullname,
                    'nim' => $alumni->nim,
                    'study_program' => $alumni->study_program,
                    'total_answers' => $alumni->answers_count,
                    'total_points' => $alumni->answers_sum_points ?? 0,
                    'completion_rate' => $totalQuestions > 0 ? 
                        round(($alumni->answers_count / $totalQuestions) * 100, 1) : 0
                ];
            });
        
        // Hitung response rate dengan pengecekan division by zero
        $responseRate = $totalAlumni > 0 ? 
            round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0;
        
        // Format period
        $period = 'Semua Periode';
        if ($startDate && $endDate) {
            $period = date('d F Y', strtotime($startDate)) . ' sampai ' . date('d F Y', strtotime($endDate));
        } elseif ($startDate) {
            $period = 'Mulai ' . date('d F Y', strtotime($startDate));
        } elseif ($endDate) {
            $period = 'Sampai ' . date('d F Y', strtotime($endDate));
        }
        
        // Cek apakah ada data berdasarkan filter
        $hasDataBasedOnFilter = $totalAnswers > 0;
        $isDateFiltered = !empty($startDate) || !empty($endDate);
        
        return [
            // Metadata
            'title' => 'Laporan Hasil Kuesioner Alumni',
            'subtitle' => 'Sistem Tracer Study',
            'date' => now()->format('d F Y'),
            'generated_at' => now()->format('d F Y H:i:s'),
            'period' => $period,
            'category_filter' => $categoryId ? 
                Category::find($categoryId)->name ?? 'Semua Kategori' : 'Semua Kategori',
            'is_date_filtered' => $isDateFiltered,
            'filter_start_date' => $startDate,
            'filter_end_date' => $endDate,
            'filter_date_range' => $this->formatDateRange($startDate, $endDate),
            'has_data_for_period' => $hasDataBasedOnFilter,
            // 'has_data' => $hasDataBasedOnFilter,
            
            // Data Statistik
            'total_alumni' => $totalAlumni,
            'alumni_with_answers' => $alumniWithAnswers,
            'total_answers' => $totalAnswers,
            'total_questions' => $totalQuestions,
            'completion_rate' => $totalAlumni > 0 ? 
                round(($alumniWithAnswers / $totalAlumni) * 100, 2) : 0,
            'response_rate' => $responseRate,
            
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
    private function getTracerChartsDataForPDF($startDate = null, $endDate = null)
    {
        try {
            // Tambahkan filter tanggal ke SEMUA method chart data
            return [
                // Data utama dari dashboard DENGAN FILTER TANGGAL
                'graduate_status' => $this->getRealGraduateStatusData($startDate, $endDate),
                'waiting_time' => $this->getRealWaitingTimeData($startDate, $endDate),
                'study_work_relevance' => $this->getRealStudyWorkRelevanceData($startDate, $endDate),
                'work_level' => $this->getRealWorkLevelData($startDate, $endDate),
                'salary_range' => $this->getRealSalaryRangeData($startDate, $endDate),
                'learning_methods' => $this->getRealLearningMethodData($startDate, $endDate),
                'competence' => $this->getRealCompetenceData($startDate, $endDate),
                'funding_source' => $this->getRealFundingSourceData($startDate, $endDate),
                
                // Ringkasan statistik DENGAN FILTER TANGGAL
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_questionnaires' => Questionnaire::count(),
                    'total_questions' => Question::count(),
                    'total_alumni_respondents' => $this->getAlumniRespondentsCount($startDate, $endDate),
                    'latest_response' => $this->getLatestResponseDate($startDate, $endDate),
                    'most_active_category' => $this->getMostActiveCategory($startDate, $endDate),
                    'avg_completion_rate' => $this->getAverageCompletionRate($startDate, $endDate),
                    'date_filter_applied' => !empty($startDate) || !empty($endDate),
                    'date_range' => $this->formatDateRange($startDate, $endDate)
                ]
            ];
            
        } catch (\Exception $e) {
            // Fallback data minimal
            return [
                'graduate_status' => [
                    'labels' => ['Data sedang dimuat...'],
                    'values' => [100],
                    'conclusion' => 'Data tidak tersedia untuk periode ini.'
                ],
                'summary' => [
                    'total_categories' => Category::count(),
                    'total_alumni_respondents' => 0,
                    'date_filter_applied' => !empty($startDate) || !empty($endDate),
                    'date_range' => $this->formatDateRange($startDate, $endDate)
                ]
            ];
        }
    }

    /**
     * Get alumni respondents count with date filter
     */
    private function getAlumniRespondentsCount($startDate = null, $endDate = null)
    {
        $query = StatusQuestionnaire::distinct('alumni_id');
        
        if ($startDate || $endDate) {
            $query->whereHas('alumni.answers', function($q) use ($startDate, $endDate) {
                if ($startDate) {
                    $q->whereDate('answered_at', '>=', $startDate);
                }
                if ($endDate) {
                    $q->whereDate('answered_at', '<=', $endDate);
                }
            });
        }
        
        return $query->count();
    }

    /**
     * Get latest response date with filter
     */
    private function getLatestResponseDate($startDate = null, $endDate = null)
    {
        $query = AnswerQuestion::query();
        
        if ($startDate) {
            $query->whereDate('answered_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('answered_at', '<=', $endDate);
        }
        
        $latest = $query->max('answered_at');
        
        return $latest ? Carbon::parse($latest)->format('d F Y') : '-';
    }

    /**
     * Format date range for display
     */
    private function formatDateRange($startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return date('d F Y', strtotime($startDate)) . ' - ' . date('d F Y', strtotime($endDate));
        } elseif ($startDate) {
            return 'Mulai ' . date('d F Y', strtotime($startDate));
        } elseif ($endDate) {
            return 'Sampai ' . date('d F Y', strtotime($endDate));
        }
        
        return 'Semua Periode';
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
        try {
            $categories = Category::all();
            
            // Hitung statistik untuk preview - PERBAIKAN QUERY DISINI
            $totalAlumni = Alumni::count();
            $totalAnswers = AnswerQuestion::count();
            $alumniWithAnswers = StatusQuestionnaire::distinct('alumni_id')->count();
            $responseRate = $totalAlumni > 0 ? round(($alumniWithAnswers / $totalAlumni) * 100, 1) : 0;
            
            return view('admin.views.questionnaire.export-pdf-form', compact(
                'categories',
                'totalAlumni',
                'totalAnswers',
                'responseRate'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error loading PDF export form: ' . $e->getMessage());
            
            // Fallback dengan data minimal
            $categories = collect();
            return view('admin.views.questionnaire.export-pdf-form', compact('categories'))
                ->with('error', 'Gagal memuat data statistik: ' . $e->getMessage());
        }
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
     * Prepare COMPLETE answers data
     */
    private function prepareCompleteAnswersData(
        $categoryId = null, 
        $questionnaireId = null, 
        $alumniId = null, 
        $startDate = null, 
        $endDate = null,
        $format = 'detailed'
    ) {
        // 1. QUERY UTAMA: AMBIL SEMUA JAWABAN dengan filter tanggal yang benar
        $answersQuery = AnswerQuestion::with([
            'alumni.user',
            'question.questionnaire.category',
            'detailedAnswers'
        ]);
        
        // Apply filters dengan pengecekan null yang benar
        if (!empty($categoryId)) {
            $answersQuery->whereHas('question.questionnaire', function($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            });
        }
        
        if (!empty($questionnaireId)) {
            $answersQuery->whereHas('question', function($q) use ($questionnaireId) {
                $q->where('questionnaire_id', $questionnaireId);
            });
        }
        
        if (!empty($alumniId)) {
            $answersQuery->where('alumni_id', $alumniId);
        }
        
        // Filter tanggal - PERBAIKAN PENTING!
        if (!empty($startDate)) {
            $answersQuery->whereDate('answered_at', '>=', $startDate);
        }
        
        if (!empty($endDate)) {
            $answersQuery->whereDate('answered_at', '<=', $endDate);
        }
        
        // Get all answers (tidak dibatasi untuk PDF lengkap)
        $allAnswers = $answersQuery->orderBy('answered_at', 'desc')->get();
        
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
        
        // 4. GET QUESTIONS DATA (untuk header table) - dengan filter yang sama
        $questionsQuery = Question::with(['questionnaire.category']);
        
        if (!empty($categoryId)) {
            $questionsQuery->whereHas('questionnaire', function($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            });
        }
        
        if (!empty($questionnaireId)) {
            $questionsQuery->where('questionnaire_id', $questionnaireId);
        }
        
        $questions = $questionsQuery->orderByRaw('questionnaire_id, `order`')->get();
        
        // 5. GET SUMMARY STATISTICS dengan handle division by zero
        $totalAlumniCount = count($alumniData);
        $totalAnswersCount = $allAnswers->count();
        $totalQuestionsCount = $questions->count();
        
        // Hitung rata-rata dengan pengecekan nol
        $avgAnswersPerAlumni = $totalAlumniCount > 0 
            ? round($totalAnswersCount / $totalAlumniCount, 1) 
            : 0;
        
        // Hitung completion rate dengan pengecekan nol
        $completionRate = 0;
        if ($totalQuestionsCount > 0 && $totalAlumniCount > 0) {
            $totalPossibleAnswers = $totalQuestionsCount * $totalAlumniCount;
            if ($totalPossibleAnswers > 0) {
                $completionRate = round(($totalAnswersCount / $totalPossibleAnswers) * 100, 1);
            }
        }
        
        // Format date range
        $dateRange = '-';
        if ($allAnswers->isNotEmpty()) {
            $minDate = $allAnswers->min('answered_at');
            $maxDate = $allAnswers->max('answered_at');
            
            if ($minDate && $maxDate) {
                $dateRange = $minDate->format('d M Y') . ' - ' . $maxDate->format('d M Y');
            }
        }
        
        $summaryStats = [
            'total_alumni' => $totalAlumniCount,
            'total_answers' => $totalAnswersCount,
            'total_questions' => $totalQuestionsCount,
            'total_points' => $allAnswers->sum('points'),
            'avg_answers_per_alumni' => $avgAnswersPerAlumni,
            'completion_rate' => $completionRate,
            'date_range' => $dateRange,
            'has_data' => $totalAnswersCount > 0, // Flag untuk cek apakah ada data
            'filtered_by_date' => !empty($startDate) || !empty($endDate)
        ];
        
        // 6. GET FILTER INFO
        $filterInfo = [];
        if (!empty($categoryId)) {
            $category = Category::find($categoryId);
            $filterInfo['category'] = $category ? $category->name : '-';
        }
        
        if (!empty($questionnaireId)) {
            $questionnaire = Questionnaire::find($questionnaireId);
            $filterInfo['questionnaire'] = $questionnaire ? $questionnaire->name : '-';
        }
        
        if (!empty($alumniId)) {
            $alumni = Alumni::find($alumniId);
            $filterInfo['alumni'] = $alumni ? $alumni->fullname . ' (' . $alumni->nim . ')' : '-';
        }
        
        // Tambahkan info tanggal ke filter
        if (!empty($startDate)) {
            $filterInfo['start_date'] = date('d F Y', strtotime($startDate));
        }
        
        if (!empty($endDate)) {
            $filterInfo['end_date'] = date('d F Y', strtotime($endDate));
        }
        
        // 7. PREPARE MATRIX DATA (untuk format summary)
        $answerMatrix = [];
        if ($format === 'summary' && $totalAlumniCount > 0) {
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
                'has_filters' => !empty($categoryId) || !empty($questionnaireId) || 
                            !empty($alumniId) || !empty($startDate) || !empty($endDate)
            ],
            
            // Options
            'show_all_details' => true,
            'max_answers_per_page' => $format === 'detailed' ? 15 : 50,
            
            // Tambahan untuk handle empty data
            'has_no_data' => $totalAnswersCount === 0
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