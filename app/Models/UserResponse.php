<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResponse extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'questionnaire_id',
        'category_id',
        'status',
        'submitted_at',
        'total_questions',
        'answered_questions',
        'completion_percentage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'submitted_at' => 'datetime',
        'completion_percentage' => 'decimal:2',
        'total_questions' => 'integer',
        'answered_questions' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'submitted_at',
    ];

    /**
     * Get the user that owns the response.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the questionnaire that owns the response.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the category that owns the response.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the response details for the response.
     */
    public function responseDetails()
    {
        return $this->hasMany(ResponseDetail::class);
    }

    /**
     * Scope a query to only include draft responses.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope a query to only include submitted responses.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope a query to only include pending review responses.
     */
    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    /**
     * Scope a query to only include approved responses.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by questionnaire.
     */
    public function scopeByQuestionnaire($query, $questionnaireId)
    {
        return $query->where('questionnaire_id', $questionnaireId);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to order by submission date.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('submitted_at', 'desc')
            ->orderBy('updated_at', 'desc');
    }

    /**
     * Check if response is draft.
     */
    public function isDraft()
    {
        return $this->status === 'draft';
    }

    /**
     * Check if response is submitted.
     */
    public function isSubmitted()
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if response is pending review.
     */
    public function isPendingReview()
    {
        return $this->status === 'pending_review';
    }

    /**
     * Check if response is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Mark response as submitted.
     */
    public function markAsSubmitted()
    {
        $this->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }

    /**
     * Mark response as approved.
     */
    public function markAsApproved()
    {
        $this->update([
            'status' => 'approved',
        ]);
    }

    /**
     * Mark response as pending review.
     */
    public function markAsPendingReview()
    {
        $this->update([
            'status' => 'pending_review',
        ]);
    }

    /**
     * Calculate completion percentage.
     */
    public function calculateCompletionPercentage()
    {
        if ($this->total_questions === 0) {
            return 0;
        }

        $percentage = ($this->answered_questions / $this->total_questions) * 100;

        $this->update([
            'completion_percentage' => round($percentage, 2),
        ]);

        return $percentage;
    }

    /**
     * Update answered questions count.
     */
    public function updateAnsweredCount()
    {
        $answeredCount = $this->responseDetails()->count();

        $this->update([
            'answered_questions' => $answeredCount,
        ]);

        $this->calculateCompletionPercentage();

        return $answeredCount;
    }

    /**
     * Set total questions count.
     */
    public function setTotalQuestions($count)
    {
        $this->update([
            'total_questions' => $count,
        ]);

        $this->calculateCompletionPercentage();

        return $this;
    }

    /**
     * Get completion status text.
     */
    public function getCompletionStatusAttribute()
    {
        if ($this->completion_percentage >= 100) {
            return 'Selesai';
        } elseif ($this->completion_percentage >= 50) {
            return 'Setengah';
        } elseif ($this->completion_percentage > 0) {
            return 'Sedang Dikerjakan';
        } else {
            return 'Belum Dimulai';
        }
    }

    /**
     * Get status badge color.
     */
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft' => 'secondary',
            'submitted' => 'primary',
            'pending_review' => 'warning',
            'approved' => 'success',
            default => 'secondary',
        };
    }

    /**
     * Get status text.
     */
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Terkirim',
            'pending_review' => 'Menunggu Review',
            'approved' => 'Disetujui',
            default => 'Draft',
        };
    }

    /**
     * Get submission date formatted.
     */
    public function getSubmittedAtFormattedAttribute()
    {
        return $this->submitted_at
            ? $this->submitted_at->format('d/m/Y H:i')
            : '-';
    }

    /**
     * Check if response can be submitted.
     */
    public function canBeSubmitted()
    {
        return $this->isDraft() && $this->completion_percentage >= 100;
    }

    /**
     * Get remaining questions count.
     */
    public function getRemainingQuestionsAttribute()
    {
        return $this->total_questions - $this->answered_questions;
    }

    /**
     * Check if all questions are answered.
     */
    public function isComplete()
    {
        return $this->answered_questions >= $this->total_questions;
    }
}
