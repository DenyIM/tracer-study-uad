<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'icon',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the questionnaires for the category.
     */
    public function questionnaires()
    {
        return $this->hasMany(Questionnaire::class);
    }

    /**
     * Get the user responses for the category.
     */
    public function userResponses()
    {
        return $this->hasMany(UserResponse::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by name.
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }

    /**
     * Get the icon HTML.
     */
    public function getIconHtmlAttribute()
    {
        return "<i class='{$this->icon}'></i>";
    }

    /**
     * Get the display name (with icon).
     */
    public function getDisplayNameAttribute()
    {
        return "{$this->iconHtml} {$this->name}";
    }

    /**
     * Check if category has any questionnaires.
     */
    public function hasQuestionnaires()
    {
        return $this->questionnaires()->exists();
    }

    /**
     * Get active questionnaires count.
     */
    public function getActiveQuestionnairesCountAttribute()
    {
        return $this->questionnaires()->active()->count();
    }

    /**
     * Get total questionnaires count.
     */
    public function getTotalQuestionnairesCountAttribute()
    {
        return $this->questionnaires()->count();
    }
}
