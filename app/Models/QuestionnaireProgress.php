<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'questionnaire_id',
        'status',
        'answered_count',
        'total_questions',
        'progress_percentage',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'answered_count' => 'integer',
        'total_questions' => 'integer',
        'progress_percentage' => 'integer',
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
     * Get the alumni that owns the progress
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the questionnaire
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Check if progress is completed
     */
    public function getIsCompletedAttribute(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if progress is in progress
     */
    public function getIsInProgressAttribute(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Update progress based on answered questions
     */
    public function updateProgress(): void
    {
        $totalQuestions = $this->questionnaire->questions()->count();
        $answeredQuestions = AnswerQuestion::where('alumni_id', $this->alumni_id)
            ->whereIn('question_id', $this->questionnaire->questions()->pluck('id'))
            ->where('is_skipped', false)
            ->count();

        $progress = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
        
        $this->update([
            'answered_count' => $answeredQuestions,
            'total_questions' => $totalQuestions,
            'progress_percentage' => min($progress, 100),
        ]);

        // Update status based on progress
        if ($progress >= 100) {
            $this->update([
                'status' => self::STATUS_COMPLETED,
                'completed_at' => now(),
            ]);
        } elseif ($progress > 0 && $this->status === self::STATUS_NOT_STARTED) {
            $this->update([
                'status' => self::STATUS_IN_PROGRESS,
                'started_at' => now(),
            ]);
        }
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
}