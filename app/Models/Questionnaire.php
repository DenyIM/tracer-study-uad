<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'title',
        'description',
        'order',
        'is_required',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the category that owns the questionnaire.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the questions for the questionnaire.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the user responses for the questionnaire.
     */
    public function userResponses()
    {
        return $this->hasMany(UserResponse::class);
    }

    /**
     * Scope a query to only include active questionnaires.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include required questionnaires.
     */
    public function scopeRequired($query)
    {
        return $query->where('is_required', true);
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
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get the display name with order.
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->name}: {$this->title}";
    }

    /**
     * Get the questionnaire number.
     */
    public function getQuestionnaireNumberAttribute()
    {
        return (int) filter_var($this->name, FILTER_SANITIZE_NUMBER_INT);
    }

    /**
     * Check if questionnaire has any questions.
     */
    public function hasQuestions()
    {
        return $this->questions()->exists();
    }

    /**
     * Get active questions count.
     */
    public function getActiveQuestionsCountAttribute()
    {
        return $this->questions()->active()->count();
    }

    /**
     * Get total questions count.
     */
    public function getTotalQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }

    /**
     * Get the next questionnaire in order.
     */
    public function getNextQuestionnaireAttribute()
    {
        return self::where('category_id', $this->category_id)
            ->where('order', '>', $this->order)
            ->active()
            ->ordered()
            ->first();
    }

    /**
     * Get the previous questionnaire in order.
     */
    public function getPreviousQuestionnaireAttribute()
    {
        return self::where('category_id', $this->category_id)
            ->where('order', '<', $this->order)
            ->active()
            ->ordered('desc')
            ->first();
    }

    /**
     * Check if this is the first questionnaire.
     */
    public function getIsFirstAttribute()
    {
        return !self::where('category_id', $this->category_id)
            ->where('order', '<', $this->order)
            ->active()
            ->exists();
    }

    /**
     * Check if this is the last questionnaire.
     */
    public function getIsLastAttribute()
    {
        return !self::where('category_id', $this->category_id)
            ->where('order', '>', $this->order)
            ->active()
            ->exists();
    }
}
