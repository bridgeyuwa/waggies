@extends('layouts.app', ['headStatus' => 401, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="401"
        title="Unauthorized"
        description="You need to sign in before viewing this page. If you think you should have access, contact Waggies."
    />
@endsection
