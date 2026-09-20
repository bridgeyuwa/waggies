@extends('layouts.app', ['headStatus' => 404])

@section('content')
    @php
        $popularPages = [
            ['href' => route('home'), 'label' => 'Home', 'icon' => 'pets'],
            ['href' => route('services.index'), 'label' => 'Services', 'icon' => 'grooming'],
            ['href' => route('about'), 'label' => 'About', 'icon' => 'info'],
            ['href' => route('contact'), 'label' => 'Contact', 'icon' => 'email'],
            ['href' => route('faq'), 'label' => 'FAQ', 'icon' => 'help'],
            ['href' => route('contact', ['intent' => 'booking']), 'label' => 'Book Appointment', 'icon' => 'calendar'],
        ];
        $quickServiceLinks = [
            ['href' => route('services.grooming'), 'label' => 'Grooming'],
            ['href' => route('services.training'), 'label' => 'Training'],
            ['href' => route('services.vet-care'), 'label' => 'Vet Care'],
            ['href' => route('relocation.transport'), 'label' => 'Transport'],
        ];
    @endphp

    <section class="relative isolate flex min-h-[80vh] flex-col items-center justify-center overflow-hidden border-t border-primary/10 bg-surface px-4 py-16 sm:py-24">
        <div class="pointer-events-none absolute inset-0 flex select-none items-center justify-center overflow-hidden" aria-hidden="true">
            <span class="font-serif text-[12rem] font-bold text-primary/10 sm:text-[16rem]">404</span>
        </div>

        <a href="{{ route('home') }}" class="relative z-10 mb-8 inline-flex items-center gap-2 rounded-full font-serif text-2xl font-bold text-primary transition-opacity hover:opacity-80 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2" aria-label="Return to Waggies homepage">
            <x-waggies.icon name="pets" size="30" variant="outlined" />
            Waggies
        </a>

        <div class="relative z-10 max-w-2xl text-center">
            <h1 class="mb-4 font-serif text-4xl text-primary-dark sm:text-5xl">Page Not Found</h1>
            <p class="text-lg leading-relaxed text-primary-dark/70">The page you&rsquo;re looking for doesn&rsquo;t exist or has been moved. Start with a search, or choose a familiar Waggies destination below.</p>
            <span class="sr-only">We couldn’t find that page.</span>
        </div>

        <button type="button" x-data @click="$dispatch('waggies:open-search')" aria-label="Search the site" class="group relative z-10 mx-auto mt-8 flex w-full max-w-lg items-center gap-3 rounded-2xl border-2 border-primary/20 bg-white px-6 py-4 text-lg transition hover:bg-surface-purple/30 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:ring-offset-2">
            <x-waggies.icon name="search" size="24" class="text-primary transition-transform group-hover:scale-110" />
            <span class="flex-1 text-left text-primary-dark/60">Search for services, articles, products&hellip;</span>
            <kbd class="hidden items-center gap-0.5 rounded-md border border-primary/10 bg-surface-purple px-2 py-0.5 text-xs font-medium uppercase tracking-wide text-primary-dark/50 sm:inline-flex" aria-hidden="true">⌘K</kbd>
        </button>

        <div class="relative z-10 mt-12 w-full max-w-3xl">
            <h2 class="mb-6 text-center text-sm font-semibold uppercase tracking-wider text-primary-dark/50">Find your way around</h2>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4">
                @foreach($popularPages as $page)
                    <a href="{{ $page['href'] }}" class="group flex min-h-24 flex-col items-center justify-center gap-2 rounded-2xl border border-primary/10 bg-white p-4 text-primary-dark transition-[background-color,border-color,box-shadow,transform] duration-[180ms] hover:-translate-y-0.5 hover:border-primary/25 hover:bg-surface-purple hover:shadow-soft focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <x-waggies.icon name="{{ $page['icon'] }}" size="24" class="text-primary" />
                        <span class="text-sm font-medium">{{ $page['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="relative z-10 mt-10 text-center">
            <span class="mr-2 text-sm text-primary-dark/60">Explore a service:</span>
            <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
                @foreach($quickServiceLinks as $service)
                    <a href="{{ $service['href'] }}" class="inline-flex min-h-11 items-center gap-1 rounded-full border border-primary/15 bg-white px-3 text-sm text-primary transition-[background-color,border-color,color] duration-[180ms] hover:border-primary hover:bg-primary hover:text-white focus:outline-none focus:ring-2 focus:ring-primary/50">
                        <x-waggies.icon name="arrow-forward" size="14" />
                        {{ $service['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="relative z-10 mt-12">
            <x-waggies.button href="{{ route('contact') }}">
                <x-waggies.icon name="contact" size="21" />
                Need help? Contact Us
            </x-waggies.button>
        </div>
    </section>
@endsection
