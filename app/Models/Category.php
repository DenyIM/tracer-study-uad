<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Set the icon attribute with proper formatting
     */
    public function setIconAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['icon'] = null;
        } else {
            // Pastikan icon memiliki format yang benar
            $icon = trim($value);
            
            // Jika tidak diawali dengan 'fas', 'fa-solid', 'fa-regular', dll, tambahkan 'fas'
            if (!preg_match('/^(fas|fa-solid|fa-regular|fa-light|fa-thin|fa-duotone|fa-brands)\s+fa-/i', $icon)) {
                if (strpos($icon, 'fa-') === 0) {
                    // Jika hanya 'fa-nama-icon', tambahkan 'fas'
                    $icon = 'fas ' . $icon;
                } elseif (!str_contains($icon, 'fa-')) {
                    // Jika tanpa 'fa-', tambahkan 'fas fa-'
                    $icon = 'fas fa-' . $icon;
                }
            }
            
            $this->attributes['icon'] = $icon;
        }
    }

    /**
     * Get all questionnaires for this category
     */
    public function questionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class);
    }

    /**
     * Get the general questionnaire for this category
     */
    public function generalQuestionnaire(): HasOne
    {
        return $this->hasOne(Questionnaire::class)->where('is_general', true);
    }

    /**
     * Get specific questionnaires (non-general)
     */
    public function specificQuestionnaires(): HasMany
    {
        return $this->hasMany(Questionnaire::class)->where('is_general', false);
    }

    /**
     * Get questionnaire sequence for this category
     */
    public function sequences(): HasMany
    {
        return $this->hasMany(QuestionnaireSequence::class)->orderBy('order');
    }

    /**
     * Get alumni statuses for this category
     */
    public function alumniStatuses(): HasMany
    {
        return $this->hasMany(StatusQuestionnaire::class);
    }

    /**
     * Get the URL for this category
     */
    public function getUrlAttribute(): string
    {
        return route('questionnaire.start', ['category' => $this->slug]);
    }

    /**
     * Get total questions count for this category
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->questionnaires()->withCount('questions')->get()->sum('questions_count');
    }

    /**
     * Check if category is selectable (active and has questionnaires)
     */
    public function getIsSelectableAttribute(): bool
    {
        return $this->is_active && $this->questionnaires()->where('is_required', true)->exists();
    }
}