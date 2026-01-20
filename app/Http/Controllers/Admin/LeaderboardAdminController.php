<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use App\Models\ForumSubmission;
use App\Models\JobSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LeaderboardAdminController extends Controller
{
    /**
     * Tampilkan dashboard admin untuk leaderboard
     */
    public function dashboard(Request $request)
    {
        // Stats utama
        $totalAlumni = Alumni::count();
        $totalPoints = Alumni::sum('points');
        $topThreeAlumni = Alumni::with('user')
            ->orderBy('points', 'desc')
            ->limit(3)
            ->get();
        
        // Submission stats
        $pendingForumSubmissions = ForumSubmission::where('status', 'pending')->count();
        $pendingJobSubmissions = JobSubmission::where('status', 'pending')->count();
        $approvedForumSubmissions = ForumSubmission::where('status', 'approved')->count();
        $approvedJobSubmissions = JobSubmission::where('status', 'approved')->count();
        
        // Recent submissions
        $recentForumSubmissions = ForumSubmission::with('alumni.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentJobSubmissions = JobSubmission::with('alumni.user')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Recent points awards
        $recentPoints = Alumni::where('points', '>', 0)
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get(['fullname', 'points', 'updated_at', 'nim', 'study_program']);
        
        return view('admin.views.leaderboard.dashboard', compact(
            'totalAlumni',
            'totalPoints',
            'topThreeAlumni',
            'pendingForumSubmissions',
            'pendingJobSubmissions',
            'approvedForumSubmissions',
            'approvedJobSubmissions',
            'recentForumSubmissions',
            'recentJobSubmissions',
            'recentPoints'
        ));
    }
    
    /**
     * Tampilkan daftar semua alumni untuk leaderboard
     */
    public function alumniLeaderboard(Request $request)
    {
        $perPage = $request->get('per_page', 20);
        $search = $request->get('search', '');
        
        $query = Alumni::with('user')
            ->orderBy('points', 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('fullname', 'LIKE', "%{$search}%")
                  ->orWhere('nim', 'LIKE', "%{$search}%")
                  ->orWhere('study_program', 'LIKE', "%{$search}%");
            });
        }
        
        $alumni = $query->paginate($perPage);
        
        // Hitung ranking
        $rankedAlumni = [];
        $startRank = ($alumni->currentPage() - 1) * $alumni->perPage() + 1;
        
        foreach ($alumni as $index => $item) {
            $item->ranking = $startRank + $index;
            $rankedAlumni[] = $item;
        }
        
        return view('admin.views.leaderboard.alumni', compact(
            'alumni',
            'rankedAlumni',
            'search',
            'perPage'
        ));
    }
    
    /**
     * Tampilkan daftar submission forum pending
     */
    public function forumSubmissions(Request $request)
    {
        $status = $request->get('status', 'pending');
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search', '');
        
        $query = ForumSubmission::with(['alumni.user'])
            ->where('status', $status)
            ->orderBy('created_at', $status === 'pending' ? 'asc' : 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%")
                  ->orWhereHas('alumni', function($q2) use ($search) {
                      $q2->where('fullname', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $submissions = $query->paginate($perPage);
        
        $stats = [
            'pending' => ForumSubmission::where('status', 'pending')->count(),
            'approved' => ForumSubmission::where('status', 'approved')->count(),
            'rejected' => ForumSubmission::where('status', 'rejected')->count(),
        ];
        
        return view('admin.views.leaderboard.forum-submissions', compact(
            'submissions',
            'status',
            'stats',
            'search',
            'perPage'
        ));
    }
    
    /**
     * Tampilkan daftar submission lowongan kerja pending
     */
    public function jobSubmissions(Request $request)
    {
        $status = $request->get('status', 'pending');
        $perPage = $request->get('per_page', 15);
        $search = $request->get('search', '');
        
        $query = JobSubmission::with(['alumni.user'])
            ->where('status', $status)
            ->orderBy('created_at', $status === 'pending' ? 'asc' : 'desc');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('position', 'LIKE', "%{$search}%")
                  ->orWhere('field', 'LIKE', "%{$search}%")
                  ->orWhereHas('alumni', function($q2) use ($search) {
                      $q2->where('fullname', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        $submissions = $query->paginate($perPage);
        
        $stats = [
            'pending' => JobSubmission::where('status', 'pending')->count(),
            'approved' => JobSubmission::where('status', 'approved')->count(),
            'rejected' => JobSubmission::where('status', 'rejected')->count(),
        ];
        
        return view('admin.views.leaderboard.job-submissions', compact(
            'submissions',
            'status',
            'stats',
            'search',
            'perPage'
        ));
    }
    
    /**
     * Tampilkan detail submission forum
     */
    public function showForumSubmission($id)
    {
        $submission = ForumSubmission::with(['alumni.user', 'verifier'])
            ->findOrFail($id);
            
        return view('admin.views.leaderboard.forum-submission-detail', compact('submission'));
    }
    
    /**
     * Tampilkan detail submission job
     */
    public function showJobSubmission($id)
    {
        $submission = JobSubmission::with(['alumni.user', 'verifier'])
            ->findOrFail($id);
            
        return view('admin.views.leaderboard.job-submission-detail', compact('submission'));
    }
    
    /**
     * Approve forum submission
     */
    public function approveForumSubmission(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $submission = ForumSubmission::findOrFail($id);
            
            // Update submission
            $submission->update([
                'status' => 'approved',
                'points_awarded' => 2000, // 2000 points untuk forum
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'updated_at' => now(),
            ]);
            
            // Update alumni points
            $alumni = $submission->alumni;
            $alumni->increment('points', 2000);
            $alumni->updated_at = now();
            $alumni->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Submission forum berhasil disetujui. Alumni mendapatkan 2000 points.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Approve job submission
     */
    public function approveJobSubmission(Request $request, $id)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $submission = JobSubmission::findOrFail($id);
            
            // Update submission
            $submission->update([
                'status' => 'approved',
                'points_awarded' => 3000, // 3000 points untuk job
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'updated_at' => now(),
            ]);
            
            // Update alumni points
            $alumni = $submission->alumni;
            $alumni->increment('points', 3000);
            $alumni->updated_at = now();
            $alumni->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Submission lowongan kerja berhasil disetujui. Alumni mendapatkan 3000 points.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reject submission (forum/job)
     */
    public function rejectSubmission(Request $request, $type, $id)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ]);
        
        try {
            if ($type === 'forum') {
                $submission = ForumSubmission::findOrFail($id);
            } else {
                $submission = JobSubmission::findOrFail($id);
            }
            
            $submission->update([
                'status' => 'rejected',
                'admin_notes' => $request->admin_notes,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Submission berhasil ditolak.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete forum submission (soft delete)
     */
    public function deleteForumSubmission($id)
    {
        try {
            $submission = ForumSubmission::findOrFail($id);
            $submission->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Forum submission berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Delete job submission (soft delete)
     */
    public function deleteJobSubmission($id)
    {
        try {
            $submission = JobSubmission::findOrFail($id);
            $submission->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Job submission berhasil dihapus.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk approve submissions
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'type' => 'required|in:forum,job',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);
        
        try {
            DB::beginTransaction();
            
            $approvedCount = 0;
            $pointsAwarded = 0;
            
            if ($request->type === 'forum') {
                $submissions = ForumSubmission::whereIn('id', $request->ids)
                    ->where('status', 'pending')
                    ->get();
                
                foreach ($submissions as $submission) {
                    $submission->update([
                        'status' => 'approved',
                        'points_awarded' => 2000,
                        'verified_at' => now(),
                        'verified_by' => Auth::id(),
                        'updated_at' => now(),
                    ]);
                    
                    $alumni = $submission->alumni;
                    $alumni->increment('points', 2000);
                    $alumni->updated_at = now();
                    $alumni->save();
                    
                    $approvedCount++;
                    $pointsAwarded += 2000;
                }
            } else {
                $submissions = JobSubmission::whereIn('id', $request->ids)
                    ->where('status', 'pending')
                    ->get();
                
                foreach ($submissions as $submission) {
                    $submission->update([
                        'status' => 'approved',
                        'points_awarded' => 3000,
                        'verified_at' => now(),
                        'verified_by' => Auth::id(),
                        'updated_at' => now(),
                    ]);
                    
                    $alumni = $submission->alumni;
                    $alumni->increment('points', 3000);
                    $alumni->updated_at = now();
                    $alumni->save();
                    
                    $approvedCount++;
                    $pointsAwarded += 3000;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menyetujui {$approvedCount} submission. Total points diberikan: {$pointsAwarded}"
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyetujui bulk submission: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Edit alumni points manually
     */
    public function editAlumniPoints(Request $request, $id)
    {
        $request->validate([
            'points' => 'required|integer|min:0',
            'notes' => 'required|string|max:255',
        ]);
        
        try {
            $alumni = Alumni::findOrFail($id);
            $oldPoints = $alumni->points;
            $alumni->update(['points' => $request->points]);
            
            // Log perubahan points
            DB::table('point_adjustments')->insert([
                'alumni_id' => $id,
                'old_points' => $oldPoints,
                'new_points' => $request->points,
                'difference' => $request->points - $oldPoints,
                'admin_id' => Auth::id(),
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Points alumni berhasil diupdate.'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate points: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get submission statistics for charts
     */
    public function getStatistics()
    {
        // Submission stats per month
        $forumStats = ForumSubmission::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $jobStats = JobSubmission::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                DB::raw('SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Category distribution
        $forumCategories = ForumSubmission::select('category', DB::raw('COUNT(*) as count'))
            ->where('status', 'approved')
            ->groupBy('category')
            ->get();
            
        $jobFields = JobSubmission::select('field', DB::raw('COUNT(*) as count'))
            ->where('status', 'approved')
            ->groupBy('field')
            ->get();
        
        // Top alumni contributors
        $topForumContributors = ForumSubmission::select('alumni_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'approved')
            ->groupBy('alumni_id')
            ->with('alumni')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
            
        $topJobContributors = JobSubmission::select('alumni_id', DB::raw('COUNT(*) as count'))
            ->where('status', 'approved')
            ->groupBy('alumni_id')
            ->with('alumni')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'forum_stats' => $forumStats,
                'job_stats' => $jobStats,
                'forum_categories' => $forumCategories,
                'job_fields' => $jobFields,
                'top_forum_contributors' => $topForumContributors,
                'top_job_contributors' => $topJobContributors,
            ]
        ]);
    }
    
    /**
     * Get pending counts for sidebar
     */
    public function getPendingCounts()
    {
        try {
            $pendingForum = ForumSubmission::where('status', 'pending')->count();
            $pendingJob = JobSubmission::where('status', 'pending')->count();
            
            return response()->json([
                'success' => true,
                'pending_forum' => $pendingForum,
                'pending_job' => $pendingJob,
                'total_pending' => $pendingForum + $pendingJob
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending counts'
            ], 500);
        }
    }
}