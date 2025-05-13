<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Auth\InvitedUserMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function inviteUsers(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('ambassador') && $user->approved) {
            Mail::to($request->email)->send(new InvitedUserMail($user->ambassador_uuid));

            return response(['message' => __('mail.mail_sent')]);
        }

        return response(['message' => __('mail.permission_denied')]);
    }
}
