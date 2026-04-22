<x-layouts.app title="Dog Training" nav-section="services">

    <x-hero.image
        image-src="/images/training.jpg"
        eyebrow="Dog Training · Abuja"
        title='Positive Training for<br/>a Better-Behaved Dog'
        subtitle="Science-based, positive-reinforcement training programmes for puppies and adult dogs — building confidence, manners, and a stronger bond."
        :primary-cta="['label' => 'Enquire About Training', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Pricing', 'href' => route('services.pricing')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Training Programmes"
                title="A Programme for<br/>Every Dog"
                subtitle="We offer structured training programmes to suit puppies, adolescent dogs, and adults at any stage of their learning."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach([
                    ['icon' => 'child_care',    'title' => 'Puppy Foundation',       'desc' => 'Essential early socialisation, toilet training, recall, and bite inhibition for puppies 8–16 weeks.'],
                    ['icon' => 'school',        'title' => 'Basic Obedience',         'desc' => 'Sit, stay, come, heel, and leash manners — the core commands every dog should know.'],
                    ['icon' => 'workspace_premium','title' => 'Advanced Obedience',  'desc' => 'Off-lead reliability, distance commands, and polished behaviour in real-world environments.'],
                    ['icon' => 'psychology',    'title' => 'Behaviour Modification',  'desc' => 'Tailored programmes for reactivity, anxiety, aggression, or other specific behavioural concerns.'],
                    ['icon' => 'groups',        'title' => 'Group Classes',           'desc' => 'Sociable small-group sessions to practise skills around other dogs and people.'],
                    ['icon' => 'person',        'title' => '1-to-1 Private Sessions', 'desc' => 'Focused one-on-one sessions with a trainer, tailored to your dog\'s specific needs and goals.'],
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

    <x-feature-band
        eyebrow="Our Approach"
        title='Positive Methods,<br/><span class="text-secondary italic">Lasting Results</span>'
        subtitle="We use only reward-based, force-free training methods that build trust and produce long-lasting behavioural change."
        :cta="['label' => 'Start Training', 'href' => route('contact')]"
        :features="[
            ['icon' => 'thumb_up',      'title' => 'Reward-Based Only',      'description' => 'We never use aversive tools or punishment-based techniques.'],
            ['icon' => 'verified',      'title' => 'Certified Trainers',     'description' => 'All trainers are formally certified in canine behaviour and training.'],
            ['icon' => 'favorite',      'title' => 'Bond-Building Focus',    'description' => 'Training that strengthens the relationship between you and your dog.'],
            ['icon' => 'trending_up',   'title' => 'Measurable Progress',    'description' => 'Clear goals set at the outset and tracked throughout the programme.'],
        ]"
    />


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
    ->name('Dog Training Abuja — Waggies')
    ->description('Science-based, positive-reinforcement dog training in Abuja for puppies and adults — obedience, behaviour modification, and group classes.')
    ->url(url()->current())
    ->provider(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->areaServed('Abuja, Nigeria')
    ->toScript();
@endphp
@endpush
</x-layouts.app>
