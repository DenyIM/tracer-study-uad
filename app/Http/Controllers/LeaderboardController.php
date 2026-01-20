<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumni;
use App\Models\User;
use App\Helpers\RankingHelper;
use Illuminate\Support\Facades\Auth;

class LeaderboardController extends Controller
{
    /**
     * Display leaderboard page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $alumni = $user->alumni;
        
        // Get paginated alumni with points
        $perPage = 20;
        $page = $request->get('page', 1);
        
        // Get top alumni ordered by points
        $topAlumni = Alumni::where('points', '>', 0)
            ->orderByDesc('points')
            ->paginate($perPage, ['*'], 'page', $page);
        
        // Get podium (top 3)
        $podiumAlumni = Alumni::where('points', '>', 0)
            ->orderByDesc('points')
            ->take(3)
            ->get();
        
        // Get current user rank
        $currentUserRank = $alumni ? RankingHelper::getAlumniRank($alumni->id) : null;
        
        // Prepare podium data
        $podiumData = [];
        if ($podiumAlumni->count() >= 3) {
            $podiumData = [
                'first' => $podiumAlumni[0] ?? null,
                'second' => $podiumAlumni[1] ?? null,
                'third' => $podiumAlumni[2] ?? null,
            ];
        }
        
        // Get total participants
        $totalParticipants = RankingHelper::getTotalParticipants();
        
        return view('leaderboard.index', [
            'topAlumni' => $topAlumni,
            'podiumData' => $podiumData,
            'currentUser' => $alumni,
            'currentUserRank' => $currentUserRank,
            'totalParticipants' => $totalParticipants,
            'userPoints' => $alumni ? $alumni->points : 0,
        ]);
    }
    
    /**
     * Submit forum information
     */
    public function submitForum(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date_time' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'link' => 'nullable|url|max:500',
            'contact' => 'nullable|string|max:255',
        ]);
        
        // Here you would save to database
        // For now, we'll simulate success
        
        // Calculate points (2,000 points for forum submission)
        $points = 2000;
        
        // Add points to alumni (you'll need to implement this)
        // $user = Auth::user();
        // $alumni = $user->alumni;
        // $alumni->increment('points', $points);
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi forum berhasil dikirim! Admin akan memverifikasi dalam 1-2 hari kerja.',
            'points' => $points,
        ]);
    }
    
    /**
     * Submit job information
     */
    public function submitJob(Request $request)
    {
        $request->validate([
            'company' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'field' => 'required|string|max:255',
            'deadline' => 'nullable|date',
            'link' => 'required|url|max:500',
            'contact' => 'nullable|string|max:255',
        ]);
        
        // Here you would save to database
        // For now, we'll simulate success
        
        // Calculate points (3,000 points for job submission)
        $points = 3000;
        
        // Add points to alumni (you'll need to implement this)
        // $user = Auth::user();
        // $alumni = $user->alumni;
        // $alumni->increment('points', $points);
        
        return response()->json([
            'success' => true,
            'message' => 'Informasi lowongan kerja berhasil dikirim! Admin akan memverifikasi dalam 1-2 hari kerja.',
            'points' => $points,
        ]);
    }
    
    /**
     * Get leaderboard data for API
     */
    public function getLeaderboardData(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = 20;
        
        $alumni = Alumni::where('points', '>', 0)
            ->orderByDesc('points')
            ->paginate($perPage, ['*'], 'page', $page);
        
        $data = $alumni->map(function ($alumni, $index) use ($page, $perPage) {
            // Calculate actual rank
            $rank = (($page - 1) * $perPage) + $index + 1;
            
            return [
                'rank' => $rank,
                'name' => $alumni->fullname,
                'initials' => $this->getInitials($alumni->fullname),
                'study_program' => $alumni->study_program,
                'graduation_year' => $alumni->graduation_date ? $alumni->graduation_date->format('Y') : 'N/A',
                'points' => number_format($alumni->points),
                'raw_points' => $alumni->points,
                'alumni_id' => $alumni->id,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'current_page' => $alumni->currentPage(),
            'total_pages' => $alumni->lastPage(),
            'total_items' => $alumni->total(),
        ]);
    }
    
    /**
     * Get initials from name
     */
    private function getInitials($name)
    {
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
}