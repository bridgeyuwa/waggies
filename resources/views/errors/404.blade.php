@extends('layouts.app', ['headStatus' => 404, 'compactFooter' => true, 'hideFloatingActions' => true])

@section('content')
    <section class="relative isolate overflow-hidden border-t border-primary/10 bg-surface" aria-labelledby="not-found-heading">
        <div class="page-container flex min-h-[calc(100dvh-4.5rem)] items-center py-14 sm:py-20 lg:py-24">
            <div class="w-full max-w-3xl">
                <p class="font-serif text-[clamp(5rem,13vw,8rem)] font-bold leading-none tracking-[-0.03em] text-primary">404</p>

                <h1 id="not-found-heading" class="text-h1 mt-4">Page not found</h1>

                <p class="text-lead mt-5 max-w-xl">We couldn&rsquo;t find the page you&rsquo;re looking for. It may have moved or the link may be out of date.</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                    <x-waggies.button href="{{ route('home') }}">
                        Return to homepage
                        <x-waggies.icon name="arrow-forward" size="17" />
                    </x-waggies.button>

                    <button type="button" x-data @click="$dispatch('waggies:open-search')" class="w-cta w-cta--secondary">
                        <x-waggies.icon name="search" size="18" />
                        Search Waggies
                    </button>
                </div>

                <div class="mt-12 max-w-2xl border-t border-primary/10 pt-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between sm:gap-6">
                        <p class="text-sm text-primary-dark/60">Looking for a particular service?</p>

                        <nav class="flex flex-wrap items-center gap-x-6 gap-y-2" aria-label="More Waggies links">
                            <a href="{{ route('services.index') }}" class="inline-flex min-h-11 items-center gap-1.5 rounded-sm text-sm font-semibold text-primary underline-offset-4 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:ring-offset-2">
                                Browse services
                                <x-waggies.icon name="arrow-forward" size="16" />
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex min-h-11 items-center gap-1.5 rounded-sm text-sm font-semibold text-primary underline-offset-4 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/60 focus-visible:ring-offset-2">
                                Contact Waggies
                                <x-waggies.icon name="arrow-forward" size="16" />
                            </a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
