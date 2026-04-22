<x-layouts.app title="Luxury Pet Care, Abuja" nav-section="home">
    <x-hero.image image-src="/images/hero.jpg" eyebrow="Abuja's #1 Pet Services"
        title='Premium pet services in the heart of Abuja'
        subtitle="From international relocation to 5-star boarding suites. We provide world-class veterinary and grooming services tailored for the discerning pet owner."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('services.boarding.index')]"
        :secondary-cta="['label' => 'View Services', 'href' => route('services.index')]" />

    <x-hero.gradient title='Premium pet services<br/><span class="text-secondary italic">in the heart of Abuja</span>'
        subtitle="From international relocation to 5-star boarding suites. We provide world-class veterinary and grooming services tailored for the discerning pet owner."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('services.boarding.index')]"
        :secondary-cta="['label' => 'View Services', 'href' => route('services.index')]" layout="centered" />

    <x-hero.action title="Premium pet services" highlight="in the heart of Abuja"
        subtitle="From international relocation to 5-star boarding suites. We provide world-class veterinary and grooming services tailored for the discerning pet owner."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('services.boarding.index')]"
        :secondary-cta="['label' => 'View Services', 'href' => route('services.index')]" />

    {{-- Services grid --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What We Offer" title='World-Class Care<br/>for Every Pet'
                subtitle="From luxury overnight boarding to international relocation — all under one roof." />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-card.service title="Luxury Boarding"
                    description="Premium overnight stays in spacious, climate-controlled suites with 24/7 care."
                    href="{{ route('services.boarding.index') }}" image-src="/images/boarding.jpg" icon="apartment" />
                <x-card.service title="Grooming Spa"
                    description="Breed-specific treatments, luxury baths, and styling by certified pet groomers."
                    href="{{ route('services.grooming') }}" image-src="/images/grooming.jpg" icon="spa" />
                <x-card.service title="Vet Care"
                    description="On-site veterinary consultations, vaccinations, and wellness check-ups."
                    href="{{ route('services.vet-care') }}" image-src="/images/vetcare.jpg" icon="medical_services" />
                <x-card.service title="Dog Training"
                    description="Positive-reinforcement training programmes for puppies and adult dogs."
                    href="{{ route('services.training') }}" image-src="/images/training.jpg" icon="school" />
                <x-card.service title="Pet Relocation"
                    description="Stress-free international pet moves with full documentation support."
                    href="{{ route('relocation.index') }}" image-src="/images/relocation.jpg" icon="flight_takeoff" />
                <x-card.service title="Local Transport"
                    description="Door-to-door pickup and drop-off across Abuja, in air-conditioned vehicles."
                    href="{{ route('services.transport') }}" image-src="/images/transport.jpg" icon="local_shipping" />
            </div>
        </div>
    </section>

    {{-- Feature band --}}
    <x-feature-band eyebrow="The Waggies Difference"
        title='Why Pet Owners<br/><span class="text-secondary italic">Choose Us</span>'
        subtitle="We treat every animal as family — combining luxury facilities with genuine expertise."
        :cta="['label' => 'Learn About Us', 'href' => route('about.index')]" :features="[
        ['icon' => 'verified', 'title' => 'PCSA Certified', 'description' => 'Fully licensed by the Pet Care Services Association of Nigeria.'],
        ['icon' => 'medical_services', 'title' => 'On-site Vet', 'description' => 'A qualified veterinarian is on-site every day of the week.'],
        ['icon' => 'lock_clock', 'title' => '24/7 Supervision', 'description' => 'Your pets are monitored around the clock — never left alone.'],
        ['icon' => 'hotel', 'title' => 'Luxury Suites', 'description' => 'Spacious, climate-controlled suites with orthopedic bedding.'],
        ['icon' => 'notifications', 'title' => 'Daily Photo Updates', 'description' => 'Receive photos and updates on your pet every single day.'],
    ]" />

    {{-- Testimonials --}}
    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What Clients Say" title="Trusted by 500+ Pet Owners" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card.testimonial stars="5"
                    quote="Waggies is the only place I'd trust with my dog. The daily updates give me real peace of mind."
                    author-initial="A" author-name="Adaeze O." author-subtitle="Dog owner · Maitama" />
                <x-card.testimonial stars="5"
                    quote="The grooming team transformed my Persian cat. She looked like she'd come straight from a pet show!"
                    author-initial="E" author-name="Emeka N." author-subtitle="Cat owner · Wuse II" />
                <x-card.testimonial stars="5"
                    quote="Our relocation from London was seamless. Every document was sorted — zero stress on our end."
                    author-initial="F" author-name="Fatima M." author-subtitle="Relocation client · Asokoro" />
            </div>
        </div>
    </section>

    <x-cta-section title="Ready to experience the Waggies difference?"
        subtitle="Book your pet's stay or consultation today and see why we're Abuja's most trusted pet care provider."
        :primary-cta="['label' => 'Book Now', 'href' => route('services.boarding.index')]"
        :secondary-cta="['label' => 'Contact Us', 'href' => route('contact')]" 
    />

    
<section class="w-full">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
        <x-cta.primary :badge-text="'Trusted by 2,000+ Pet Owners'" :heading="'Give Your Pet the'"
            :heading-accent="'Care They Deserve'" :body="'Book a consultation today. Our team is ready to create a personalised care plan for your furry family member.'"
            :primary-cta="['label' => 'Book a Free Consultation', 'href' => '#']"
            :secondary-cta="['label' => 'Call Us Now', 'href' => '#', 'icon' => 'call']" 
        />
   
    </div>
</section>

    <x-blog-section label="From the Blog" heading="Pet Care Tips &amp; News"
        subheading="Expert advice, heartwarming stories, and the latest from Waggies HQ." view-all-url="#">

    </x-blog-section>


    {{-- home.blade.php (guides section) --}}

    <section id="guides-section" class="py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">

            <div class="flex items-end justify-between mb-10">
                <div>
                    <span class="text-primary font-bold tracking-widest uppercase text-xs mb-2 block">Free
                        Resources</span>
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">Pet Care
                        Guides</h2>
                    <p class="text-primary-dark/50 mt-2 text-sm max-w-md">Downloadable checklists, how-to guides, and
                        expert reference material — all free.</p>
                </div>
                <a href="{{ route('guides.index') }}"
                    class="hidden sm:inline-flex items-center gap-1.5 text-primary font-semibold text-sm uppercase tracking-wide hover:text-primary-light transition-colors shrink-0 ml-6">
                    Browse all guides <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

            <x-guides.featured-card title="The Complete Pet Relocation Checklist for Nigeria"
                description="Everything you need to move your pet internationally — documents, timelines, vaccinations, and airline requirements in one place."
                downloadUrl="{{ route('home', 'relocation-checklist') }}"
                readUrl="{{ route('guides.show', 'relocation-checklist') }}" :pages="12" />

            <div
                class="divide-y divide-surface-purple border border-surface-purple rounded-2xl bg-white overflow-hidden">
                <x-guides.featured-card-row icon="vaccines" title="Nigeria Pet Vaccination Schedule 2026"
                    meta="Vet-approved · 8 min read · Vaccination"
                    href="{{ route('guides.show', 'vaccination-schedule-2026') }}" badge="Updated"
                    badgeVariant="success" />
                <x-guides.featured-card-row icon="flight"
                    title="Airline Pet Policies: Which Airlines Fly Pets from Lagos?"
                    meta="Relocation · 10 min read · International Travel"
                    href="{{ route('guides.show', 'airline-pet-policies') }}" badge="Popular" />
                <x-guides.featured-card-row icon="content_cut" title="Home Grooming Basics: A Step-by-Step Dog Guide"
                    meta="Grooming · 6 min read · Beginner-friendly"
                    href="{{ route('guides.show', 'home-grooming-basics') }}" />
                <x-guides.featured-card-row icon="health_and_safety"
                    title="Signs Your Dog Needs Immediate Vet Attention" meta="Vet Care · 5 min read · Health"
                    href="{{ route('guides.show', 'vet-attention-signs') }}" />
            </div>

            <div class="mt-8 text-center sm:hidden">
                <a href="{{ route('guides.index') }}"
                    class="inline-flex items-center gap-2 border border-primary text-primary px-7 py-3 rounded-full font-semibold text-sm hover:bg-primary hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                    Browse all guides <span class="material-symbols-outlined text-base">arrow_forward</span>
                </a>
            </div>

        </div>
    </section>

    @push('head')
        @php
            echo \Spatie\SchemaOrg\Schema::webSite()
                ->name('Waggies')
                ->description("Abuja's most trusted luxury pet boarding, grooming, vet care and relocation specialists.")
                ->url(url('/'))
                ->toScript();
        @endphp
    @endpush
</x-layouts.app>