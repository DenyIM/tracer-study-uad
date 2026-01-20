<?php

namespace App\Helpers;

use App\Models\Alumni; // TAMBAHKAN INI
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingHelper
{
    /**
     * Get alumni rank based on alumni.points (MySQL compatible)
     */
    public static function getAlumniRank($alumniId)
    {
        try {
            // Get current alumni total points from alumni table
            $alumni = Alumni::find($alumniId);
            if (!$alumni) {
                return 1;
            }
            
            $currentPoints = $alumni->points ?? 0;
            
            // Count alumni with higher points from alumni table
            $higherCount = Alumni::where('points', '>', $currentPoints)->count();
            
            // Rank = higher count + 1
            $ranking = $higherCount + 1;
            
            // Total participants
            $totalParticipants = Alumni::count();
            
            // If points 0, last rank
            if ($currentPoints <= 0 && $totalParticipants > 0) {
                $ranking = $totalParticipants;
            }
            
            return $ranking;
            
        } catch (\Exception $e) {
            Log::error('Error calculating rank: ' . $e->getMessage());
            return 1;
        }
    }
    
    public static function getTotalParticipants()
    {
        return Alumni::count();
    }
}