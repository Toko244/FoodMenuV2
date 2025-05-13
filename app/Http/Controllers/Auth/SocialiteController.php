<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    // ------------------------ what should each case return?

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        return $this->handleSocialCallback('google');
    }

    public function handleFacebookCallback()
    {
        return $this->handleSocialCallback('facebook');
    }

    private function handleSocialCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $existingUser = User::where("{$provider}_id", $socialUser->id)
                                ->orWhere('email', $socialUser->getEmail())
                                ->first();

            if ($existingUser) {
                if (!$existingUser->getAttribute("{$provider}_id")) {
                    $existingUser->update(["{$provider}_id" => $socialUser->id]);
                }
            } else {
                $existingUser = User::create([
                    'name' => $firstName,
                    'surname' => $surname,
                    'email' => $socialUser->getEmail(),
                    "{$provider}_id" => $socialUser->getId(),
                    'password' => Hash::make(Str::random(16)),
                ]);
            }

            return response([
                'access_token' => $existingUser->createToken('API Token')->plainTextToken,
                'user' => $existingUser,
            ]);

        } catch (\Throwable $th) {
            return response(['message' => __('auth.something_went_wrong')], 422);
        }
    }
}
