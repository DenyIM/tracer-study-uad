<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question_id',
        'option_text',
        'value',
        'label',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the question that owns the option.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Scope a query to order by order field.
     */
    public function scopeOrdered($query, $direction = 'asc')
    {
        return $query->orderBy('order', $direction)
            ->orderBy('value', $direction)
            ->orderBy('id', $direction);
    }

    /**
     * Scope a query to filter by question.
     */
    public function scopeByQuestion($query, $questionId)
    {
        return $query->where('question_id', $questionId);
    }

    /**
     * Scope a query to filter by value.
     */
    public function scopeByValue($query, $value)
    {
        return $query->where('value', $value);
    }

    /**
     * Check if option has a value.
     */
    public function hasValue()
    {
        return !is_null($this->value);
    }

    /**
     * Check if option has a label.
     */
    public function hasLabel()
    {
        return !empty($this->label);
    }

    /**
     * Get display text with value/label.
     */
    public function getDisplayTextAttribute()
    {
        if ($this->hasValue() && $this->hasLabel()) {
            return "{$this->value}. {$this->label} - {$this->option_text}";
        } elseif ($this->hasValue()) {
            return "{$this->value}. {$this->option_text}";
        } else {
            return $this->option_text;
        }
    }

    /**
     * Get short display text.
     */
    public function getShortTextAttribute()
    {
        if (strlen($this->option_text) > 50) {
            return substr($this->option_text, 0, 50) . '...';
        }

        return $this->option_text;
    }

    /**
     * Check if this is the "Other" option.
     */
    public function getIsOtherOptionAttribute()
    {
        return str_contains(strtolower($this->option_text), 'lainnya') ||
            str_contains(strtolower($this->option_text), 'other') ||
            str_contains(strtolower($this->option_text), 'sebutkan');
    }

    /**
     * Get the next option in order.
     */
    public function getNextOptionAttribute()
    {
        return self::where('question_id', $this->question_id)
            ->where('order', '>', $this->order)
            ->ordered()
            ->first();
    }

    /**
     * Get the previous option in order.
     */
    public function getPreviousOptionAttribute()
    {
        return self::where('question_id', $this->question_id)
            ->where('order', '<', $this->order)
            ->ordered('desc')
            ->first();
    }

    /**
     * Check if this is the first option.
     */
    public function getIsFirstAttribute()
    {
        return !self::where('question_id', $this->question_id)
            ->where('order', '<', $this->order)
            ->exists();
    }

    /**
     * Check if this is the last option.
     */
    public function getIsLastAttribute()
    {
        return !self::where('question_id', $this->question_id)
            ->where('order', '>', $this->order)
            ->exists();
    }

    /**
     * Get option as array for form.
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'question_id' => $this->question_id,
            'option_text' => $this->option_text,
            'value' => $this->value,
            'label' => $this->label,
            'order' => $this->order,
            'display_text' => $this->display_text,
            'is_other_option' => $this->is_other_option,
        ];
    }
}
