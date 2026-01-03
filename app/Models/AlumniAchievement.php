<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniAchievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'achievement_type',
        'title',
        'description',
        'points_rewarded',
        'metadata',
        'achieved_at',
    ];

    protected $casts = [
        'points_rewarded' => 'integer',
        'metadata' => 'array',
        'achieved_at' => 'datetime',
    ];

    protected $dates = [
        'achieved_at',
    ];

    /**
     * Achievement types
     */
    const TYPE_QUESTIONNAIRE_COMPLETED = 'questionnaire_completed';
    const TYPE_POINTS_MILESTONE = 'points_milestone';
    const TYPE_CATEGORY_COMPLETED = 'category_completed';
    const TYPE_ALL_COMPLETED = 'all_completed';

    /**
     * Get the alumni that owns the achievement
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Create achievement for questionnaire completion
     */
    public static function createForQuestionnaireCompletion($alumniId, $questionnaire, $points): self
    {
        return self::create([
            'alumni_id' => $alumniId,
            'achievement_type' => self::TYPE_QUESTIONNAIRE_COMPLETED,
            'title' => "Menyelesaikan {$questionnaire->name}",
            'description' => "Telah menyelesaikan kuesioner {$questionnaire->name} pada kategori {$questionnaire->category->name}",
            'points_rewarded' => $points,
            'metadata' => [
                'questionnaire_id' => $questionnaire->id,
                'category_id' => $questionnaire->category_id,
            ],
            'achieved_at' => now(),
        ]);
    }

    /**
     * Create achievement for category completion
     */
    public static function createForCategoryCompletion($alumniId, $category, $totalPoints): self
    {
        return self::create([
            'alumni_id' => $alumniId,
            'achievement_type' => self::TYPE_CATEGORY_COMPLETED,
            'title' => "Kompletor Kategori {$category->name}",
            'description' => "Telah menyelesaikan semua kuesioner pada kategori {$category->name}",
            'points_rewarded' => $totalPoints,
            'metadata' => [
                'category_id' => $category->id,
            ],
            'achieved_at' => now(),
        ]);
    }
}