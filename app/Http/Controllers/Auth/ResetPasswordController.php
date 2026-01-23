<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showForgotPassword()
    {
        return view('auth.reset-password');
    }

    /**
     * Kirim email reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@(?:webmail|[a-zA-Z0-9.-]+)\.uad\.ac\.id$/']
        ], [
            'email.regex' => 'Email harus menggunakan format UAD: @webmail.uad.ac.id untuk alumni atau @*.uad.ac.id untuk admin'
        ]);

        // Cek apakah email terdaftar
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak terdaftar'
            ], 404);
        }

        // Generate kode verifikasi 6 digit
        $verificationCode = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        // Hapus tabel jika belum ada (untuk development)
        if (!Schema::hasTable('password_reset_codes')) {
            // Buat tabel sementara di session
            session(['password_reset_codes' => []]);
        }

        // Simpan kode verifikasi
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $request->email],
            [
                'code' => $verificationCode,
                'created_at' => now(),
                'expires_at' => $expiresAt
            ]
        );

        // Kirim email dengan kode verifikasi
        try {
            Mail::to($user->email)->send(new PasswordResetMail($verificationCode, $user->alumni->fullname ?? null));
            
            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi telah dikirim ke email Anda'
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Verifikasi kode reset password
     */
    public function verifyCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|digits:6'
            ]);

            // Cek kode verifikasi
            $resetCode = DB::table('password_reset_codes')
                ->where('email', $request->email)
                ->where('code', $request->code)
                ->where('expires_at', '>', now())
                ->first();

            if (!$resetCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode verifikasi tidak valid atau sudah kadaluarsa'
                ], 400);
            }

            // Generate token untuk reset password
            $token = Str::random(60);
            
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi valid',
                'token' => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Error verifyCode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required',
                'password' => ['required', 'confirmed', Rules\Password::defaults()]
            ]);

            // Validasi token
            $passwordReset = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->first();

            if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token reset password tidak valid'
                ], 400);
            }

            // Cek apakah token masih valid (max 1 jam)
            if (now()->diffInMinutes($passwordReset->created_at) > 60) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Token reset password telah kadaluarsa'
                ], 400);
            }

            // Update password user
            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }

            $user->update([
                'password' => Hash::make($request->password)
            ]);

            // Hapus token dan kode verifikasi setelah berhasil reset
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            DB::table('password_reset_codes')->where('email', $request->email)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset'
            ]);
        } catch (\Exception $e) {
            Log::error('Error resetPassword: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }

    /**
     * Kirim ulang kode verifikasi
     */
    public function resendCode(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email'
            ]);

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email tidak terdaftar'
                ], 404);
            }

            // Generate kode verifikasi baru
            $verificationCode = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            // Update kode verifikasi
            DB::table('password_reset_codes')->updateOrInsert(
                ['email' => $request->email],
                [
                    'code' => $verificationCode,
                    'created_at' => now(),
                    'expires_at' => $expiresAt
                ]
            );

            // Kirim email
            try {
                Mail::to($user->email)->send(new PasswordResetMail($verificationCode, $user->alumni->fullname ?? null));
                
                return response()->json([
                    'success' => true,
                    'message' => 'Kode verifikasi baru telah dikirim'
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal mengirim ulang email: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim email. Silakan coba lagi.'
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error resendCode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server'
            ], 500);
        }
    }
}