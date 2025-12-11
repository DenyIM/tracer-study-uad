<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'admin';

    protected $fillable = [
        'name',
        'email',
        'username',
        'phone',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Cek jika super admin
    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    // Cek jika admin biasa
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Cek jika operator
    public function isOperator()
    {
        return $this->role === 'operator';
    }

    // Scope untuk admin aktif
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    // Scope berdasarkan role
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
