@props([
    'title'       => null,
    'description' => null,
    'navSection'  => 'services',
    'ogImage'     => null,
])

<x-layouts.app
    :title="$title"
    :description="$description"
    :nav-section="$navSection"
    :og-image="$ogImage"
>
    @isset($hero)
        {{ $hero }}
    @endisset

    {{ $slot }}
</x-layouts.app>
