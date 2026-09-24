@extends('layouts.app', ['headStatus' => 503, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="503"
        title="Temporarily unavailable"
        description="Waggies is taking a short break. Please try again in a moment."
        primary-label="Try again"
        primary-href="{{ url()->current() }}"
        secondary-label="Return to homepage"
        secondary-href="{{ route('home') }}"
    />
@endsection
