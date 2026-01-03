<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Alumni;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Mail\OtpVerificationMail;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9]+@webmail\.uad\.ac\.id$/', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'nim' => ['required', 'unique:alumnis'],
            'prodi' => ['required'],
            'tanggal_lulus' => ['required', 'date'],
            'no_hp' => ['required', 'string', 'max:20'],
            'npwp' => ['nullable', 'string', 'max:20'],
            'agree_terms' => ['required', 'accepted'],
        ], [
            'email.regex' => 'Email harus menggunakan format UAD: namanim@webmail.uad.ac.id',
            'agree_terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'alumni',
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Alumni::create([
            'user_id' => $user->id,
            'fullname' => $request->nama,
            'nim' => $request->nim,
            'study_program' => $request->prodi,
            'graduation_date' => $request->tanggal_lulus,
            'phone' => $request->no_hp,
            'npwp' => $request->npwp ?? null,
        ]);

        // Kirim OTP ke email
        Mail::to($user->email)->send(new OtpVerificationMail($otp));

        // Redirect ke halaman input OTP
        return redirect()->route('otp.show')->with('email', $user->email);
    }
}