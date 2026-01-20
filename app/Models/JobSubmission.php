<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'alumni_id',
        'company_name',
        'position',
        'location',
        'job_description',
        'qualifications',
        'field',
        'deadline',
        'link',
        'contact',
        'status',
        'points_awarded',
        'admin_notes',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'deadline' => 'date',
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