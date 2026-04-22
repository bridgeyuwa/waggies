@props([
    'title'       => null,
    'description' => null,
    'navSection'  => 'blog',
])

<x-layouts.app
    :title="$title"
    :description="$description"
    :nav-section="$navSection"
>
    {{-- Page header band --}}
    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-16">
            @isset($breadcrumb)
                <div class="mb-6">{{ $breadcrumb }}</div>
            @endisset
            {{ $header }}
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-16">
        <div class="flex flex-col lg:flex-row gap-12">

            <div class="flex-1 min-w-0">
                {{ $slot }}
                @isset($pagination)
                    <div class="mt-12">{{ $pagination }}</div>
                @endisset
            </div>

            @isset($sidebar)
                <aside class="w-full lg:w-72 xl:w-80 shrink-0 flex flex-col gap-6">
                    {{ $sidebar }}
                </aside>
            @endisset

        </div>
    </div>
</x-layouts.app>
