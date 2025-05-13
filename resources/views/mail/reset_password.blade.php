@extends('mail.layout', ['title' => __('mail.title')])

@section('content')

<tr>
    <td style="height:40px;">&nbsp;</td>
</tr>
<tr>
    <td style="padding:0 35px;">
        <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:32px;font-family:'Rubik',sans-serif;">
            {{ __('mail.user_request') }}</h1>
        <span
            style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
        <p style="color:#455056; font-size:15px;line-height:24px; margin:0;">
            {{ __('mail.user_message') }}
        </p>
        <a href="{{ $url }}"
            style="background:#20e277;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:50px;">
            {{ __('mail.reset_password') }}</a>
    </td>
</tr>
<tr>
    <td style="height:40px;">&nbsp;</td>
</tr>
</table>
</td>
<tr>
    <td style="height:20px;">&nbsp;</td>
</tr>
<tr>
    <td style="text-align:center;">
        <p style="font-size:14px; color:rgba(69, 80, 86, 0.7411764705882353); line-height:18px; margin:0 0 0;">&copy;
            <strong>
                <a style="text-decoration:none !important; font-weight:500;color:black; font-size:14px;"
                    href="mailto:{{ config('mail.support_email') }}">{{ config('mail.support_email') }}
                </a>
            </strong>
    </td>
</tr>
<tr>
    <td style="height:80px;">&nbsp;</td>
</tr>

@endsection
