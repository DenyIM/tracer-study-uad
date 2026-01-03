<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'nim',
        'date_of_birth',
        'phone',
        'study_program',
        'graduation_date',
        'npwp',
        'ranking',
        'points'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'graduation_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessors
    public function getAgeAttribute(): ?int
    {
        return $this->date_of_birth ? now()->diffInYears($this->date_of_birth) : null;
    }

    public function getGraduationAgeAttribute(): ?int
    {
        if (!$this->date_of_birth || !$this->graduation_date) {
            return null;
        }

        return $this->date_of_birth->diffInYears($this->graduation_date);
    }

    public function getYearsSinceGraduationAttribute(): ?int
    {
        return $this->graduation_date ? now()->diffInYears($this->graduation_date) : null;
    }

    public function getFormattedDateOfBirthAttribute(): ?string
    {
        return $this->date_of_birth ? $this->date_of_birth->format('d F Y') : null;
    }

    public function getFormattedGraduationDateAttribute(): ?string
    {
        return $this->graduation_date ? $this->graduation_date->format('d F Y') : null;
    }
}