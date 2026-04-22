<x-layouts.app title="About Us" nav-section="about">

    <x-hero.image
        image-src="/images/about.jpg"
        eyebrow="About Waggies"
        title='Abuja&rsquo;s Most Trusted<br/>Luxury Pet Care'
        subtitle="Founded on a simple belief: every pet deserves to be treated like family. Waggies brings world-class facilities and genuine expertise to Abuja."
        :primary-cta="['label' => 'Get in Touch', 'href' => route('contact')]"
        :secondary-cta="['label' => 'Our Services', 'href' => route('services.index')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Our Story</span>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-5 leading-tight">
                        Built by Pet Lovers,<br/>for Pet Owners
                    </h2>
                    <p class="text-primary-dark/60 leading-relaxed mb-4">
                        Waggies was born from a personal experience — a pet owner in Abuja struggling to find a boarding facility that matched the standard of care they gave their own dog at home. What started as a small dog hotel has grown into Abuja's most comprehensive luxury pet care centre.
                    </p>
                    <p class="text-primary-dark/60 leading-relaxed mb-4">
                        Today, we offer boarding, grooming, vet care, training, transport, and international relocation — all under one roof, all to the same uncompromising standard.
                    </p>
                    <p class="text-primary-dark/60 leading-relaxed">
                        Every member of our team is an animal lover first. We believe the best pet care comes from genuine passion — not just process.
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    @foreach([
                        ['value' => '500+', 'label' => 'Happy Clients'],
                        ['value' => '7',    'label' => 'Years in Business'],
                        ['value' => '4.9',  'label' => 'Average Rating'],
                        ['value' => '15+',  'label' => 'Team Members'],
                    ] as $stat)
                    <div class="bg-surface-purple rounded-2xl p-6 text-center">
                        <span class="font-serif text-4xl font-bold text-primary block mb-1">{{ $stat['value'] }}</span>
                        <span class="text-xs font-semibold uppercase tracking-widest text-primary-dark/50">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <x-feature-band
        eyebrow="Our Values"
        title='What Makes Waggies<br/><span class="text-secondary italic">Different</span>'
        subtitle="We hold ourselves to a higher standard — because your pets deserve nothing less."
        :cta="['label' => 'Meet the Team', 'href' => route('about.index')]"
        :features="[
            ['icon' => 'favorite',         'title' => 'Genuine Care',          'description' => 'We treat every animal as our own — with patience, love, and attention.'],
            ['icon' => 'verified',         'title' => 'PCSA Certified',        'description' => 'Fully licensed and regulated by the Pet Care Services Association of Nigeria.'],
            ['icon' => 'school',           'title' => 'Trained Professionals', 'description' => 'Every team member is formally trained in their area of expertise.'],
            ['icon' => 'transparency',     'title' => 'Full Transparency',     'description' => 'Daily updates, open facilities, and honest communication — always.'],
        ]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Explore More" title="Learn More About Waggies" />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                <x-card.service title="Testimonials"  description="Read what hundreds of satisfied pet owners have to say about their Waggies experience."   href="{{ route('about.testimonials') }}"  image-src="/images/testimonials.jpg"  icon="star" />
                <x-card.service title="Photo Gallery" description="Take a look inside our facilities — boarding suites, grooming spa, and play areas."         href="{{ route('about.gallery') }}"       image-src="/images/gallery.jpg"       icon="photo_library" />
                <x-card.service title="Careers"       description="Passionate about animals? Join our growing team of pet care professionals in Abuja."        href="{{ route('about.careers') }}"       image-src="/images/careers.jpg"       icon="work" />
                <x-card.service title="Partnerships"  description="We work with vets, breeders, and businesses who share our commitment to animal welfare."     href="{{ route('about.partnerships') }}"  image-src="/images/partnerships.jpg"  icon="handshake" />
            </div>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::aboutPage()
    ->name('About Waggies — Abuja\'s Most Trusted Luxury Pet Care')
    ->description("Founded on a belief that every pet deserves to be treated like family. Waggies brings world-class facilities and genuine expertise to Abuja.")
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
