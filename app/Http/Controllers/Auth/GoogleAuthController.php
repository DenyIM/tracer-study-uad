<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    // Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $email = $googleUser->getEmail();
            $googleName = $googleUser->getName();
            
            // **VALIDASI 1: Format email UAD**
            if (!$this->isValidUADEmail($email)) {
                return redirect()->route('login')
                    ->with('error', 'Hanya email @webmail.uad.ac.id yang diperbolehkan.');
            }
            
            // **VALIDASI 2: Extract dan validasi data dari email**
            $emailData = $this->extractDataFromEmail($email);
            
            if (!$emailData) {
                return redirect()->route('login')
                    ->with('error', 'Format email tidak valid. Contoh format: nama10digitNIM@webmail.uad.ac.id');
            }
            
            $nameFromEmail = $emailData['name'];
            $nimFromEmail = $emailData['nim'];
            
            // **VALIDASI 3: Panjang NIM harus 10 digit**
            if (strlen($nimFromEmail) !== 10) {
                return redirect()->route('login')
                    ->with('error', 'NIM harus 10 digit angka. Format: nama10digitNIM@webmail.uad.ac.id');
            }
            
            // **VALIDASI 4: NIM harus angka semua**
            if (!is_numeric($nimFromEmail)) {
                return redirect()->route('login')
                    ->with('error', 'NIM harus berupa angka. Format: nama10digitNIM@webmail.uad.ac.id');
            }
            
            // Cek apakah user sudah ada
            $user = User::where('email', $email)->first();
            
            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)),
                    'role' => 'alumni',
                    'email_verified_at' => now(),
                    'google_id' => $googleUser->getId(),
                ]);
            } else {
                // Update google_id jika belum ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }
            
            // Cek apakah alumni sudah ada
            $alumni = Alumni::where('user_id', $user->id)->first();
            
            if (!$alumni) {
                // Cek apakah NIM dari email sudah digunakan oleh user lain
                $existingAlumniWithNim = Alumni::where('nim', $nimFromEmail)
                    ->where('user_id', '!=', $user->id)
                    ->first();
                
                if ($existingAlumniWithNim) {
                    // NIM sudah digunakan oleh user lain
                    return redirect()->route('login')
                        ->with('error', 'NIM dari email Anda sudah terdaftar dengan akun lain. Hubungi administrator.');
                }
                
                // Buat data alumni dengan NIM dari email
                $alumniData = [
                    'user_id' => $user->id,
                    'fullname' => $googleName, // Simpan nama dari Google
                    'nim' => $nimFromEmail,    // Simpan NIM dari email
                    'name_from_email' => $nameFromEmail, // Simpan nama dari email untuk referensi
                    'study_program' => null,
                    'graduation_date' => null,
                    'phone' => null,
                    'npwp' => null,
                    'is_data_complete' => false,
                ];
                
                $alumni = Alumni::create($alumniData);
            }
            
            // Login user
            Auth::login($user);
            
            // Redirect berdasarkan status data
            if ($alumni->is_data_complete) {
                return redirect()->route('public');
            } else {
                // Simpan data untuk warning di form
                session()->flash('email_warning', [
                    'name_from_email' => ucfirst($nameFromEmail),
                    'name_from_google' => $googleName,
                    'is_name_mismatch' => !$this->namesMatch($nameFromEmail, $googleName)
                ]);
                
                return redirect()->route('alumni.registration.form')
                    ->with('info', 'Silakan lengkapi data alumni Anda.');
            }
            
        } catch (\Exception $e) {
            Log::error('Google Auth Error: ' . $e->getMessage());
            
            // Pesan error yang user-friendly
            $errorMessage = 'Login dengan Google gagal.';
            
            if (strpos($e->getMessage(), 'Integrity constraint violation') !== false) {
                $errorMessage = 'Terjadi kesalahan database. Silakan hubungi administrator.';
            } elseif (strpos($e->getMessage(), 'Connection') !== false) {
                $errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
            }
            
            return redirect()->route('login')->with('error', $errorMessage);
        }
    }
    
    /**
     * Validasi format email UAD
     * Format: namaNIM@webmail.uad.ac.id
     * Contoh: deny2100018138@webmail.uad.ac.id
     */
    private function isValidUADEmail($email)
    {
        // Pattern: nama (huruf saja) + NIM (angka) + @webmail.uad.ac.id
        $pattern = '/^[a-zA-Z]+[0-9]+@webmail\.uad\.ac\.id$/';
        
        return preg_match($pattern, $email) === 1;
    }
    
    /**
     * Extract data dari email UAD
     * Mengembalikan array dengan 'name' dan 'nim'
     */
    private function extractDataFromEmail($email)
    {
        $username = strstr($email, '@', true);
        
        // Pattern: nama (huruf) + NIM (angka)
        if (preg_match('/^([a-zA-Z]+)([0-9]+)$/', $username, $matches)) {
            return [
                'name' => strtolower($matches[1]), // nama dalam lowercase
                'nim' => $matches[2]               // NIM
            ];
        }
        
        return null;
    }
    
    /**
     * Cek kecocokan nama (untuk warning saja)
     * Tidak strict, hanya untuk informasi
     */
    private function namesMatch($nameFromEmail, $googleName)
    {
        // Normalisasi: lowercase dan hapus spasi ekstra
        $emailNameNorm = strtolower(trim($nameFromEmail));
        $googleNameNorm = strtolower(trim($googleName));
        
        // Cari nama di googleName (bisa lebih panjang)
        return strpos($googleNameNorm, $emailNameNorm) !== false || 
               strpos($emailNameNorm, substr($googleNameNorm, 0, 3)) !== false;
    }
}