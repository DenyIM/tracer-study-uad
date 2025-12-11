<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResponseDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_response_id',
        'question_id',
        'answer_text',
        'answer_value',
        'other_answer',
        'matrix_answers',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'answer_value' => 'integer',
        'matrix_answers' => 'array',
    ];

    /**
     * Get the user response that owns the detail.
     */
    public function userResponse()
    {
        return $this->belongsTo(UserResponse::class);
    }

    /**
     * Get the question that owns the detail.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Scope a query to filter by question.
     */
    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Scope a query to filter by question type.
     */
    public function scopeByQuestionType($query, $type)
    {
        return $query->whereHas('question', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Check if response has text answer.
     */
    public function hasTextAnswer()
    {
        return !empty($this->answer_text);
    }

    /**
     * Check if response has value answer.
     */
    public function hasValueAnswer()
    {
        return !is_null($this->answer_value);
    }

    /**
     * Check if response has other answer.
     */
    public function hasOtherAnswer()
    {
        return !empty($this->other_answer);
    }

    /**
     * Check if response has matrix answers.
     */
    public function hasMatrixAnswers()
    {
        return !empty($this->matrix_answers) && is_array($this->matrix_answers);
    }

    /**
     * Get the answer display text.
     */
    public function getAnswerDisplayAttribute()
    {
        if ($this->hasTextAnswer()) {
            return $this->answer_text;
        }

        if ($this->hasValueAnswer()) {
            // Coba dapatkan label dari question options
            if ($this->question && $this->question->options) {
                $option = $this->question->options->firstWhere('value', $this->answer_value);
                if ($option) {
                    return $option->option_text;
                }
            }
            return (string) $this->answer_value;
        }

        if ($this->hasOtherAnswer()) {
            return "Lainnya: {$this->other_answer}";
        }

        if ($this->hasMatrixAnswers()) {
            return 'Jawaban Matrix (Lihat Detail)';
        }

        return 'Tidak Dijawab';
    }

    /**
     * Get the answer for display in summary.
     */
    public function getAnswerSummaryAttribute()
    {
        $answer = $this->answer_display;

        if (strlen($answer) > 100) {
            return substr($answer, 0, 100) . '...';
        }

        return $answer;
    }

    /**
     * Get matrix answers formatted.
     */
    public function getMatrixAnswersFormattedAttribute()
    {
        if (!$this->hasMatrixAnswers()) {
            return [];
        }

        $formatted = [];
        foreach ($this->matrix_answers as $key => $value) {
            $formatted[] = [
                'item' => $key,
                'value' => $value,
                'label' => $this->getMatrixLabel($value),
            ];
        }

        return $formatted;
    }

    /**
     * Get label for matrix value.
     */
    protected function getMatrixLabel($value)
    {
        $labels = [
            1 => 'Sangat Rendah',
            2 => 'Rendah',
            3 => 'Cukup',
            4 => 'Tinggi',
            5 => 'Sangat Tinggi',
        ];

        return $labels[$value] ?? $value;
    }

    /**
     * Check if this response is for a matrix question.
     */
    public function isMatrixQuestion()
    {
        return $this->question && in_array($this->question->type, ['competency_scale', 'matrix']);
    }

    /**
     * Check if this response is for a scale question.
     */
    public function isScaleQuestion()
    {
        return $this->question && $this->question->type === 'scale';
    }

    /**
     * Check if this response is for a text question.
     */
    public function isTextQuestion()
    {
        return $this->question && in_array($this->question->type, ['text', 'textarea']);
    }

    /**
     * Check if this response is for a choice question.
     */
    public function isChoiceQuestion()
    {
        return $this->question && in_array($this->question->type, ['dropdown', 'radio', 'checkbox']);
    }

    /**
     * Get the answer value as integer.
     */
    public function getNumericValueAttribute()
    {
        if ($this->hasValueAnswer()) {
            return $this->answer_value;
        }

        if ($this->hasMatrixAnswers()) {
            // Rata-rata nilai matrix
            $values = array_values($this->matrix_answers);
            return count($values) > 0 ? array_sum($values) / count($values) : 0;
        }

        return null;
    }

    /**
     * Check if answer is valid (not empty).
     */
    public function isValid()
    {
        return $this->hasTextAnswer() ||
            $this->hasValueAnswer() ||
            $this->hasOtherAnswer() ||
            $this->hasMatrixAnswers();
    }

    /**
     * Get answer timestamp formatted.
     */
    public function getAnsweredAtFormattedAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }
}
