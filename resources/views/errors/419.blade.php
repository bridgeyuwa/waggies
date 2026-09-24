@extends('layouts.app', ['headStatus' => 419, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="419"
        title="Page expired"
        description="This form session has expired. Return to Waggies and start again."
    />
@endsection
