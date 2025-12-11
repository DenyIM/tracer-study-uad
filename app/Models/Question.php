<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'questionnaire_id',
        'code',
        'question_text',
        'description',
        'type',
        'order',
        'is_required',
        'has_other_option',
        'validation_rules',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'has_other_option' => 'boolean',
        'order' => 'integer',
        'validation_rules' => 'array',
    ];

    /**
     * Get the questionnaire that owns the question.
     */
    public function questionnaire()
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the options for the question.
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    /**
     * Get the response details for the question.
     */
    public function responseDetails()
    {
        return $this->hasMany(ResponseDetail::class);
    }

    /**
     * Scope a query to only include required questions.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
    }

    /**
     * Scope a query to only include optional questions.
     */
    public function scopeOptional($query)
    {
        return $query->where('is_required', false);
    }

    /**
     * Scope a query to filter by questionnaire.
     */
    public function scopeByQuestionnaire($query, $questionnaireId)
    {
        return $query->where('questionnaire_id', $questionnaireId);
    }

    /**
     * Scope a query to order by order field.
     */
    public function scopeOrdered($query, $direction = 'asc')
    {
        return $query->orderBy('order', $direction)
            ->orderBy('id', $direction);
    }

    /**
     * Scope a query to filter by question type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to include questions with other option.
     */
    public function scopeWithOtherOption($query)
    {
        return $query->where('has_other_option', true);
    }

    /**
     * Get the next question in order.
     */
    public function getNextQuestionAttribute()
    {
        return self::where('questionnaire_id', $this->questionnaire_id)
            ->where('order', '>', $this->order)
            ->ordered()
            ->first();
    }

    /**
     * Get the previous question in order.
     */
    public function getPreviousQuestionAttribute()
    {
        return self::where('questionnaire_id', $this->questionnaire_id)
            ->where('order', '<', $this->order)
            ->ordered('desc')
            ->first();
    }

    /**
     * Check if this is the first question.
     */
    public function getIsFirstAttribute()
    {
        return !self::where('questionnaire_id', $this->questionnaire_id)
            ->where('order', '<', $this->order)
            ->exists();
    }

    /**
     * Check if this is the last question.
     */
    public function getIsLastAttribute()
    {
        return !self::where('questionnaire_id', $this->questionnaire_id)
            ->where('order', '>', $this->order)
            ->exists();
    }

    /**
     * Check if question has options.
     */
    public function hasOptions()
    {
        return $this->options()->exists();
    }

    /**
     * Get options count.
     */
    public function getOptionsCountAttribute()
    {
        return $this->options()->count();
    }

    /**
     * Check if question type is choice-based.
     */
    public function isChoiceType()
    {
        return in_array($this->type, ['dropdown', 'radio', 'checkbox']);
    }

    /**
     * Check if question type is text-based.
     */
    public function isTextType()
    {
        return in_array($this->type, ['text', 'textarea']);
    }

    /**
     * Check if question type is scale-based.
     */
    public function isScaleType()
    {
        return in_array($this->type, ['scale', 'competency_scale']);
    }

    /**
     * Check if question type is matrix.
     */
    public function isMatrixType()
    {
        return $this->type === 'matrix';
    }

    /**
     * Check if question type is numeric.
     */
    public function isNumericType()
    {
        return $this->type === 'number';
    }

    /**
     * Check if question type is date.
     */
    public function isDateType()
    {
        return $this->type === 'date';
    }

    /**
     * Get question type label.
     */
    public function getTypeLabelAttribute()
    {
        $labels = [
            'dropdown' => 'Dropdown',
            'radio' => 'Pilihan Tunggal',
            'checkbox' => 'Pilihan Ganda',
            'text' => 'Teks Pendek',
            'textarea' => 'Teks Panjang',
            'number' => 'Angka',
            'date' => 'Tanggal',
            'scale' => 'Skala Likert',
            'competency_scale' => 'Skala Kompetensi',
            'matrix' => 'Matriks/Tabel',
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Get validation rules array.
     */
    public function getValidationRulesArrayAttribute()
    {
        $rules = [];

        if ($this->is_required) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        // Add type-specific rules
        switch ($this->type) {
            case 'number':
                $rules[] = 'numeric';
                break;
            case 'date':
                $rules[] = 'date';
                break;
            case 'email':
                $rules[] = 'email';
                break;
        }

        // Add custom validation rules
        if ($this->validation_rules && is_array($this->validation_rules)) {
            $rules = array_merge($rules, $this->validation_rules);
        }

        return $rules;
    }

    /**
     * Get the HTML input type.
     */
    public function getHtmlInputTypeAttribute()
    {
        return match ($this->type) {
            'text', 'textarea' => 'text',
            'number' => 'number',
            'date' => 'date',
            'email' => 'email',
            'tel' => 'tel',
            'url' => 'url',
            default => 'text',
        };
    }

    /**
     * Get options as array for form select.
     */
    public function getOptionsArrayAttribute()
    {
        return $this->options->mapWithKeys(function ($option) {
            return [$option->value ?? $option->id => $option->option_text];
        })->toArray();
    }

    /**
     * Get options with labels for scale questions.
     */
    public function getScaleOptionsAttribute()
    {
        if (!$this->isScaleType()) {
            return collect();
        }

        return $this->options->map(function ($option) {
            return [
                'value' => $option->value,
                'label' => $option->label,
                'text' => $option->option_text,
            ];
        })->sortBy('value');
    }

    /**
     * Get competency items for matrix questions.
     */
    public function getCompetencyItemsAttribute()
    {
        if (!$this->isMatrixType() && $this->type !== 'competency_scale') {
            return [];
        }

        // Extract competency items from options or return default
        if ($this->options->count() > 0) {
            return $this->options->pluck('option_text')->toArray();
        }

        // Default competency items
        return [
            'Etika',
            'Keahlian Bidang Ilmu',
            'Bahasa Inggris',
            'Penggunaan IT',
            'Komunikasi',
            'Kerja Sama Tim',
            'Pengembangan Diri',
        ];
    }

    /**
     * Get scale labels for matrix questions.
     */
    public function getScaleLabelsAttribute()
    {
        $labels = [
            1 => 'Sangat Rendah',
            2 => 'Rendah',
            3 => 'Cukup',
            4 => 'Tinggi',
            5 => 'Sangat Tinggi',
        ];

        // Check if custom labels exist in options
        if ($this->options->where('label', '!=', null)->count() > 0) {
            $customLabels = [];
            foreach ($this->options as $option) {
                if ($option->label) {
                    $customLabels[$option->value] = $option->label;
                }
            }
            if (!empty($customLabels)) {
                ksort($customLabels);
                return $customLabels;
            }
        }

        return $labels;
    }

    /**
     * Get learning methods for F21-F27 type questions.
     */
    public function getLearningMethodsAttribute()
    {
        if ($this->code === 'F21-F27') {
            return [
                'Perkuliahan',
                'Demonstrasi',
                'Partisipasi Proyek Riset',
                'Magang',
                'Praktikum',
                'Kerja Lapangan',
                'Diskusi',
            ];
        }

        return [];
    }

    /**
     * Get learning method scale options.
     */
    public function getLearningMethodScaleOptionsAttribute()
    {
        return [
            'Sangat Besar',
            'Besar',
            'Cukup',
            'Kurang',
            'Tidak Sama Sekali',
        ];
    }

    /**
     * Check if question is routing question (F8).
     */
    public function getIsRoutingQuestionAttribute()
    {
        return $this->code === 'F8';
    }

    /**
     * Get routing options for F8 question.
     */
    public function getRoutingOptionsAttribute()
    {
        if ($this->code === 'F8') {
            return [
                'Bekerja (full time/part time) di perusahaan/instansi',
                'Wiraswasta/Pemilik Usaha',
                'Melanjutkan Pendidikan',
                'Tidak Kerja, tetapi sedang mencari kerja',
                'Belum memungkinkan bekerja / Tidak mencari kerja',
            ];
        }

        return [];
    }

    /**
     * Get question display with code.
     */
    public function getDisplayTextAttribute()
    {
        if ($this->code) {
            return "[{$this->code}] {$this->question_text}";
        }

        return $this->question_text;
    }

    /**
     * Get short question text (truncated).
     */
    public function getShortTextAttribute()
    {
        if (strlen($this->question_text) > 100) {
            return substr($this->question_text, 0, 100) . '...';
        }

        return $this->question_text;
    }

    /**
     * Check if question has been answered by a specific user response.
     */
    public function isAnsweredByResponse($userResponseId)
    {
        return $this->responseDetails()
            ->where('user_response_id', $userResponseId)
            ->exists();
    }

    /**
     * Get answer for a specific user response.
     */
    public function getAnswerForResponse($userResponseId)
    {
        return $this->responseDetails()
            ->where('user_response_id', $userResponseId)
            ->first();
    }
}
