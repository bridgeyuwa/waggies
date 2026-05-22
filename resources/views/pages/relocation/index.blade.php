<x-layouts.app title="Pet Relocation" nav-section="relocation">

    <x-hero.image
        image-src="/images/relocation.jpg"
        eyebrow="International Pet Relocation"
        title='Stress-Free International<br/>Pet Relocation'
        subtitle="Full-service import and export of pets — from health certificates and microchipping to airline bookings and customs clearance."
        :primary-cta="['label' => 'Get a Quote', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Checklist', 'href' => route('relocation.checklist')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Our Relocation Services"
                title="Whether You're Moving In<br/>or Moving Out"
                subtitle="We handle every stage of the relocation process so you can focus on your move, not the paperwork."
            />
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <x-card.service title="Import to Nigeria"  description="Bringing your pet to Abuja? We manage all import permits, health checks, and airport collection."  href="{{ route('relocation.import') }}"    image-src="/images/relocation-import.jpg"  icon="flight_land" />
                <x-card.service title="Export from Nigeria" description="Moving abroad? We handle export permits, airline-approved crates, and international documentation." href="{{ route('relocation.export') }}"    image-src="/images/relocation-export.jpg" icon="flight_takeoff" />
                <x-card.service title="Relocation Checklist" description="Use our comprehensive checklist to stay on top of every requirement for a smooth relocation."    href="{{ route('relocation.checklist') }}" image-src="/images/relocation-checklist.jpg" icon="checklist" />
            </div>
        </div>
    </section>

    <x-feature-band
        eyebrow="Why Choose Waggies Relocation?"
        title='Every Document,<br/><span class="text-secondary italic">Every Step</span>'
        subtitle="International pet moves are complex. We've done hundreds of them — let us handle the details."
        :cta="['label' => 'Get a Quote', 'href' => route('contact')]"
        :features="[
            ['icon' => 'description',      'title' => 'Full Documentation',      'description' => 'Health certs, import/export permits, microchip records — all handled for you.'],
            ['icon' => 'flight',           'title' => 'Airline Coordination',    'description' => 'We liaise directly with airlines to book the right cargo or cabin option.'],
            ['icon' => 'verified',         'title' => 'Vet-Signed Health Certs', 'description' => 'Our on-site vet completes all required health examinations and paperwork.'],
            ['icon' => 'support_agent',    'title' => 'Dedicated Coordinator',   'description' => 'A single point of contact who manages your pet\'s move from start to finish.'],
            ['icon' => 'schedule',         'title' => 'Timeline Management',     'description' => 'We plan against your travel date to ensure all requirements are met on time.'],
        ]"
    />

    <section class="py-20 bg-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Client Experiences" title="Smooth Moves, Happy Pets" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <x-card.testimonial stars="5" quote="Waggies handled every single document for our move to the UK. Completely seamless — zero stress on our part." author-initial="F" author-name="Fatima M." author-subtitle="Relocation client · Asokoro" />
                <x-card.testimonial stars="5" quote="We imported two dogs from the US. The team knew exactly what was needed and kept us updated throughout." author-initial="K" author-name="Kunle A." author-subtitle="Import client · Maitama" />
                <x-card.testimonial stars="5" quote="Professional, knowledgeable, and genuinely caring. Our cat arrived safely and we couldn't be happier." author-initial="A" author-name="Amaka O." author-subtitle="Export client · Wuse II" />
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
    ->name('International Pet Relocation Abuja — Waggies')
    ->description('Full-service pet import and export from Abuja — health certificates, import permits, airline coordination, and customs clearance.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
