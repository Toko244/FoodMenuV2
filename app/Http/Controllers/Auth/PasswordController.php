<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                ? response(['status' => __('auth.sent')])
                : throw ValidationException::withMessages(['email' => [__('auth.'.$status, ['seconds' => config('auth.passwords.users.throttle')])]]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response(['message' => __($request->messages()[$status])], 200);
        } else {
            return response(['message' => __($request->messages()[$status])], 500);
        }
    }

    public function checkPasswordToken(EmailRequest $request)
    {
        $error = '';
        $data = $request->validated();
        $email = $data['email'];
        $token = $request->token;

        $db = DB::table('password_reset_tokens')->where('email', $email)->first();

        $checkToken = Hash::check($token, $db->token);
        if (! $checkToken || ! $this->tokenExpired($email)) {
            $error = __('auth.token');
        }

        $url = config('app.url')."/reset-password?token=$token&email=$email";

        if (! empty($error)) {
            $url .= "&error=$error";
        }

        return redirect($url);
    }

    public function tokenExpired($email)
    {
        $db = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(config('auth.passwords.users.expire')))
            ->exists();
        if ($db) {
            return true;
        }

        return false;
    }
}
