<?php

namespace App\Http\Controllers;

use App\Models\Alumni;
use App\Models\StatusQuestionnaire;
use App\Models\AlumniAchievement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * Tampilkan halaman leaderboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        // Ambil parameter untuk filter
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search', '');
        
        // Query untuk ranking dengan window function
        $rankQuery = Alumni::select([
                'alumnis.id',
                'alumnis.fullname',
                'alumnis.nim',
                'alumnis.study_program',
                'alumnis.points',
                'alumnis.graduation_date',
                'users.pp_url',
                DB::raw('(SELECT COUNT(*) + 1 FROM alumnis a2 WHERE a2.points > alumnis.points) as ranking')
            ])
            ->join('users', 'alumnis.user_id', '=', 'users.id')
            ->where('alumnis.points', '>', 0);
        
        // Filter berdasarkan pencarian
        if ($search) {
            $rankQuery->where(function($q) use ($search) {
                $q->where('alumnis.fullname', 'LIKE', "%{$search}%")
                  ->orWhere('alumnis.nim', 'LIKE', "%{$search}%")
                  ->orWhere('alumnis.study_program', 'LIKE', "%{$search}%");
            });
        }
        
        // Paginasi hasil
        $leaderboard = $rankQuery->orderBy('alumnis.points', 'DESC')
            ->paginate($perPage)
            ->withQueryString(); // Untuk mempertahankan query string
        
        // Ambil top 3 untuk podium
        $topThree = Alumni::select([
                'alumnis.id',
                'alumnis.fullname',
                'alumnis.nim',
                'alumnis.study_program',
                'alumnis.points',
                'users.pp_url',
            ])
            ->join('users', 'alumnis.user_id', '=', 'users.id')
            ->where('alumnis.points', '>', 0)
            ->orderBy('alumnis.points', 'DESC')
            ->limit(3)
            ->get();
        
        // Hitung ranking user saat ini
        $currentUserRank = Alumni::where('points', '>', $alumni->points ?? 0)
            ->count() + 1;
        
        // Ambil total peserta
        $totalParticipants = Alumni::where('points', '>', 0)->count();
        
        // Ambil achievements user saat ini
        $achievements = AlumniAchievement::where('alumni_id', $alumni->id)
            ->orderBy('achieved_at', 'desc')
            ->take(5)
            ->get();
        
        // Data untuk view
        return view('leaderboard.index', [
            'leaderboard' => $leaderboard,
            'topThree' => $topThree,
            'currentUser' => $alumni,
            'currentUserRank' => $currentUserRank,
            'totalParticipants' => $totalParticipants,
            'achievements' => $achievements,
            'search' => $search,
            'perPage' => $perPage,
        ]);
    }
    
    /**
     * Submit informasi forum
     */
    public function submitForum(Request $request)
    {
        // Cek token CSRF untuk mencegah double submit
        if (!$request->hasValidSignature() && !$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request signature'
            ], 403);
        }
        
        $request->validate([
            'category' => 'required|in:seminar,event,tips,bootcamp,other',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'date_time' => 'nullable|date',
            'location' => 'nullable|string|max:200',
            'link' => 'nullable|url|max:500',
            'contact' => 'nullable|string|max:200',
        ]);
        
        try {
            $user = Auth::user();
            $alumni = $user->alumni;
            
            // Cek duplicate submission dalam 5 menit terakhir
            $recentSubmission = DB::table('forum_submissions')
                ->where('alumni_id', $alumni->id)
                ->where('title', $request->title)
                ->where('created_at', '>', now()->subMinutes(5))
                ->first();
                
            if ($recentSubmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengirim informasi ini baru saja. Tunggu beberapa saat sebelum mengirim lagi.'
                ], 429);
            }
            
            // Simpan data ke tabel forum_submissions
            DB::table('forum_submissions')->insert([
                'alumni_id' => $alumni->id,
                'category' => $request->category,
                'title' => $request->title,
                'description' => $request->description,
                'date_time' => $request->date_time,
                'location' => $request->location,
                'link' => $request->link,
                'contact' => $request->contact,
                'status' => 'pending',
                'points_awarded' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Informasi forum berhasil dikirim! Tim admin akan memverifikasi dalam 1-2 hari kerja.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim informasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Submit informasi lowongan kerja
     */
    public function submitJob(Request $request)
    {
        // Cek token CSRF untuk mencegah double submit
        if (!$request->hasValidSignature() && !$request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request signature'
            ], 403);
        }
        
        $request->validate([
            'company_name' => 'required|string|max:200',
            'position' => 'required|string|max:200',
            'location' => 'required|string|max:200',
            'job_description' => 'required|string',
            'qualifications' => 'required|string',
            'field' => 'required|in:it,marketing,finance,hrd,engineering,other',
            'deadline' => 'nullable|date',
            'link' => 'required|url|max:500',
            'contact' => 'nullable|string|max:200',
        ]);
        
        try {
            $user = Auth::user();
            $alumni = $user->alumni;
            
            // Cek duplicate submission dalam 5 menit terakhir
            $recentSubmission = DB::table('job_submissions')
                ->where('alumni_id', $alumni->id)
                ->where('company_name', $request->company_name)
                ->where('position', $request->position)
                ->where('created_at', '>', now()->subMinutes(5))
                ->first();
                
            if ($recentSubmission) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah mengirim lowongan ini baru saja. Tunggu beberapa saat sebelum mengirim lagi.'
                ], 429);
            }
            
            // Simpan data ke tabel job_submissions
            DB::table('job_submissions')->insert([
                'alumni_id' => $alumni->id,
                'company_name' => $request->company_name,
                'position' => $request->position,
                'location' => $request->location,
                'job_description' => $request->job_description,
                'qualifications' => $request->qualifications,
                'field' => $request->field,
                'deadline' => $request->deadline,
                'link' => $request->link,
                'contact' => $request->contact,
                'status' => 'pending',
                'points_awarded' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Informasi lowongan kerja berhasil dikirim! Tim admin akan memverifikasi dalam 1-2 hari kerja.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim informasi: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get user's submission history
     */
    public function getSubmissionHistory(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        $forumSubmissions = DB::table('forum_submissions')
            ->where('alumni_id', $alumni->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $jobSubmissions = DB::table('job_submissions')
            ->where('alumni_id', $alumni->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'forum_submissions' => $forumSubmissions,
                'job_submissions' => $jobSubmissions,
            ]
        ]);
    }
    
    /**
     * Get detailed user info for ranking
     */
    public function getUserRankInfo($id)
    {
        $user = Alumni::select([
                'alumnis.*',
                'users.email',
                'users.pp_url',
            ])
            ->join('users', 'alumnis.user_id', '=', 'users.id')
            ->where('alumnis.id', $id)
            ->first();
            
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }
        
        // Hitung ranking user
        $ranking = Alumni::where('points', '>', $user->points)->count() + 1;
        $user->ranking = $ranking;
        
        // Ambil achievements user
        $achievements = AlumniAchievement::where('alumni_id', $id)
            ->orderBy('achieved_at', 'desc')
            ->get();
            
        // Hitung total submissions yang sudah diverifikasi
        $verifiedSubmissions = DB::table('forum_submissions')
            ->where('alumni_id', $id)
            ->where('status', 'approved')
            ->count();
            
        $verifiedJobs = DB::table('job_submissions')
            ->where('alumni_id', $id)
            ->where('status', 'approved')
            ->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'user' => $user,
                'achievements' => $achievements,
                'submissions' => [
                    'forum' => $verifiedSubmissions,
                    'jobs' => $verifiedJobs,
                ]
            ]
        ]);
    }
}