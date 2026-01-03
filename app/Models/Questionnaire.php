<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Questionnaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'order',
        'is_required',
        'is_general',
        'time_estimate',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_general' => 'boolean',
        'time_estimate' => 'integer',
    ];

    /**
     * Get the category that owns the questionnaire
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all questions for this questionnaire
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    /**
     * Get required questions only
     */
    public function requiredQuestions(): HasMany
    {
        return $this->hasMany(Question::class)->where('is_required', true)->orderBy('order');
    }

    /**
     * Get optional questions only
     */
    public function optionalQuestions(): HasMany
    {
        return $this->hasMany(Question::class)->where('is_required', false)->orderBy('order');
    }

    /**
     * Get progress records for this questionnaire
     */
    public function progressRecords(): HasMany
    {
        return $this->hasMany(QuestionnaireProgress::class);
    }

    /**
     * Get the next questionnaire in sequence
     */
    public function nextQuestionnaire()
    {
        return $this->category->sequences()
            ->where('order', '>', function ($query) {
                $query->select('order')
                    ->from('questionnaire_sequences')
                    ->where('questionnaire_id', $this->id)
                    ->whereColumn('category_id', 'categories.id')
                    ->limit(1);
            })
            ->orderBy('order')
            ->first()
            ?->questionnaire;
    }

    /**
     * Get the previous questionnaire in sequence
     */
    public function previousQuestionnaire()
    {
        return $this->category->sequences()
            ->where('order', '<', function ($query) {
                $query->select('order')
                    ->from('questionnaire_sequences')
                    ->where('questionnaire_id', $this->id)
                    ->whereColumn('category_id', 'categories.id')
                    ->limit(1);
            })
            ->orderBy('order', 'desc')
            ->first()
            ?->questionnaire;
    }

    /**
     * Check if questionnaire has locked questions
     */
    public function hasLockedQuestions(): bool
    {
        return $this->questions()->where('is_locked_by_default', true)->exists();
    }
}