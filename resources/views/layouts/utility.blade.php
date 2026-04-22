@props([
    'title'       => null,
    'description' => null,
    'navSection'  => '',
])

<x-layouts.app
    :title="$title"
    :description="$description"
    :nav-section="$navSection"
>
    @isset($hero)
        {{ $hero }}
    @endisset

    <div class="max-w-5xl mx-auto px-4 md:px-10 py-12 md:py-20">
        @isset($breadcrumb)
            <div class="mb-8">{{ $breadcrumb }}</div>
        @endisset
        {{ $slot }}
    </div>
</x-layouts.app>
