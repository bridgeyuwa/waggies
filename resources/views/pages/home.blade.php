<x-layouts.app title="Luxury Pet Care, Abuja" nav-section="home">
    <x-hero.image image-src="https://images.unsplash.com/photo-1650454027983-e2b8fe55b30b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGx1eHVyeSUyMGRvZyUyMGJvYXJkaW5nJTIwZmFjaWxpdHklMjBpbmRvb3IlMjBtb2Rlcm4lMjBjbGVhbiUyMGRvZyUyMHN1aXRlfGVufDB8fDB8fHww" eyebrow="Abuja's #1 Pet Services"
        title='Premium pet services in the heart of Abuja'
        subtitle="From international relocation to 5-star boarding suites. We provide world-class veterinary and grooming services tailored for the discerning pet owner."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('services.boarding.index')]"
        :secondary-cta="['label' => 'View Services', 'href' => route('services.index')]" />


    <x-hero.gradient rating="4.3" imageSrc="https://images.unsplash.com/photo-1650454027983-e2b8fe55b30b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTR8fGx1eHVyeSUyMGRvZyUyMGJvYXJkaW5nJTIwZmFjaWxpdHklMjBpbmRvb3IlMjBtb2Rlcm4lMjBjbGVhbiUyMGRvZyUyMHN1aXRlfGVufDB8fDB8fHww" eyebrow="Experience the Waggies Difference"
        title='Where Luxury Meets Compassionate Care'
        subtitle="Our state-of-the-art facility in Abuja offers a unique blend of opulence and expert care, ensuring your pet's comfort and well-being at every visit."
        :primary-cta="['label' => 'Take a Virtual Tour', 'href' => route('contact')]"
        :secondary-cta="['label' => 'Meet Our Team', 'href' => route('contact')]" />

       {{-- Services grid --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="What We Offer" title='World-Class Care<br/>for Every Pet'
                subtitle="From luxury overnight boarding to international relocation — all under one roof." />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <x-card.service title="Luxury Boarding"
                    description="Premium overnight stays in spacious, climate-controlled suites with 24/7 care."
                    href="{{ route('services.boarding.index') }}" image-src="https://lh3.googleusercontent.com/aida-public/AB6AXuA-hFLGUTZfR4o6aEmNZblS2r51j254QMcrSmpvNX3gqf_PRrK4s4gMZDlNMovWT6w3T_GpetAuHCroSsYBK9jakgwd2tcq-ncK3OC3Fa6axI61kTa1IUJICwA8mYBa_rhA2Dh3TV4uIXJb1O0Iw2sQ6qnHppigFgxpAQsDbWg5tzx-r9sNF_2M5pObOb8oTnakgOtF80OU4vK6ClwuKgdFGRmhd6aGpTjFzvD15SopA7DUGZ8JNDxSSDPkN9SlBhE41EDqGmI6Axfm" icon="apartment" />
                <x-card.service title="Grooming Spa"
                    description="Breed-specific treatments, luxury baths, and styling by certified pet groomers."
                    href="{{ route('services.grooming') }}" image-src="https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?w=600&h=400&fit=crop&q=80" icon="spa" />
                <x-card.service title="Vet Care"
                    description="On-site veterinary consultations, vaccinations, and wellness check-ups."
                    href="{{ route('services.vet-care') }}" image-src="https://lh3.googleusercontent.com/aida-public/AB6AXuDTHx-2eTZ7bwYucOWHLo7yqtzUxvxjRdAb4ZpHiWevyYa1KvptQ3f-zghgc_4vKzVAfs5vVlPV2rgs7kI2FCajkWDLoMdjPapYSNyqkdN0kZsErZv9eix2rZBmjWcDSPL6cGjQ_-2weGgk7JKU6Xd6gpDTPlWDn49qUugijUJdlSwBRKd42KKg_9KKij4oO59SWhw0jGlM3Ehw32RTSVkI9bQtSRXzJ1qVFkmRnmqBkOnCjPsogYNb93pTrK6HhXijRWCk7Y3EebHF" icon="medical_services" />
                <x-card.service title="Dog Training"
                    description="Positive-reinforcement training programmes for puppies and adult dogs."
                    href="{{ route('services.training') }}" image-src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmd8xXxW_oQhjGyRngta_b_G0TdAztFfgHUHcn5btMzOIh3q3eQk9tbqth3qPo9CWbuMYp8WpoWcpQM_5jT7uiC9VRtOxUAeYt6LloE-jFSki2BUrXQqQiGy3nTIVvjhj66UOsmWcxUq5Lz30b-5vlokWvpK0FUB897pMCrrNFKH7Tmc9VExw20xfsBZd8dgqysHE0Ig3d3NLp0P9ZGwWT0ZoVW-g6E7l50ZAoj9EAGz6RpKd8IvyX6y5dQBM0AGt2upSp4a0vHE7J" icon="school" />
                <x-card.service title="Pet Relocation"
                    description="Stress-free international pet moves with full documentation support."
                    href="{{ route('relocation.index') }}" image-src="https://lh3.googleusercontent.com/aida-public/AB6AXuD8zMeLP6ESzVNlEiUIMeqNo39rxSo0QWsdUUPiub856XZD95tgaC9TVYlM5InAGjQF5PBkOieuBiJ2FYOjI8Y7mAz3_5bVSzQ9Q-W4nKTuqrpH_e_V0gh2mznDCDB66JIruUaeX7g4-pF9lQUw9xAqL-ijY2fdaAaAeW_nJ93dvvjroQShmAIefL-FlJGH_2f4x7Tiy67wVdeChUVSQ4xOdWr9ezWlY3DB_Q0spNNoCnO-FbpB9o3e-aMrSGk1wci_fRiv-MzgZAZd" icon="flight_takeoff" />
                <x-card.service title="Local Transport"
                    description="Door-to-door pickup and drop-off across Abuja, in air-conditioned vehicles."
                    href="{{ route('services.transport') }}" image-src="/images/transport.jpg" icon="local_shipping" />
            </div>
        </div>
    </section>

    {{-- Feature band --}}
    <x-feature-band eyebrow="The Waggies Difference"
        title='Trusted Care<br/><span class="text-secondary italic">Standards</span>'
        subtitle="Certified standards across all services."
        :cta="['label' => 'Book a Free Consultation', 'href' => route('contact')]" :features="[
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

    {{-- Shop Teaser Widget (Correct Pattern) --}}
    <section id="shop-section" class="bg-surface py-20">
<div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
          <!-- Section heading + View All -->
          <div class="flex items-end justify-between mb-8">
            <div>
              <span class="text-primary font-bold tracking-widest uppercase text-xs mb-2 block">Waggies Shop</span>
              <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight">Shop for Your Pet</h2>
              <p class="text-primary-dark/50 mt-2 text-sm max-w-md">Curated products our vets and groomers actually use and recommend.</p>
            </div>
            <a href="#" class="hidden sm:inline-flex items-center gap-1.5 text-primary font-semibold text-sm uppercase tracking-wide hover:text-primary-light transition-colors shrink-0 ml-6">
              Visit shop <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
          </div>

          <!-- Category filter pills -->
          <div class="flex gap-2 mb-8 overflow-x-auto pb-1">
            <button class="shrink-0 px-5 py-2 rounded-full bg-primary text-white text-xs font-bold uppercase tracking-wider transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60">All</button>
            <button class="shrink-0 px-5 py-2 rounded-full border border-primary/25 text-primary-dark/70 text-xs font-semibold uppercase tracking-wider hover:border-primary hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60">Food &amp; Treats</button>
            <button class="shrink-0 px-5 py-2 rounded-full border border-primary/25 text-primary-dark/70 text-xs font-semibold uppercase tracking-wider hover:border-primary hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60">Grooming</button>
            <button class="shrink-0 px-5 py-2 rounded-full border border-primary/25 text-primary-dark/70 text-xs font-semibold uppercase tracking-wider hover:border-primary hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60">Accessories</button>
            <button class="shrink-0 px-5 py-2 rounded-full border border-primary/25 text-primary-dark/70 text-xs font-semibold uppercase tracking-wider hover:border-primary hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60">Health &amp; Vet</button>
          </div>

          <!-- Product grid -->
          <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <!-- Product 1 — Bestseller -->
            <div class="group bg-white border border-surface-purple rounded-2xl overflow-hidden card-lift cursor-pointer flex flex-col relative">
              <!-- Badge -->
              <span class="absolute top-3 left-3 z-10 bg-primary text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider shadow-glow">Bestseller</span>
              <!-- Wishlist -->
              <button class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-surface-purple flex items-center justify-center text-primary-dark/40 hover:text-primary hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60" aria-label="Add to wishlist">
                <span class="material-symbols-outlined text-sm">favorite</span>
              </button>
              <!-- Image -->
              <div class="img-zoom h-44 bg-surface-purple relative">
                <div class="bg-img h-full w-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1535591273668-578e31182c4f?w=400&amp;h=350&amp;fit=crop&amp;q=80')"></div>
              </div>
              <div class="p-4 flex flex-col flex-1 gap-3">
                <div>
                  <p class="text-[10px] font-bold text-primary/80 uppercase tracking-wider mb-1">Food &amp; Treats</p>
                  <h3 class="font-semibold text-primary-dark text-sm leading-snug line-clamp-2">Royal Canin Maxi Adult Dog Food 15kg</h3>
                </div>
                <div class="flex items-center gap-1.5 mt-auto">
                  <span class="material-symbols-outlined icon-filled text-gold text-sm">star</span>
                  <span class="text-xs font-bold text-primary-dark">4.8</span>
                  <span class="text-xs text-primary-dark/40">(124)</span>
                </div>
                <div class="flex items-center justify-between">
                  <p class="font-serif font-bold text-primary-dark text-lg">₦28,500</p>
                </div>
                <button class="w-full bg-primary hover:bg-primary-dark text-white py-2.5 rounded-full font-semibold text-xs transition shadow-glow hover:-translate-y-0.5 flex items-center justify-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-1">
                  <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Add to Cart
                </button>
              </div>
            </div>

            <!-- Product 2 — Sale -->
            <div class="group bg-white border border-surface-purple rounded-2xl overflow-hidden card-lift cursor-pointer flex flex-col relative">
              <span class="absolute top-3 left-3 z-10 bg-error text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">Sale</span>
              <button class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-surface-purple flex items-center justify-center text-primary hover:text-error transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60" aria-label="Added to wishlist">
                <span class="material-symbols-outlined icon-filled text-sm">favorite</span>
              </button>
              <div class="img-zoom h-44 bg-surface-purple relative">
                <div class="bg-img h-full w-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1601758228041-f3b2795255f1?w=400&amp;h=350&amp;fit=crop&amp;q=80')"></div>
              </div>
              <div class="p-4 flex flex-col flex-1 gap-3">
                <div>
                  <p class="text-[10px] font-bold text-primary/80 uppercase tracking-wider mb-1">Grooming</p>
                  <h3 class="font-semibold text-primary-dark text-sm leading-snug line-clamp-2">Professional Deshedding Brush — All Breeds</h3>
                </div>
                <div class="flex items-center gap-1.5 mt-auto">
                  <span class="material-symbols-outlined icon-filled text-gold text-sm">star</span>
                  <span class="text-xs font-bold text-primary-dark">4.6</span>
                  <span class="text-xs text-primary-dark/40">(89)</span>
                </div>
                <div class="flex items-center gap-2">
                  <p class="font-serif font-bold text-primary-dark text-lg">₦6,400</p>
                  <p class="text-sm text-primary-dark/35 line-through font-medium">₦8,500</p>
                </div>
                <button class="w-full bg-primary hover:bg-primary-dark text-white py-2.5 rounded-full font-semibold text-xs transition shadow-glow hover:-translate-y-0.5 flex items-center justify-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-1">
                  <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Add to Cart
                </button>
              </div>
            </div>

            <!-- Product 3 — New -->
            <div class="group bg-white border border-surface-purple rounded-2xl overflow-hidden card-lift cursor-pointer flex flex-col relative">
              <span class="absolute top-3 left-3 z-10 bg-success text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">New</span>
              <button class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-surface-purple flex items-center justify-center text-primary-dark/40 hover:text-primary hover:border-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60" aria-label="Add to wishlist">
                <span class="material-symbols-outlined text-sm">favorite</span>
              </button>
              <div class="img-zoom h-44 bg-surface-purple relative">
                <div class="bg-img h-full w-full bg-cover bg-center" style="background-image: url('https://media.istockphoto.com/id/174843968/photo/a-brown-leather-dog-collar-with-metal-accents.webp?a=1&b=1&s=612x612&w=0&k=20&c=wzpxsd9BdjND9mLiuUe1J_0D2qZxEtNPURKORpYZ_4Q=')"></div>
              </div>
              <div class="p-4 flex flex-col flex-1 gap-3">
                <div>
                  <p class="text-[10px] font-bold text-primary/80 uppercase tracking-wider mb-1">Accessories</p>
                  <h3 class="font-semibold text-primary-dark text-sm leading-snug line-clamp-2">Waggies Signature Leather Pet Collar</h3>
                </div>
                <div class="flex items-center gap-1.5 mt-auto">
                  <span class="material-symbols-outlined icon-filled text-gold text-sm">star</span>
                  <span class="text-xs font-bold text-primary-dark">5.0</span>
                  <span class="text-xs text-primary-dark/40">(17)</span>
                </div>
                <div class="flex items-center justify-between">
                  <p class="font-serif font-bold text-primary-dark text-lg">₦12,000</p>
                </div>
                <button class="w-full bg-primary hover:bg-primary-dark text-white py-2.5 rounded-full font-semibold text-xs transition shadow-glow hover:-translate-y-0.5 flex items-center justify-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-1">
                  <span class="material-symbols-outlined text-sm">add_shopping_cart</span>Add to Cart
                </button>
              </div>
            </div>

            <!-- Product 4 — Out of stock state -->
            <div class="group bg-white border border-surface-purple rounded-2xl overflow-hidden cursor-not-allowed flex flex-col relative opacity-70">
              <button class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-white/90 backdrop-blur border border-surface-purple flex items-center justify-center text-primary-dark/40 cursor-not-allowed" disabled="" aria-label="Add to wishlist">
                <span class="material-symbols-outlined text-sm">favorite</span>
              </button>
              <div class="h-44 bg-surface-purple/60 relative">
                <div class="h-full w-full bg-cover bg-center grayscale" style="background-image: url('https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=400&amp;h=350&amp;fit=crop&amp;q=80')"></div>
                <!-- Out of stock overlay -->
                <div class="absolute inset-0 bg-white/50 flex items-center justify-center">
                  <span class="bg-primary-dark/80 text-white text-[10px] font-bold px-3 py-1.5 rounded-full uppercase tracking-widest">Out of Stock</span>
                </div>
              </div>
              <div class="p-4 flex flex-col flex-1 gap-3">
                <div>
                  <p class="text-[10px] font-bold text-primary/80 uppercase tracking-wider mb-1">Health &amp; Vet</p>
                  <h3 class="font-semibold text-primary-dark text-sm leading-snug line-clamp-2">Tick &amp; Flea Prevention Drops — 3 Month</h3>
                </div>
                <div class="flex items-center gap-1.5 mt-auto">
                  <span class="material-symbols-outlined icon-filled text-gold text-sm">star</span>
                  <span class="text-xs font-bold text-primary-dark">4.9</span>
                  <span class="text-xs text-primary-dark/40">(203)</span>
                </div>
                <div class="flex items-center justify-between">
                  <p class="font-serif font-bold text-primary-dark/40 text-lg">₦9,800</p>
                </div>
                <button disabled="" class="w-full border border-primary-dark/20 text-primary-dark/40 py-2.5 rounded-full font-semibold text-xs flex items-center justify-center gap-1.5 cursor-not-allowed">
                  <span class="material-symbols-outlined text-sm">notifications</span>Notify Me
                </button>
              </div>
            </div>

          </div><!-- /product grid -->

          <!-- Mobile "Visit Shop" -->
          <div class="mt-8 text-center sm:hidden">
            <a href="#" class="inline-flex items-center gap-2 border border-primary text-primary px-7 py-3 rounded-full font-semibold text-sm hover:bg-primary hover:text-white transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
              Visit shop <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>
          </div>

        </div>
      </section>


        {{-- Blog Section --}}
    <x-blog-section label="From the Blog" heading="Pet Care Tips & News"
        subheading="Expert advice, heartwarming stories, and the latest from Waggies HQ." view-all-url="#">

    </x-blog-section>

   <section class="w-full py-20">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-cta.primary :badge-text="'Trusted by 2,000+ Pet Owners'" :heading="'Give Your Pet the'"
                :heading-accent="'Care They Deserve'" :body="'Book a consultation today. Our team is ready to create a personalised care plan for your furry family member.'"
                :primary-cta="['label' => 'Book a Free Consultation', 'href' => '#']"
                :secondary-cta="['label' => 'Call Us Now', 'href' => '#', 'icon' => 'call']" 
            />
    
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