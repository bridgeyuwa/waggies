<x-layouts.app title="Our Services" nav-section="services">

    <x-hero.image image-src="/images/services.jpg" eyebrow="Everything Your Pet Needs" variant="center"
        title='Luxury Pet Care,<br/>All Under One Roof'
        subtitle="From overnight boarding to international relocation — Waggies offers a full suite of premium services tailored to your pet."
        :primary-cta="['label' => 'View Our Services', 'href' => route('services.index') . '#services']" />



    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What We Offer" title='Services Built Around<br/>Your Pet&rsquo;s Wellbeing'
                subtitle="Every service is designed with your pet's comfort, health, and happiness at the centre." />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-card.service title="Luxury Boarding"
                    description="Spacious, climate-controlled suites with 24/7 supervision and daily photo updates."
                    href="{{ route('services.boarding.index') }}" image-src="/images/boarding.jpg" icon="apartment" />
                <x-card.service title="Grooming Spa"
                    description="Breed-specific baths, trims, and styling by certified groomers in a calm spa environment."
                    href="{{ route('services.grooming') }}" image-src="/images/grooming.jpg" icon="spa" />
                <x-card.service title="Vet Care"
                    description="On-site veterinary consultations, vaccinations, and routine wellness check-ups."
                    href="{{ route('services.vet-care') }}" image-src="/images/vetcare.jpg" icon="medical_services" />
                <x-card.service title="Dog Training"
                    description="Positive-reinforcement programmes for puppies and adult dogs of all breeds."
                    href="{{ route('services.training') }}" image-src="/images/training.jpg" icon="school" />
                <x-card.service title="Pet Transport"
                    description="Air-conditioned door-to-door pickup and drop-off anywhere across Abuja."
                    href="{{ route('services.transport') }}" image-src="/images/transport.jpg" icon="local_shipping" />
                <x-card.service title="Pet Relocation"
                    description="Stress-free international moves with full documentation and airline coordination."
                    href="{{ route('relocation.index') }}" image-src="/images/relocation.jpg" icon="flight_takeoff" />
            </div>
        </div>
    </section>



    <section class="py-12 bg-surface-purple/40 border-y border-primary/10">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">

                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">verified</span>
                    <p class="font-bold text-primary-dark text-sm">Vet-Supervised Care</p>
                    <p class="text-xs text-primary-dark/50">Health-first handling</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">schedule</span>
                    <p class="font-bold text-primary-dark text-sm">24/7 Monitoring</p>
                    <p class="text-xs text-primary-dark/50">Constant supervision</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">pets</span>
                    <p class="font-bold text-primary-dark text-sm">500+ Pets Served</p>
                    <p class="text-xs text-primary-dark/50">Across Abuja & beyond</p>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-2xl">star</span>
                    <p class="font-bold text-primary-dark text-sm">4.9 Average Rating</p>
                    <p class="text-xs text-primary-dark/50">Trusted by pet owners</p>
                </div>

            </div>

        </div>
    </section>

    <section id="standards" class="w-full bg-primary-dark text-white py-24 relative overflow-hidden">

        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 relative z-10 grid lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT --}}
            <div class="max-w-xl">
                <p class="text-secondary uppercase text-xs tracking-widest font-bold">
                    The Waggies Standard
                </p>

                <h2 class="font-serif text-3xl md:text-4xl font-bold mt-3">
                    Why Our Care System<br><span class="text-secondary italic">Works So Well</span>
                </h2>

                <p class="text-white/70 mt-4 leading-relaxed">
                    Every pet is handled through structured care protocols designed to ensure safety, comfort, and
                    emotional wellbeing.
                </p>
            </div>

            {{-- RIGHT --}}
            <div class="flex flex-col gap-5">

                <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
                    <h3 class="font-bold">Structured Daily Routines</h3>
                    <p class="text-white/60 text-sm">Professionally trained handlers and protocols.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
                    <h3 class="font-bold">24/7 Supervision</h3>
                    <p class="text-white/60 text-sm">Continuous monitoring, day and night.</p>
                </div>

                <div class="p-5 rounded-2xl bg-white/5 border border-white/10">
                    <h3 class="font-bold">Daily Updates</h3>
                    <p class="text-white/60 text-sm">Owners receive real-time pet updates.</p>
                </div>

            </div>

        </div>

    </section>

    <section class="w-full py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <x-cta.primary :badge-text="'Trusted by 2,000+ Pet Owners'" :heading="'Give Your Pet the'" :heading-accent="'Care They Deserve'" :body="'Book a service or speak with our care team today.'" :primary-cta="['label' => 'Contact', 'href' => route('contact'), 'icon' => null]"
                :secondary-cta="['label' => 'View Pricing', 'href' => '#', 'icon' => null]" />

            <x-cta.secondary :heading="'Experience the Waggies Difference'" :body="'From our state-of-the-art facilities to our compassionate care team, discover why Waggies is the trusted choice for pet owners in Abuja.'" :primary-cta="['label' => 'Learn More', 'href' => route('about.index'), 'icon' => null]" />

        </div>
    </section>




    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::webPage()
                ->name('Our Services — Waggies Luxury Pet Care Abuja')
                ->description(
                    'Full suite of premium pet services in Abuja: boarding, grooming, vet care, training, transport and relocation.',
                )
                ->url(url()->current())
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>
