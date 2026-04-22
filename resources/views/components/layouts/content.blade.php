@props([
    'title'       => null,
    'description' => null,
    'navSection'  => 'blog',
    'ogImage'     => null,
    'canonical'   => null,
])

<x-layouts.app
    :title="$title"
    :description="$description"
    :nav-section="$navSection"
    :og-image="$ogImage"
    :canonical="$canonical"
>
    @isset($hero)
        {{ $hero }}
    @endisset

    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-10 md:py-16">

        @isset($breadcrumb)
            <div class="mb-8">{{ $breadcrumb }}</div>
        @endisset

        <div class="flex flex-col lg:flex-row gap-12 xl:gap-16">

            <article class="flex-1 min-w-0">
                <div class="prose max-w-none">
                    {{ $slot }}
                </div>
            </article>

            @isset($sidebar)
                <aside class="w-full lg:w-80 xl:w-96 shrink-0 flex flex-col gap-6">
                    {{ $sidebar }}
                </aside>
            @endisset

        </div>
    </div>
</x-layouts.app>
