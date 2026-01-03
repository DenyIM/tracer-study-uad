<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpVerificationMail; 

class OtpVerificationController extends Controller
{
    public function show()
    {
        // Pastikan ada email di session
        if (!session('email')) {
            return redirect()->route('register')->withErrors([
                'email' => 'Silakan daftar terlebih dahulu.'
            ]);
        }
        
        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $user = User::where('email', $request->email)
            ->where('otp_code', $request->otp)
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa']);
        }

        // Verifikasi berhasil
        $user->update([
            'email_verified_at' => now(),
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect()->route('public')->with('success', 'Akun berhasil diverifikasi');
    }

    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan.'
            ], 404);
        }

        // Generate OTP baru
        $otpCode = rand(100000, 999999);
        $otpExpires = now()->addMinutes(5);

        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => $otpExpires,
        ]);

        // Kirim email OTP
        try {
            // Ambil nama alumni jika ada
            $userName = $user->alumni->fullname ?? null;
            
            Mail::to($user->email)->send(new OtpVerificationMail($otpCode, $userName));
            
            // Simpan email di session untuk halaman OTP
            session(['email' => $user->email]);
            
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP baru telah dikirim ke email Anda.'
            ]);
        } catch (\Exception $e) {
            error_log('Gagal mengirim email OTP: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ], 500);
        }
    }
}