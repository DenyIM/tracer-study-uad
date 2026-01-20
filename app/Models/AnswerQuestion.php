<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AnswerQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'alumni_id',
        'question_id',
        'answer',
        'selected_options',
        'scale_value',
        'points',
        'is_skipped',
        'answered_at',
    ];

    protected $casts = [
        'selected_options' => 'array',
        'is_skipped' => 'boolean',
        'answered_at' => 'datetime',
        'scale_value' => 'integer',
        'points' => 'integer',
    ];

    protected $dates = [
        'answered_at',
    ];

    /**
     * Get the alumni that owns the answer
     */
    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    /**
     * Get the question that owns the answer
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the questionnaire through question
     */
    public function questionnaire()
    {
        return $this->question->questionnaire;
    }

    /**
     * Get the category through questionnaire
     */
    public function category()
    {
        return $this->questionnaire->category;
    }

    /**
     * Get formatted answer for display
     */
    public function getFormattedAnswerAttribute(): string
    {
        if ($this->scale_value !== null) {
            return "Skala: {$this->scale_value}";
        }

        if (!empty($this->selected_options)) {
            if (is_array($this->selected_options)) {
                return implode(', ', $this->selected_options);
            }
            return $this->selected_options;
        }

        return $this->answer ?? '-';
    }

    /**
     * Check if answer is complete (not skipped and has value)
     */
    public function getIsCompleteAttribute(): bool
    {
        return !$this->is_skipped && (
            !empty($this->answer) || 
            !empty($this->selected_options) || 
            $this->scale_value !== null
        );
    }

    /**
     * Calculate points earned from this answer
     */
    public function getPointsEarnedAttribute(): int
    {
        if ($this->is_skipped || !$this->is_complete) {
            return 0;
        }
        
        return $this->points ?? ($this->question->points ?? 0);
    }

    /**
     * Get detailed answers for per-row questions
     */
    public function detailedAnswers(): HasMany
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    /**
     * Get answer for specific row item
     */
    public function getAnswerForRowItem($itemKey)
    {
        return $this->detailedAnswers()->where('item_key', $itemKey)->first();
    }

    /**
     * Check if question has per-row answers
     */
    public function getHasPerRowAnswersAttribute(): bool
    {
        return $this->question->is_per_row && $this->detailedAnswers()->exists();
    }

    /**
     * Get formatted answer for per-row questions
     */
    public function getPerRowFormattedAnswersAttribute(): array
    {
        if (!$this->has_per_row_answers) {
            return [];
        }
        
        $formatted = [];
        foreach ($this->detailedAnswers as $detail) {
            $formatted[$detail->item_key] = [
                'label' => $detail->item_label,
                'answer' => $detail->text_answer ?? $detail->scale_value ?? $detail->selected_options,
            ];
        }
        
        return $formatted;
    }
}