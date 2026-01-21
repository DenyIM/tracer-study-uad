<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'phone',
        'job_title'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Tambahkan method untuk mendapatkan URL foto profil
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->user->pp_url) {
            // Jika ada URL foto profil, gunakan itu
            return asset('storage/' . $this->user->pp_url);
        } else {
            // Jika tidak ada, generate dari UI Avatars berdasarkan nama
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->fullname) . '&background=0d6efd&color=fff';
        }
    }
}