@props([
    'title'       => null,
    'description' => null,
    'navSection'  => '',
    'lastUpdated' => null,
])

<x-layouts.app
    :title="$title"
    :description="$description"
    :nav-section="$navSection"
>
    <div class="max-w-3xl mx-auto px-4 md:px-8 py-16 md:py-24">

        <header class="mb-12">
            <div class="mb-6">
                @isset($breadcrumb)
                    {{ $breadcrumb }}
                @else
                    <x-breadcrumb />
                @endisset
            </div>
            <h1 class="font-serif text-4xl font-bold text-primary-dark mb-3">{{ $title }}</h1>
            @if($lastUpdated)
                <p class="text-sm text-primary-dark/50">Last updated: {{ $lastUpdated }}</p>
            @endif
        </header>

        <div class="prose max-w-none">
            {{ $slot }}
        </div>

    </div>
</x-layouts.app>
