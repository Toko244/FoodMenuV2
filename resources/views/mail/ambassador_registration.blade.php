@extends('mail.layout', ['title' => 'Ambassador Registration'])

@section('content')

<body style="font-family: Arial, sans-serif; margin: 0; padding: 0;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td style="padding: 20px 0; text-align: center;">
                <h2 style="margin-bottom: 20px;">{{ __('mail.continue_registration') }}</h2>
                <p>{{ __('mail.ambassador_message') }}</p>
                <p><a href="{{ config('web_url.ambassador_registration_link') . $token }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">{{ __('mail.continue_registration') }}</a></p>
            </td>
        </tr>
    </table>
</body>

@endsection
