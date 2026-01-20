<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionOption extends Model
{
    protected $table = 'question_options';
    protected $fillable = [
        'questions_id', 'nilai', 'label', 'urutan',
        'memerlukan_input_lainnya', 'placeholder_input_lainnya'
    ];
    
    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'questions_id');
    }
}