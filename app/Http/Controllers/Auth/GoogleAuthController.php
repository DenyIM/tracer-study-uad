<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alumni;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(Str::random(16)),
                'role' => 'alumni',
                'email_verified_at' => now(), // Google sudah valid
                'google_id' => $googleUser->getId(),
            ]);

            Alumni::create([
                'user_id' => $user->id,
                'fullname' => $googleUser->getName(),
            ]);
        }

        Auth::login($user);

        return redirect()->route('main');
    }
}
