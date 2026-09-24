@extends('layouts.app', ['headStatus' => 403, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <x-waggies.error-page
        code="403"
        title="Access denied"
        description="You do not have permission to view this page. If you think you should have access, contact Waggies."
    />
@endsection
