@extends('layouts.app')

@section('content')
    <x-waggies.page-header
        eyebrow="Waggies"
        eyebrow-icon="pets"
        title="Waggies services and support are available"
        description="Contact us or stay connected for updates."
        alignment="center"
    >
        <x-slot:actions>
            <div class="flex flex-wrap justify-center gap-3">
                <x-waggies.button href="{{ route('contact') }}">
                    Contact Waggies
                    <x-waggies.icon name="arrow-forward" size="16" />
                </x-waggies.button>
                <x-waggies.button href="{{ route('home') }}" variant="secondary">
                    Return home
                </x-waggies.button>
            </div>
        </x-slot:actions>
    </x-waggies.page-header>
@endsection
