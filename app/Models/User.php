<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'nim',
        'program_studi',
        'tanggal_lulus',
        'npwp',
        'no_hp',
        'password',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'tanggal_lulus' => 'date',
        ];
    }

    /**
     * Scope untuk mencari berdasarkan NIM
     */
    public function scopeByNim($query, $nim)
    {
        return $query->where('nim', $nim);
    }

    /**
     * Scope untuk mencari berdasarkan program studi
     */
    public function scopeByProgramStudi($query, $programStudi)
    {
        return $query->where('program_studi', $programStudi);
    }

    /**
     * Scope untuk alumni berdasarkan tahun lulus
     */
    public function scopeByTahunLulus($query, $tahun)
    {
        return $query->whereYear('tanggal_lulus', $tahun);
    }

    /**
     * Mutator untuk mengubah format NPWP
     */
    public function setNpwpAttribute($value)
    {
        if ($value) {
            // Hapus semua karakter non-digit
            $clean = preg_replace('/\D/', '', $value);
            // Format: XX.XXX.XXX.X-XXX.XXX
            if (strlen($clean) == 15) {
                $this->attributes['npwp'] = substr($clean, 0, 2) . '.' .
                    substr($clean, 2, 3) . '.' .
                    substr($clean, 5, 3) . '.' .
                    substr($clean, 8, 1) . '-' .
                    substr($clean, 9, 3) . '.' .
                    substr($clean, 12, 3);
            } else {
                $this->attributes['npwp'] = $value;
            }
        } else {
            $this->attributes['npwp'] = null;
        }
    }

    /**
     * Mutator untuk format nomor HP
     */
    public function setNoHpAttribute($value)
    {
        // Hapus semua karakter non-digit
        $clean = preg_replace('/\D/', '', $value);

        // Jika diawali dengan 0, ganti dengan 62
        if (str_starts_with($clean, '0')) {
            $clean = '62' . substr($clean, 1);
        }

        // Jika belum diawali dengan 62, tambahkan
        if (!str_starts_with($clean, '62')) {
            $clean = '62' . $clean;
        }

        $this->attributes['no_hp'] = $clean;
    }

    /**
     * Accessor untuk nama lengkap (title case)
     */
    public function getNamaLengkapAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Accessor untuk program studi (title case)
     */
    public function getProgramStudiAttribute($value)
    {
        return ucwords(strtolower($value));
    }

    /**
     * Accessor untuk tahun lulus
     */
    public function getTahunLulusAttribute()
    {
        return $this->tanggal_lulus ? $this->tanggal_lulus->format('Y') : null;
    }

    /**
     * Accessor untuk format tanggal lulus (d-m-Y)
     */
    public function getTanggalLulusFormattedAttribute()
    {
        return $this->tanggal_lulus ? $this->tanggal_lulus->format('d-m-Y') : null;
    }

    /**
     * Validasi bahwa user adalah alumni UAD
     */
    public function isAlumniUad()
    {
        // Anda bisa menambahkan logika validasi tambahan di sini
        return !empty($this->nim) && !empty($this->program_studi);
    }

    /**
     * Hitung lama sejak lulus (dalam tahun)
     */
    public function getLamaLulusAttribute()
    {
        if ($this->tanggal_lulus) {
            return now()->diffInYears($this->tanggal_lulus);
        }
        return null;
    }
}
