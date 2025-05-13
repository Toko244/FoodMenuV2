@extends('mail.layout', ['title' => 'Submit Ambassador'])

@section('content')

<body>
    <div class="container">
        <h2>{{ __('mail.submit_ambassador') }}</h2>
        <p>{{ __('mail.submitted_message') }}</p>

        <div class="user-details">
            <p><strong>{{ __('mail.name') }}:</strong> {{ $user->name }}</p>
            <p><strong>{{ __('mail.surname') }}:</strong> {{ $user->surname }}</p>
            <p><strong>{{ __('mail.email') }}:</strong> {{ $user->email }}</p>
            <p><strong>{{ __('mail.phone') }}:</strong> {{ $user->phone }}</p>
            <p><strong>{{ __('mail.date_of_birth') }}:</strong> {{ $user->date_of_birth }}</p>
            <p><strong>{{ __('mail.personal_number') }}:</strong> {{ $user->personal_number }}</p>
            <p><strong>{{ __('mail.city') }}:</strong> {{ $user->city }}</p>
            <p><strong>{{ __('mail.address') }}:</strong> {{ $user->address }}</p>
            <p><strong>{{ __('mail.country') }}:</strong> {{ $user->country->name }}</p>
        </div>

        {{-- <a href="{{  }}" class="button">Submit Ambassador</a> --}}
    </div>
</body>


@endsection
