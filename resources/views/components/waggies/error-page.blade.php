@props([
    'code',
    'title',
    'description',
    'primaryLabel' => 'Return to homepage',
    'primaryHref' => null,
    'secondaryLabel' => 'Contact Waggies',
    'secondaryHref' => null,
])

@php
    $primaryHref ??= route('home');
    $secondaryHref ??= route('contact');
@endphp

<section class="relative isolate overflow-hidden border-t border-primary/10 bg-surface" aria-labelledby="error-heading">
    <div class="page-container flex min-h-[calc(100dvh-4.5rem)] items-center py-14 sm:py-20 lg:py-24">
        <div class="w-full max-w-3xl">
            <p class="font-serif text-[clamp(5rem,13vw,8rem)] font-bold leading-none tracking-[-0.03em] text-primary">{{ $code }}</p>

            <h1 id="error-heading" class="text-h1 mt-4">{{ $title }}</h1>

            <p class="text-lead mt-5 max-w-xl">{{ $description }}</p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center">
                <x-waggies.button href="{{ $primaryHref }}">
                    {{ $primaryLabel }}
                    <x-waggies.icon name="arrow-forward" size="17" />
                </x-waggies.button>

                @if($secondaryLabel && $secondaryHref)
                    <x-waggies.button href="{{ $secondaryHref }}" variant="secondary">
                        {{ $secondaryLabel }}
                    </x-waggies.button>
                @endif
            </div>
        </div>
    </div>
</section>
