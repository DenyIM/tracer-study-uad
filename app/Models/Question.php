<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionnaire_id',
        'question_text',
        'question_type',
        'description',
        'options',
        'scale_options',
        'row_items',
        'scale_label_low',
        'scale_label_high',
        'min_value',
        'max_value',
        'input_type',
        'max_length',
        'rows',
        'is_required',
        'order',
        'points',
        'validation_rules',
        'placeholder',
        'helper_text',
        'has_other_option',
        'has_none_option',
        'is_locked_by_default',
        'allow_multiple_selection',
        'max_selections',
    ];

    protected $casts = [
        'options' => 'array',
        'scale_options' => 'array',
        'row_items' => 'array',
        'validation_rules' => 'array',
        'is_required' => 'boolean',
        'has_other_option' => 'boolean',
        'has_none_option' => 'boolean',
        'is_locked_by_default' => 'boolean',
        'allow_multiple_selection' => 'boolean',
        'points' => 'integer',
        'min_value' => 'integer',
        'max_value' => 'integer',
        'max_length' => 'integer',
        'rows' => 'integer',
        'max_selections' => 'integer',
    ];

    /**
     * Get the questionnaire that owns the question
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get all answers for this question
     */
    public function answers(): HasMany
    {
        return $this->hasMany(AnswerQuestion::class);
    }

    /**
     * Get answer for a specific alumni
     */
    public function answerForAlumni($alumniId)
    {
        return $this->answers()->where('alumni_id', $alumniId)->first();
    }

    /**
     * Check if question is answered by alumni
     */
    public function isAnsweredBy($alumniId): bool
    {
        return $this->answers()->where('alumni_id', $alumniId)->exists();
    }

    /**
     * Get available options including "other" and "none" if applicable
     */
    public function getAvailableOptionsAttribute(): array
    {
        $options = $this->options ?? [];
        
        if ($this->has_other_option) {
            $options[] = 'Lainnya, sebutkan!';
        }
        
        if ($this->has_none_option) {
            $options[] = 'Tidak Ada';
        }
        
        return $options;
    }

    /**
     * Check if question type supports multiple selection
     */
    public function getSupportsMultipleAttribute(): bool
    {
        return in_array($this->question_type, ['checkbox', 'competency_scale']);
    }

    /**
     * Check if question type is scale-based
     */
    public function getIsScaleAttribute(): bool
    {
        return in_array($this->question_type, ['likert_scale', 'competency_scale']);
    }


    /**
     * Get available scale options with labels
     */
    public function getScaleOptionsWithLabelsAttribute(): array
    {
        $options = $this->scale_options ?? [1, 2, 3, 4, 5];
        $labels = [];
        
        foreach ($options as $value) {
            if ($value == 1) {
                $labels[$value] = $this->scale_label_low ?? 'Sangat Rendah';
            } elseif ($value == 5) {
                $labels[$value] = $this->scale_label_high ?? 'Sangat Tinggi';
            } else {
                $labels[$value] = $value;
            }
        }
        
        return $labels;
    }

    /**
     * Check if question type is per-row (likert per baris)
     */
    public function getIsPerRowAttribute(): bool
    {
        return in_array($this->question_type, ['radio_per_row', 'checkbox_per_row', 'likert_per_row']);
    }

    /**
     * Get row items for per-row questions
     */
    public function getFormattedRowItemsAttribute(): array
    {
        if (!$this->row_items || !is_array($this->row_items)) {
            return [];
        }
        
        return $this->row_items;
    }

    /**
     * Check if question is numeric input
     */
    public function getIsNumericAttribute(): bool
    {
        return $this->question_type === 'number';
    }

    /**
     * Check if question is date input
     */
    public function getIsDateAttribute(): bool
    {
        return $this->question_type === 'date';
    }

    /**
     * Check if question is textarea
     */
    public function getIsTextareaAttribute(): bool
    {
        return $this->question_type === 'textarea';
    }

    /**
     * Get validation rules as array
     */
    public function getValidationRulesArrayAttribute(): array
    {
        $rules = [];
        
        if ($this->is_required) {
            $rules[] = 'required';
        }
        
        if ($this->validation_rules && is_array($this->validation_rules)) {
            $rules = array_merge($rules, $this->validation_rules);
        }
        
        if ($this->is_numeric) {
            $rules[] = 'numeric';
            if ($this->min_value !== null) {
                $rules[] = 'min:' . $this->min_value;
            }
            if ($this->max_value !== null) {
                $rules[] = 'max:' . $this->max_value;
            }
        }
        
        if ($this->max_length) {
            $rules[] = 'max:' . $this->max_length;
        }
        
        if ($this->input_type === 'email') {
            $rules[] = 'email';
        }
        
        return $rules;
    }
}