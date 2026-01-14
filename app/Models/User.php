<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'role',
        'provider',
        'provider_id',
        'verification_string',
        'pp_url',
        'last_login_at',
        'otp_code',
        'otp_expires_at',
        'email_verified_at',
        'theme_preference'
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    public function alumni()
    {
        return $this->hasOne(Alumni::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    // Helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    public function getProfile()
    {
        if ($this->isAlumni()) {
            return $this->alumni;
        } elseif ($this->isAdmin()) {
            return $this->admin;
        }

        return null;
    }

    public function getFullnameAttribute(): ?string
    {
        if ($this->isAlumni() && $this->alumni) {
            return $this->alumni->fullname;
        } elseif ($this->isAdmin() && $this->admin) {
            return $this->admin->fullname;
        }

        return null;
    }

    public function getPhoneAttribute(): ?string
    {
        if ($this->isAlumni() && $this->alumni) {
            return $this->alumni->phone;
        } elseif ($this->isAdmin() && $this->admin) {
            return $this->admin->phone;
        }

        return null;
    }

    // Add these methods to your existing User model

    public function getStatusBadgeAttribute(): string
    {
        if ($this->email_verified_at) {
            return '<span class="status-badge badge-success">
                        <i class="bi bi-check-circle me-1"></i> Terverifikasi
                    </span>';
        }
        
        return '<span class="status-badge badge-warning">
                    <i class="bi bi-clock me-1"></i> Belum Verifikasi
                </span>';
    }

    public function getLastLoginFormattedAttribute(): string
    {
        if (!$this->last_login_at) {
            return '<small class="text-muted">Belum pernah</small>';
        }
        
        return '<small>' . $this->last_login_at->diffForHumans() . '</small>';
    }
}
