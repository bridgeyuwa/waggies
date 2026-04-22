<x-layouts.app title="Shop" nav-section="">

    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-24 text-center">
            <span class="inline-block text-xs font-bold uppercase tracking-widest text-primary/60 mb-3">Coming Soon</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-4">The Waggies Shop</h1>
            <p class="text-primary-dark/60 max-w-xl mx-auto mb-8">
                Premium pet food, accessories, grooming products, and more — hand-picked by the Waggies team. Launching soon.
            </p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Notify Me When Live <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </div>

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
