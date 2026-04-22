<x-layouts.app title="Dog Boarding" nav-section="services">

    <x-hero.image
        image-src="/images/boarding-dogs.jpg"
        eyebrow="Dog Boarding · Abuja"
        title='Premium Dog Boarding<br/>in the Heart of Abuja'
        subtitle="Private suites, outdoor play areas, socialisation sessions, and 24/7 supervision — your dog will love it here."
        :primary-cta="['label' => 'Book a Stay', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Dog Boarding Includes"
                title="Everything Your Dog<br/>Needs to Thrive"
                subtitle="From arrival to pickup, every detail is taken care of so your dog is comfortable, stimulated, and loved."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'hotel',          'title' => 'Private Suites',       'desc' => 'Spacious, climate-controlled rooms with orthopedic bedding and personal enrichment toys.'],
                    ['icon' => 'directions_run', 'title' => 'Daily Exercise',       'desc' => 'Structured outdoor play and exercise sessions matched to your dog\'s energy level.'],
                    ['icon' => 'groups',         'title' => 'Socialisation',        'desc' => 'Carefully supervised group play for social dogs — separated by size and temperament.'],
                    ['icon' => 'restaurant',     'title' => 'Tailored Feeding',     'desc' => 'Meals served on your dog\'s usual schedule using your preferred food or our premium options.'],
                    ['icon' => 'medical_services','title' => 'Daily Vet Check',     'desc' => 'Our on-site vet reviews all boarding dogs daily for peace of mind.'],
                    ['icon' => 'notifications',  'title' => 'Photo Updates',        'desc' => 'A photo report sent to you every day so you can see how your dog is doing.'],
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
                <h2 class="font-serif text-2xl font-bold text-primary-dark mb-1">Ready to book a stay?</h2>
                <p class="text-primary-dark/60">Contact us to check availability and reserve your dog's suite.</p>
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
            <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => e($f->answer)])->all()" />
            <p class="mt-6 text-sm text-primary-dark/50">More questions? <a href="{{ route('faq') }}" class="text-primary font-medium hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="text-primary font-medium hover:underline">contact us</a>.</p>
        </div>
    </section>
@endif

@push('head')
@php
echo \Spatie\SchemaOrg\Schema::service()
    ->name('Dog Boarding Abuja — Waggies')
    ->description('Premium dog boarding in Abuja with private suites, outdoor play areas, socialisation sessions, and 24/7 supervision.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
