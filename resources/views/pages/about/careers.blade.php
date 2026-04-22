<x-layouts.app title="Careers at Waggies" nav-section="about">

    <x-hero.image
        image-src="/images/careers.jpg"
        eyebrow="Join Our Team"
        title='Work With Animals<br/>You Love Every Day'
        subtitle="We're always looking for passionate, qualified people to join the Waggies family. If you love animals and care about doing things properly, we'd love to hear from you."
        :primary-cta="['label' => 'See Open Roles', 'href' => '#open-roles']"
        :secondary-cta="['label' => 'About Waggies', 'href' => route('about.index')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="Why Work at Waggies?"
                title="A Place Where<br/>Passion Meets Purpose"
                subtitle="We offer a supportive environment, continuous training, and the satisfaction of making a real difference to animals every single day."
            />
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mt-10">
                @foreach([
                    ['icon' => 'school',       'title' => 'Paid Training',       'desc' => 'Fully funded professional development and certification for all team members.'],
                    ['icon' => 'favorite',     'title' => 'Do What You Love',    'desc' => 'Spend your working day doing something that genuinely matters to you.'],
                    ['icon' => 'groups',       'title' => 'Great Team Culture',  'desc' => 'A tight-knit, supportive team that looks out for each other.'],
                    ['icon' => 'trending_up',  'title' => 'Room to Grow',        'desc' => 'We promote from within and invest in the long-term careers of our staff.'],
                ] as $item)
                <div class="bg-surface-purple rounded-2xl p-6 flex flex-col gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary icon-filled">{{ $item['icon'] }}</span>
                    </div>
                    <h3 class="font-semibold text-primary-dark">{{ $item['title'] }}</h3>
                    <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="open-roles" class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Open Roles" title="Current Vacancies" />
            <div class="mt-10 flex flex-col gap-4">
                @foreach([
                    ['role' => 'Pet Care Attendant',     'type' => 'Full-time', 'dept' => 'Boarding'],
                    ['role' => 'Certified Pet Groomer',  'type' => 'Full-time', 'dept' => 'Grooming Spa'],
                    ['role' => 'Veterinary Assistant',   'type' => 'Full-time', 'dept' => 'Vet Care'],
                    ['role' => 'Dog Trainer',            'type' => 'Full-time', 'dept' => 'Training'],
                    ['role' => 'Pet Transport Driver',   'type' => 'Part-time', 'dept' => 'Transport'],
                ] as $job)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-surface-purple rounded-2xl px-6 py-5">
                    <div>
                        <h3 class="font-semibold text-primary-dark">{{ $job['role'] }}</h3>
                        <p class="text-sm text-primary-dark/50">{{ $job['dept'] }} · {{ $job['type'] }}</p>
                    </div>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 border-2 border-primary text-primary px-5 py-2.5 rounded-full text-sm font-semibold hover:bg-primary hover:text-white transition shrink-0">
                        Apply Now <span class="material-symbols-outlined text-base">arrow_forward</span>
                    </a>
                </div>
                @endforeach
            </div>
            <p class="mt-8 text-sm text-primary-dark/50">Don't see a role that fits? Send us a speculative application — we love meeting passionate people.</p>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Careers at Waggies — Join Our Pet Care Team in Abuja')
    ->description('Join the Waggies team in Abuja. We are hiring passionate pet care professionals — view current vacancies and apply today.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
