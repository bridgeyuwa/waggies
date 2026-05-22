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
        @isset($breadcrumb)
            <div class="bg-white border-b border-primary/5">
                <div class="max-w-5xl mx-auto px-4 md:px-10 py-4">
                    {{ $breadcrumb }}
                </div>
            </div>
        @else
            <x-breadcrumb.strip class="bg-white border-b border-primary/5" />
        @endisset
        {{ $hero }}
    @else
        <div class="max-w-5xl mx-auto px-4 md:px-10 pt-12 md:pt-20">
            <div class="mb-8">
                @isset($breadcrumb)
                    {{ $breadcrumb }}
                @else
                    <x-breadcrumb />
                @endisset
            </div>
        </div>
    @endisset

    <div class="max-w-5xl mx-auto px-4 md:px-10 {{ isset($hero) ? 'py-12 md:py-20' : 'pb-12 md:pb-20' }}">
        {{ $slot }}
    </div>
</x-layouts.app>
