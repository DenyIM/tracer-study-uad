<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'questionnaire_id',
        'order',
        'is_required',
        'unlocks_next',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'unlocks_next' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get the category that owns the sequence
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the questionnaire
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get the next sequence in order
     */
    public function next()
    {
        return self::where('category_id', $this->category_id)
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get the previous sequence in order
     */
    public function previous()
    {
        return self::where('category_id', $this->category_id)
            ->where('order', '<', $this->order)
            ->orderBy('order', 'desc')
            ->first();
    }

    /**
     * Check if this sequence unlocks the next one when completed
     */
    public function unlocksNext(): bool
    {
        return $this->unlocks_next;
    }

    /**
     * Check if this is the first sequence in the category
     */
    public function isFirst(): bool
    {
        return $this->order === 1;
    }

    /**
     * Check if this is the last sequence in the category
     */
    public function isLast(): bool
    {
        $maxOrder = self::where('category_id', $this->category_id)->max('order');
        return $this->order === $maxOrder;
    }
}