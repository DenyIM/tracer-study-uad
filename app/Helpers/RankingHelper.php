<?php

namespace App\Helpers;

use App\Models\Alumni; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingHelper
{
    /**
     * Get alumni rank based on alumni.points
     */
    public static function getAlumniRank($alumniId)
    {
        try {
            $alumni = Alumni::find($alumniId);
            if (!$alumni) {
                return 1;
            }
            
            $currentPoints = $alumni->points ?? 0;
            
            $higherCount = Alumni::where('points', '>', $currentPoints)->count();
            
            $ranking = $higherCount + 1;
            
            $totalParticipants = Alumni::count();
            
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