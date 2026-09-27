@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip class="border-b border-primary/5 bg-white" :items="[['label' => 'Services', 'route' => 'services.index']]" />
    <x-waggies.cover-hero :hero="$hero" />

    <section class="bg-white py-20">
        <div class="page-container">
            <x-waggies.section-heading eyebrow="WHAT WE OFFER" title="Three services, one careful review" subtitle="Waggies keeps the public catalogue focused on the care requests our team can review and coordinate." />
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach($cards as $card)
                    <x-waggies.service-card :card="$card" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-primary/5 bg-surface py-20">
        <div class="page-container grid gap-12 lg:grid-cols-2 lg:items-start">
            <div>
                <p class="text-eyebrow">HOW REQUESTS WORK</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark md:text-4xl">A request is the start of the conversation.</h2>
                <p class="mt-4 max-w-xl text-base leading-relaxed text-primary-dark/70">Submitting a request does not reserve a slot or confirm a price. Waggies reviews availability, pet details, service suitability, and any special requirements before sending the next steps.</p>
            </div>
            <ol class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                @foreach([
                    ['title' => 'Request received', 'body' => 'We acknowledge the details you send.'],
                    ['title' => 'Under review', 'body' => 'Staff check availability, suitability, and route or clinical requirements.'],
                    ['title' => 'Quote and confirmation', 'body' => 'We send the final price, payment instructions, and confirmation manually.'],
                ] as $step)
                    <li class="rounded-2xl border border-primary/10 bg-white p-5">
                        <h3 class="font-serif text-lg font-bold text-primary-dark">{{ $step['title'] }}</h3>
                        <p class="mt-2 text-sm leading-relaxed text-primary-dark/65">{{ $step['body'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <section class="bg-primary-dark py-16 text-white">
        <div class="page-container flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <p class="text-eyebrow text-secondary">READY WHEN YOU ARE</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-white">Submit a Booking Request</h2>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-white/70">Tell us what your pet needs and our team will review the request with you.</p>
            </div>
            <x-waggies.button href="{{ route('book') }}" class="shrink-0 bg-secondary! text-primary-dark!">Submit Booking Request <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
        </div>
    </section>

    @if($faqs !== [])
        <section class="bg-surface py-16">
            <div class="page-container">
                <x-waggies.section-heading title="Questions before you request?" spacing="mb-8" />
                <x-waggies.faq-accordion :faqs="$faqs" />
            </div>
        </section>
    @endif
@endsection
