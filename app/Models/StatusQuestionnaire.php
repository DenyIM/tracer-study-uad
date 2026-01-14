<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatusQuestionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'category_id',
        'current_questionnaire_id',
        'status',
        'progress_percentage',
        'total_points',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'progress_percentage' => 'integer',
        'total_points' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    protected $dates = [
        'started_at',
        'completed_at',
    ];

    /**
     * Status constants
     */
    const STATUS_NOT_STARTED = 'not_started';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    /**
     * Get the alumni that owns the status
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the category that owns the status
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the current questionnaire
     */
    public function currentQuestionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class, 'current_questionnaire_id');
    }

    /**
     * Get all progress records for this status
     */
    public function progressRecords()
    {
        return $this->hasMany(QuestionnaireProgress::class, 'alumni_id', 'alumni_id')
            ->whereHas('questionnaire', function ($query) {
                $query->where('category_id', $this->category_id);
            });
    }

    /**
     * Check if status is completed
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if status is in progress
     */
    public function getIsInProgressAttribute(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if status is not started
     */
    public function getIsNotStartedAttribute(): bool
    {
        return $this->status === self::STATUS_NOT_STARTED;
    }

    /**
     * Mark as completed
     */
    public function markAsCompleted(): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'progress_percentage' => 100,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark as in progress
     */
    public function markAsInProgress(): void
    {
        if ($this->is_not_started) {
            $this->update([
                'status' => self::STATUS_IN_PROGRESS,
                'started_at' => now(),
            ]);
        }
    }

    /**
     * Update progress based on answered questions
     */
    public function updateProgress(): void
    {
        $totalQuestions = $this->category->total_questions;
        $answeredQuestions = AnswerQuestion::where('alumni_id', $this->alumni_id)
            ->whereHas('question.questionnaire', function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->where('is_skipped', false)
            ->count();

        // Hitung total points untuk kategori ini
        $totalPoints = AnswerQuestion::where('alumni_id', $this->alumni_id)
            ->whereHas('question.questionnaire', function ($query) {
                $query->where('category_id', $this->category_id);
            })
            ->where('is_skipped', false)
            ->sum('points');

        $progress = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
        
        $this->update([
            'progress_percentage' => min($progress, 100),
            'total_points' => $totalPoints, // Ini akan trigger observer
        ]);

        // Update status based on progress
        if ($progress >= 100) {
            $this->markAsCompleted();
        } elseif ($progress > 0 && $this->is_not_started) {
            $this->markAsInProgress();
        }
    }
}