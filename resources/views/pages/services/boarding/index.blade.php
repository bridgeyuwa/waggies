@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Boarding', 'route' => 'services.boarding']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.cover-hero :hero="$hero" />

    <section class="bg-white py-20">
        <div class="page-container">
            <x-waggies.section-heading eyebrow="BOARDING FOR DOGS & CATS" title="The core boarding service" subtitle="Boarding is priced per pet per night. Every boarded pet receives an individual enclosure, while the practical details are confirmed with staff before the stay." />
            <div class="mt-10 grid gap-6 md:grid-cols-2">
                <a href="{{ route('services.boarding.species', ['species' => 'dogs']) }}" class="group rounded-2xl border border-primary/10 bg-surface p-6 transition hover:border-primary/40 hover:shadow-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <div class="flex items-center gap-4"><span class="flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary"><x-waggies.icon name="dog" size="24" /></span><h3 class="font-serif text-2xl font-bold text-primary-dark">Dog boarding</h3></div>
                    <p class="mt-4 text-sm leading-relaxed text-primary-dark/65">Choose the closest operational size guidance — small, medium, large, or manual review — without entering a weight.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary">View dog boarding <x-waggies.icon name="arrow-forward" size="16" /></span>
                </a>
                <a href="{{ route('services.boarding.species', ['species' => 'cats']) }}" class="group rounded-2xl border border-primary/10 bg-surface p-6 transition hover:border-primary/40 hover:shadow-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                    <div class="flex items-center gap-4"><span class="flex size-12 items-center justify-center rounded-2xl bg-primary/10 text-primary"><x-waggies.icon name="cat" size="24" /></span><h3 class="font-serif text-2xl font-bold text-primary-dark">Cat boarding</h3></div>
                    <p class="mt-4 text-sm leading-relaxed text-primary-dark/65">Cats use one nightly boarding request path. Staff confirms the applicable rate during review.</p>
                    <span class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-primary">View cat boarding <x-waggies.icon name="arrow-forward" size="16" /></span>
                </a>
            </div>
        </div>
    </section>

    <section class="border-t border-primary/5 bg-surface py-20">
        <div class="page-container grid gap-12 lg:grid-cols-2">
            <div>
                <p class="text-eyebrow">INCLUDED IN CORE BOARDING</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">The essentials are clear.</h2>
                <ul class="mt-6 grid gap-3">
                    @foreach($inclusions as $inclusion)
                        <li class="flex items-start gap-3 rounded-xl border border-primary/10 bg-white p-4 text-sm text-primary-dark/80"><x-waggies.icon name="check-circle" variant="filled" size="18" class="mt-0.5 shrink-0 text-primary" />{{ $inclusion }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="text-eyebrow">PLEASE NOTE</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Some needs need a separate review.</h2>
                <ul class="mt-6 grid gap-3">
                    @foreach($notes as $note)
                        <li class="rounded-xl border border-primary/10 bg-white p-4 text-sm leading-relaxed text-primary-dark/75">{{ $note }}</li>
                    @endforeach
                </ul>
                <p class="mt-5 text-sm leading-relaxed text-primary-dark/65">Daily photos, scheduled owner updates, outdoor walks, structured play, enrichment programmes, daily veterinary checks, and 24/7 supervision are not standard boarding promises.</p>
            </div>
        </div>
    </section>

    <section class="border-t border-primary/5 bg-white py-16">
        <div class="page-container flex flex-col items-start justify-between gap-5 md:flex-row md:items-center">
            <div><h2 class="font-serif text-2xl font-bold text-primary-dark">Need boarding?</h2><p class="mt-2 text-sm text-primary-dark/65">Send your dates, pet details, feeding instructions, and any care considerations.</p></div>
            <x-waggies.button href="{{ route('book', ['service' => 'boarding']) }}">Submit Boarding Request <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
        </div>
    </section>

    @if($faqs !== [])
        <section class="bg-surface py-16"><div class="page-container"><x-waggies.section-heading title="Boarding questions" spacing="mb-8" /><x-waggies.faq-accordion :faqs="$faqs" /></div></section>
    @endif
@endsection
