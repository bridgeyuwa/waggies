<x-layouts.app title="Shop" nav-section="">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-ui.page-header
        align="center"
        eyebrow="Coming Soon"
        title="The Waggies Shop"
        subtitle="Premium pet food, accessories, grooming products, and more — hand-picked by the Waggies team. Launching soon."
    >
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
            Notify Me When Live <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
        </a>
    </x-ui.page-header>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What to Expect" title="What We'll Be Stocking" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                @foreach([
                    ['icon' => 'restaurant',  'title' => 'Premium Pet Food',    'desc' => 'Nutritionist-approved dry, wet, and raw diets from leading brands.'],
                    ['icon' => 'spa',         'title' => 'Grooming Products',   'desc' => 'Professional-grade shampoos, conditioners, and styling tools.'],
                    ['icon' => 'toys',        'title' => 'Toys & Enrichment',   'desc' => 'Stimulating toys, puzzle feeders, and enrichment items.'],
                    ['icon' => 'hotel',       'title' => 'Beds & Accessories',  'desc' => 'Orthopedic beds, travel crates, collars, leads, and more.'],
                ] as $item)
                <div class="bg-surface-purple rounded-2xl p-6 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary icon-filled">{{ $item['icon'] }}</span>
                    </div>
                    <h3 class="font-semibold text-primary-dark">{{ $item['title'] }}</h3>
                    <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Shop — Waggies Pet Care')
    ->description('Premium pet food, accessories, and grooming products hand-picked by the Waggies team. Coming soon.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
