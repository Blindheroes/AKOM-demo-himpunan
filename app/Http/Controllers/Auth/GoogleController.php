<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            // log
            Log::info('Google User:', [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'avatar' => $googleUser->avatar,
                'token' => $googleUser->token,
            ]);

            // Cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->email)->first();
            // log
            Log::info('User:', [
                'id' => $user->id ?? null,
                'email' => $googleUser->email,
            ]);

            if (!$user) {
                // log
                Log::info('User not found, creating new user:', [
                    'email' => $googleUser->email,
                ]);

                // Buat user baru jika belum terdaftar
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'email_verified_at' => now(),

                ]);
                // log
                Log::info('New user created:', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
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
            // log
            Log::info('User logged in:', [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            return redirect()->route('landingpage')->with('error', 'Login dengan Google gagal. Silakan coba lagi.');
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('landingpage')->with('success', 'Anda telah berhasil logout.');
    }
}
