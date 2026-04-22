<x-layouts.app title="Partnerships" nav-section="about">

    <div class="bg-surface-purple border-b border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-16 md:py-20">
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Partnerships</span>
            <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-4">Better Together</h1>
            <p class="text-primary-dark/60 max-w-xl">We partner with aligned organisations — vets, breeders, pet retailers, and businesses — who share our commitment to animal welfare and quality care.</p>
        </div>
    </div>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Partnership Types"
                title="How We Work<br/>With Partners"
                subtitle="Whether you're a veterinary clinic, a pet shop, or a corporate with pet-owning employees, we have a partnership model that works."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'local_hospital',  'title' => 'Veterinary Clinics',      'desc' => 'Cross-referral partnerships with vet clinics across Abuja — ensuring continuity of care for shared patients.'],
                    ['icon' => 'storefront',      'title' => 'Pet Retailers',            'desc' => 'Partner with pet shops and suppliers to offer our clients exclusive product discounts and bundled services.'],
                    ['icon' => 'pets',            'title' => 'Breeders',                 'desc' => 'We work with licensed breeders to provide early socialisation and health checks for new litters.'],
                    ['icon' => 'business',        'title' => 'Corporate Partners',       'desc' => 'Corporate employee benefit packages — discounted Waggies services for your pet-owning staff.'],
                    ['icon' => 'apartment',       'title' => 'Property Developers',      'desc' => 'Partner with residential developers to offer Waggies services to residents as a premium amenity.'],
                    ['icon' => 'diversity_3',     'title' => 'NGOs & Rescue Orgs',       'desc' => 'We support animal rescue and welfare NGOs with discounted services and pro bono care where we can.'],
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

    <section class="py-16 bg-surface-purple">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">Interested in partnering with us?</h2>
            <p class="text-primary-dark/60 mb-6">We'd love to explore how we can work together. Get in touch to start the conversation.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Get in Touch <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Partnerships — Waggies Pet Care Abuja')
    ->description('Waggies partners with veterinary clinics, pet retailers, breeders, and corporate organisations who share our commitment to animal welfare.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
