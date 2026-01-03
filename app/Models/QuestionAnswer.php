<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'answer_question_id',
        'item_key',
        'item_label',
        'text_answer',
        'scale_value',
        'selected_options',
        'answered_at',
    ];

    protected $casts = [
        'selected_options' => 'array',
        'scale_value' => 'integer',
        'answered_at' => 'datetime',
    ];

    protected $dates = [
        'answered_at',
    ];

    /**
     * Get the parent answer
     */
    public function answerQuestion(): BelongsTo
    {
        return $this->belongsTo(AnswerQuestion::class);
    }

    /**
     * Get formatted answer
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

        return $this->text_answer ?? '-';
    }
}