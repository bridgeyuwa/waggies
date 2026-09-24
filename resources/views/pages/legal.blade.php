@extends('layouts.app')


@section('content')
    <div class="mx-auto max-w-3xl px-4 py-16 md:px-8 md:py-24">
        <header class="mb-12">
            <div class="mb-6"><x-waggies.breadcrumb-strip :items="[['label' => $legalTitle, 'route' => $legalRoute]]" class="mx-0! max-w-none! px-0! py-0!" /></div>
            <h1 class="mb-3 font-serif text-4xl font-bold text-primary-dark">{{ $legalTitle }}</h1>
            <p class="text-sm text-primary-dark/50">Last updated: 1 January 2026</p>
        </header>
        <div class="prose max-w-none">
            @foreach ($sections as $section)
                @if ($section['heading'])<h2>{{ $section['heading'] }}</h2>@endif
                <p>{!! $section['paragraph'] !!}</p>
                @if (!empty($section['list']))<ul>@foreach ($section['list'] as $item)<li>{!! $item !!}</li>@endforeach</ul>@endif
            @endforeach
        </div>
    </div>
@endsection
