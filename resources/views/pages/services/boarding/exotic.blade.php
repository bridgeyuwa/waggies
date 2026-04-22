<x-layouts.app title="Exotic Pet Boarding" nav-section="services">

    <x-hero.image
        image-src="/images/boarding-exotic.jpg"
        eyebrow="Exotic Pet Boarding · Abuja"
        title='Specialist Care for<br/>Exotic Pets'
        subtitle="Birds, reptiles, rabbits, guinea pigs, and small mammals — cared for by specialist staff in custom-built enclosures."
        :primary-cta="['label' => 'Enquire Now', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="We Board"
                title="Which Exotic Pets<br/>Do We Care For?"
                subtitle="Our exotic wing is staffed by specialists trained to care for the unique needs of non-traditional pets."
            />
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-10">
                @foreach(['Birds', 'Reptiles', 'Rabbits', 'Guinea Pigs', 'Hamsters', 'Fish'] as $pet)
                <div class="bg-surface-purple rounded-2xl p-5 flex flex-col items-center gap-2 text-center">
                    <span class="material-symbols-outlined text-3xl text-primary icon-filled">nest_eco_leaf</span>
                    <span class="text-sm font-semibold text-primary-dark">{{ $pet }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What's Included" title="Tailored Care for Every Species" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'home',           'title' => 'Species-Specific Enclosures', 'desc' => 'Custom-built housing with correct temperature, humidity, and lighting for each species.'],
                    ['icon' => 'restaurant',     'title' => 'Specialist Diet',              'desc' => 'Feeding according to each animal\'s specific dietary needs and your usual routine.'],
                    ['icon' => 'medical_services','title' => 'Exotic Vet Access',           'desc' => 'Access to our vet who has experience treating exotic and small animals.'],
                    ['icon' => 'notifications',  'title' => 'Regular Updates',              'desc' => 'Photo updates provided so you can check in on your pet throughout their stay.'],
                    ['icon' => 'lock_clock',     'title' => '24/7 Monitoring',              'desc' => 'Staff check on exotic guests throughout the day and night.'],
                    ['icon' => 'psychology',     'title' => 'Experienced Handlers',         'desc' => 'Our exotic staff are trained and experienced in handling a wide range of species.'],
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
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Enquire about exotic boarding</h2>
                <p class="text-primary-dark/60">Contact us to discuss your pet's specific needs and check availability.</p>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-3.5 rounded-full font-bold transition shadow-glow hover:-translate-y-1 shrink-0">
                Get in Touch <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
        </div>
    </section>


@if($faqs->isNotEmpty())
    <section class="py-16 bg-surface-purple/40">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions" class="mb-8" />
            <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => e($f->answer)])->all()" />
            <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.</p>
        </div>
    </section>
@endif

@push('head')
@php
echo \Spatie\SchemaOrg\Schema::service()
    ->name('Exotic Pet Boarding Abuja — Waggies')
    ->description('Specialist boarding for birds, reptiles, rabbits, guinea pigs, and small mammals in custom enclosures with experienced handlers.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
