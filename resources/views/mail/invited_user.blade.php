@extends('mail.layout', ['title' => 'User Registration'])

@section('content')

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td style="padding: 20px 0; text-align: center;">
                <h2 style="margin-bottom: 20px;">Continue Registration</h2>
                <p>Hi there!</p>
                <p>You have been invited to continue your registration.</p>
                <p>Please click the link below to complete your registration:</p>
                <p><a href="{{ config('web_url.referral_link') . $referal_id }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Continue Registration</a></p>
                <p>If you didn't request this invitation, you can ignore this email.</p>
            </td>
        </tr>
    </table>
</body>

@endsection
