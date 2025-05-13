<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AmbassadorRegistrationRequest;
use App\Mail\Auth\AmbassadorOnPendingMail;
use App\Mail\Auth\SubmitAmbassadorRegistrationMail;
use App\Models\Country;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class AmbassadorRegistrationController extends Controller
{
    public function completeRegistration(AmbassadorRegistrationRequest $request)
    {
        $data = $request->validated();
        $invitation = Invitation::where('token', $data['token'])->first();

        $data['email'] = $invitation['email'];
        $data['email_verified_at'] = now();
        $data['approved'] = false;

        $user = User::create($data);

        $user->assignRole('ambassador');

        $user->load('country:name,id');
        Mail::to($invitation->email)->send(new AmbassadorOnPendingMail);
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new SubmitAmbassadorRegistrationMail($user));

        $invitation->delete();

        return response([
            'message' => __('mail.mail_sent'),
        ]);
    }

    public function formData()
    {
        return response([
            'countries' => Country::select('id', 'name')->get(),
        ]);
    }
}
