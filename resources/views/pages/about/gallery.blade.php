<x-layouts.app title="Photo Gallery" nav-section="about">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-ui.page-header
        eyebrow="Photo Gallery"
        title="Take a Look Inside"
        subtitle="Our facilities speak for themselves. Browse our boarding suites, grooming spa, play areas, and the happy faces of our guests."
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <x-section-heading eyebrow="Boarding" title="Boarding Suites &amp; Play Areas" class="mb-8" />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-16">
                @foreach(range(1, 8) as $i)
                <div class="aspect-square rounded-2xl bg-surface-purple overflow-hidden">
                    <img src="/images/gallery/boarding-{{ $i }}.jpg"
                         alt="Boarding facility photo {{ $i }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                         loading="lazy" />
                </div>
                @endforeach
            </div>

            <x-section-heading eyebrow="Grooming" title="Grooming Spa" class="mb-8" />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-16">
                @foreach(range(1, 4) as $i)
                <div class="aspect-square rounded-2xl bg-surface-purple overflow-hidden">
                    <img src="/images/gallery/grooming-{{ $i }}.jpg"
                         alt="Grooming spa photo {{ $i }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                         loading="lazy" />
                </div>
                @endforeach
            </div>

            <x-section-heading eyebrow="Happy Guests" title="Our Wonderful Guests" class="mb-8" />
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach(range(1, 8) as $i)
                <div class="aspect-square rounded-2xl bg-surface-purple overflow-hidden">
                    <img src="/images/gallery/guests-{{ $i }}.jpg"
                         alt="Happy guest photo {{ $i }}"
                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                         loading="lazy" />
                </div>
                @endforeach
            </div>

        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Photo Gallery — Waggies Pet Care Abuja')
    ->description("Browse Waggies' boarding suites, grooming spa, outdoor play areas, and happy guest photos.")
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
