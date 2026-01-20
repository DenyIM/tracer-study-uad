<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForumSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'alumni_id',
        'category',
        'title',
        'description',
        'date_time',
        'location',
        'link',
        'contact',
        'status',
        'points_awarded',
        'admin_notes',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'date_time' => 'datetime',
        'verified_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function alumni()
    {
        return $this->belongsTo(Alumni::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}