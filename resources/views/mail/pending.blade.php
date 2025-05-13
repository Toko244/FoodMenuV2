@extends('mail.layout', ['title' => 'Ambassador Pending'])

@section('content')

<body>
    <div class="container">
        <h1>{{ __('mail.pending_registration') }}</h1>
        <p>{{ __('mail.pending_message') }}</p>
        <a href="{{ env('APP_URL') }}" class="btn">{{ __('mail.our_website') }}</a>
    </div>
</body>

@endsection
