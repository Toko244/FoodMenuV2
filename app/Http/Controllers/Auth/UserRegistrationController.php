<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Mail\Auth\AmbassadorRegistrationMail;
use App\Mail\Auth\UserRegistrationMail;
use App\Models\Invitation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserRegistrationController extends Controller
{
    public function signUp(RegisterRequest $request)
    {
        DB::table('invitations')->where('email', $request->email)->delete();

        $invitation = Invitation::create([
            'email' => $request->input('email'),
            'token' => Str::random(60),
            'ambassador_uuid' => $request->input('ambassador_uuid'),
        ]);

        if (User::where('email', $invitation->email)->where('approved', false)->exists()) {
            return response(['message' => __('auth.under_review')], 409);
        }

        if (User::where('email', $request->email)->exists()) {
            return response(['message' => __('auth.user_exists')], 422);
        }

        $template = new UserRegistrationMail($invitation->token);

        if ($request->user_type === 'ambassador') {
            $template = new AmbassadorRegistrationMail($invitation->token);
        }

        Mail::to($request->email)->send($template);

        return response([
            'message' => __('mail.check_email'),
        ], 200);
    }

    public function completeRegistration(UserRegistrationRequest $request)
    {
        $data = $request->validated();
        $invitation = Invitation::where('token', $data['token'])->first();

        if (!$invitation) {
            return response()->json(['message' => __('auth.token')], 422);
        }

        if (! empty($invitation->ambassador_uuid)) {
            $referal = User::where('ambassador_uuid', $invitation->ambassador_uuid)->first();
            $data['ambassador_id'] = $referal->id;
        }

        $data['email'] = $invitation['email'];
        $data['email_verified_at'] = now();

        $user = User::create($data);

        $user->assignRole('user');

        $invitation->delete();

        return response([
            'message' => __('auth.registration_completed'),
        ]);
    }

    public function checkRegistrationToken(Request $request)
    {
        $invitation = Invitation::where('token', $request->token)
            ->where('created_at', '>=', Carbon::now()->subHours(config('auth.passwords.users.expire')))
            ->first();

        if (empty($invitation)) {
            return response(['message' => __('auth.token')], 422);
        }

        return response([
            'message' => __('auth.success'),
            'email' => $invitation->email,
        ], 200);
    }
}
