<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // app/Http/Controllers/Auth/GoogleController.php
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Buat user baru jika belum terdaftar
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'email_verified_at' => now(),
                ]);

                // Buat entri OAuth provider
                $user->oauthProviders()->create([
                    'provider' => 'google',
                    'provider_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'token' => $googleUser->token,
                ]);

                // Assign role default
                $user->assignRole('member');
            }

            // Login user
            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }
}
