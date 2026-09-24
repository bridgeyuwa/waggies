@extends('layouts.app', ['headStatus' => 500, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="500"
        title="Something went wrong"
        description="We hit a problem while loading this page. Please try again or return to Waggies."
        primary-label="Try again"
        primary-href="{{ url()->current() }}"
        secondary-label="Return to homepage"
        secondary-href="{{ route('home') }}"
    />
@endsection
