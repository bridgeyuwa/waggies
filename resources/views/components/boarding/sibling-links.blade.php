{{--
    Cross-links to other boarding service pages.

    Props:
      $current — dogs | cats | exotic (page to exclude from list)
--}}
@props([
    'current' => 'dogs',
])

@php
    $siblings = [
        'dogs' => [
            'route' => 'services.boarding.dogs',
            'title' => 'Dog Boarding',
            'desc' => 'Private suites, supervised play, and structured daily routines.',
            'icon' => 'pets',
            'image' => 'https://images.unsplash.com/photo-1601758228041-f3b2795255f1?auto=format&fit=crop&w=600&q=80',
        ],
        'cats' => [
            'route' => 'services.boarding.cats',
            'title' => 'Cat Boarding',
            'desc' => 'Calm, dog-free condos with enrichment and daily photo updates.',
            'icon' => 'emoticon',
            'image' => 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?auto=format&fit=crop&w=600&q=80',
        ],
        'exotic' => [
            'route' => 'services.boarding.exotic',
            'title' => 'Exotic Pet Boarding',
            'desc' => 'Species-specific enclosures with specialist handlers and vet access.',
            'icon' => 'bug_report',
            'image' => 'https://images.unsplash.com/photo-1548767797-d9f163aedbf1?auto=format&fit=crop&w=600&q=80',
        ],
    ];
    $others = collect($siblings)->except($current);
@endphp

<section class="py-16 bg-white border-t border-surface-purple" aria-label="Other boarding options">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
        <x-section-heading eyebrow="More Boarding" title="Explore Our Other Boarding Options"
            subtitle="Every species gets a dedicated environment — find the right stay for your pet." class="mb-10" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($others as $key => $sibling)
                <a href="{{ route($sibling['route']) }}"
                    class="group flex gap-5 p-5 rounded-2xl border border-surface-purple bg-surface-purple/30 hover:bg-surface-purple hover:shadow-soft transition-all focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                    <img src="{{ $sibling['image'] }}" alt=""
                        class="w-24 h-24 rounded-xl object-cover shrink-0 group-hover:scale-[1.02] transition-transform"
                        loading="lazy" decoding="async" aria-hidden="true">
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="material-symbols-outlined text-primary icon-filled text-lg"
                                aria-hidden="true">{{ $sibling['icon'] }}</span>
                            <h3 class="font-serif font-bold text-primary-dark group-hover:text-primary transition-colors">
                                {{ $sibling['title'] }}</h3>
                        </div>
                        <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $sibling['desc'] }}</p>
                        <span
                            class="mt-3 inline-flex items-center gap-1 text-sm font-semibold text-primary group-hover:underline">
                            Learn more
                            <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
