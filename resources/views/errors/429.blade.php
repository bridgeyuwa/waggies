@extends('layouts.app', ['headStatus' => 429, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="429"
        title="Too many requests"
        description="Please wait a moment before trying again."
    />
@endsection
