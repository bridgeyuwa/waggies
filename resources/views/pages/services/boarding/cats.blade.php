<x-layouts.app title="Cat Boarding" nav-section="services">

    <x-hero.image
        image-src="/images/boarding-cats.jpg"
        eyebrow="Cat Boarding · Abuja"
        title='Calm, Cosy Stays<br/>for Your Cat'
        subtitle="Quiet cat condos, away from dogs, with enrichment toys, cosy nooks, and attentive care from cat-specialist staff."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Cat Boarding Includes"
                title="A Stress-Free Environment<br/>Designed for Cats"
                subtitle="Cats are sensitive travellers. Our cat suites are a calm, dog-free zone that respects how cats like to live."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'cabin',          'title' => 'Private Cat Condos',   'desc' => 'Multi-level condos with hammocks, perches, and cosy hideaway nooks.'],
                    ['icon' => 'do_not_disturb', 'title' => 'Dog-Free Zone',        'desc' => 'Our cat wing is completely separate from our boarding dogs for a calm, stress-free stay.'],
                    ['icon' => 'toys',           'title' => 'Daily Enrichment',     'desc' => 'Puzzle feeders, interactive toys, and one-on-one play sessions to keep cats engaged.'],
                    ['icon' => 'restaurant',     'title' => 'Tailored Feeding',     'desc' => 'Meals on your cat\'s usual schedule using your preferred brand or our premium food.'],
                    ['icon' => 'medical_services','title' => 'Vet Monitoring',      'desc' => 'Our on-site vet checks all cat guests daily and can address any health concerns immediately.'],
                    ['icon' => 'notifications',  'title' => 'Daily Photo Updates',  'desc' => 'Photo updates sent every day so you always know how your cat is doing.'],
                ] as $item)
                <div class="bg-surface-purple rounded-2xl p-6 flex gap-4">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-primary icon-filled">{{ $item['icon'] }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-primary-dark mb-1">{{ $item['title'] }}</h3>
                        <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Ready to book your cat's stay?</h2>
                <p class="text-primary-dark/60">Get in touch to check availability and reserve a condo.</p>
            </div>
            <div class="flex gap-3 shrink-0">
                <a href="{{ route('services.pricing') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">View Pricing</a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-1">Book Now <span class="material-symbols-outlined text-base">arrow_forward</span></a>
            </div>
        </div>
    </section>


@if($faqs->isNotEmpty())
    <section class="py-16 bg-surface-purple/40">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
            <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => $f->answer])->all()" />
            <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.</p>
        </div>
    </section>
@endif

@push('head')
@php
echo \Spatie\SchemaOrg\Schema::service()
    ->name('Cat Boarding Abuja — Waggies')
    ->description('Luxury cat boarding in Abuja in a calm, dog-free environment with private condos, enrichment, and daily photo updates.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
