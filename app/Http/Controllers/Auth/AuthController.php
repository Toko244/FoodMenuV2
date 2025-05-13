<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $user->load('roles:name', 'permissions:name');

        $user->roles->makeHidden('pivot');
        $user->permissions->makeHidden('pivot');

        return response([
            'access_token' => $user->createToken('API Token')->plainTextToken,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => __('auth.logout'),
        ], 200);
    }

    public function me()
    {
        $user =  auth()->user()->load('roles:name', 'permissions:name');

        $user->roles->makeHidden('pivot');
        $user->permissions->makeHidden('pivot');

        return $user;
    }
}
