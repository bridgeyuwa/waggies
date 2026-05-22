<x-layouts.app title="Loyalty Programme" nav-section="loyalty">

    <x-breadcrumb.strip class="bg-white border-b border-primary/5" />

    <x-hero.gradient
        rating="4.9"
        review-count="500+"
        title='Rewards for Every<br/><span class="text-primary italic">Waggies Visit</span>'
        subtitle="Earn points every time you use Waggies. Redeem them for free services, discounts, and exclusive member perks."
        :primary-cta="['label' => 'Join for Free', 'href' => route('contact')]"
        :secondary-cta="['label' => 'View Services', 'href' => route('services.index')]"
    />

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading
                eyebrow="How It Works"
                title="Three Simple Steps<br/>to Earning Rewards"
                subtitle="Our loyalty programme is free to join and designed to reward you every time you choose Waggies."
            />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                @foreach([
                    ['step' => '01', 'icon' => 'person_add',  'title' => 'Sign Up Free',        'desc' => 'Register in minutes at reception or by contacting us. No fees, no catches.'],
                    ['step' => '02', 'icon' => 'star',         'title' => 'Earn Points',          'desc' => 'Earn points on every booking — boarding, grooming, vet visits, training, and transport.'],
                    ['step' => '03', 'icon' => 'redeem',       'title' => 'Redeem Rewards',       'desc' => 'Spend your points on free nights, free grooms, discounts, and members-only offers.'],
                ] as $item)
                <div class="bg-surface-purple rounded-2xl p-8 flex flex-col gap-4">
                    <div class="flex items-center gap-4">
                        <span class="font-serif text-4xl font-bold text-primary/20">{{ $item['step'] }}</span>
                        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary icon-filled">{{ $item['icon'] }}</span>
                        </div>
                    </div>
                    <h3 class="font-serif text-xl font-bold text-primary-dark">{{ $item['title'] }}</h3>
                    <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-20 bg-white border-t border-surface-purple">
        <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
            <x-section-heading eyebrow="Membership Tiers" title="The More You Visit,<br/>the More You Earn" />
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-10">
                @foreach([
                    ['tier' => 'Silver', 'icon' => 'emoji_events', 'color' => 'text-primary-dark/40', 'threshold' => 'From your first visit', 'perks' => ['1 point per ₦1,000 spent', '5% birthday discount', 'Priority booking access']],
                    ['tier' => 'Gold',   'icon' => 'emoji_events', 'color' => 'text-gold',             'threshold' => 'After 10 visits',        'perks' => ['1.5 points per ₦1,000 spent', '10% birthday discount', 'Free monthly nail trim', 'Early access to promotions']],
                    ['tier' => 'Platinum','icon' => 'emoji_events','color' => 'text-primary',          'threshold' => 'After 25 visits',        'perks' => ['2 points per ₦1,000 spent', '15% birthday discount', 'Free monthly groom', 'Dedicated account manager', 'Exclusive member events']],
                ] as $tier)
                <div class="bg-white rounded-2xl border border-surface-purple shadow-sm p-8 hover:shadow-soft transition-shadow">
                    <span class="material-symbols-outlined icon-filled text-4xl {{ $tier['color'] }} mb-3 block">{{ $tier['icon'] }}</span>
                    <h3 class="font-serif text-2xl font-bold text-primary-dark mb-1">{{ $tier['tier'] }}</h3>
                    <p class="text-xs text-primary-dark/40 uppercase tracking-widest mb-4">{{ $tier['threshold'] }}</p>
                    <ul class="flex flex-col gap-2">
                        @foreach($tier['perks'] as $perk)
                        <li class="flex items-center gap-2 text-sm text-primary-dark">
                            <span class="material-symbols-outlined icon-filled text-success text-base">check_circle</span>
                            {{ $perk }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-surface-purple">
        <div class="max-w-3xl mx-auto px-4 text-center">
            <h2 class="font-serif text-3xl font-bold text-primary-dark mb-3">Start earning today</h2>
            <p class="text-primary-dark/60 mb-6">Join the Waggies Loyalty Programme for free and start earning rewards from your very first visit.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                Join Now <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>
    </section>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Loyalty Programme — Waggies Pet Care')
    ->description('Earn points on every Waggies visit and redeem them for free services, discounts, and exclusive member perks.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.app>
