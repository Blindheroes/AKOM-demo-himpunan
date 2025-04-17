<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\OAuthProvider;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect user to Google authentication page
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Error redirecting to Google: ' . $e->getMessage());
            return redirect()->route('landingpage')
                ->with('error', 'Gagal terhubung ke Google. Silakan coba lagi.');
        }
    }

    /**
     * Handle Google callback after authentication
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            Log::info('Google auth callback received', [
                'email' => $googleUser->email,
                'id' => $googleUser->id
            ]);

            // Cek apakah pengguna sudah pernah login dengan Google (via provider_id)
            $oauthProvider = OAuthProvider::where('provider', 'google')
                ->where('provider_id', $googleUser->id)
                ->first();

            if ($oauthProvider) {
                Auth::login($oauthProvider->user);
                Log::info('Existing user logged in via Google OAuth', [
                    'user_id' => $oauthProvider->user->id,
                    'email' => $oauthProvider->user->email,
                    
                ]);
                return redirect()->route('dashboard');
            }

            // Jika belum, cek apakah email sudah terdaftar
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Buat user baru jika email belum terdaftar
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(24)),
                    'role' => 'member', // Default role
                ]);

                Log::info('New user created from Google OAuth', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);
            }

            // Buat atau perbarui entry OAuth provider
            $user->oauthProviders()->updateOrCreate(
                [
                    'provider' => 'google',
                    'provider_id' => $googleUser->id
                ],
                [
                    'avatar' => $googleUser->avatar,
                    'token' => $googleUser->token,
                    'refresh_token' => $googleUser->refreshToken ?? null,
                    'token_expires_at' => isset($googleUser->expiresIn) ? now()->addSeconds($googleUser->expiresIn) : null,
                ]
            );

            // Login user
            Auth::login($user);
            Log::info('User logged in via Google', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            Log::error('Google callback error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('landingpage')
                ->with('error', 'Login dengan Google gagal. Detail: ' . $e->getMessage());
        }
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('landingpage')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
