<section class="bg-surface-purple/40 py-8 sm:py-10" aria-labelledby="cta-panel-heading">
    <div class="page-container">
        <div class="rounded-2xl bg-primary-dark px-6 py-9 text-white sm:px-9 sm:py-11 lg:px-12">
            <div class="mx-auto flex max-w-6xl flex-col gap-8 md:flex-row md:items-center md:justify-between md:gap-12">
                <div class="max-w-2xl">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-secondary">{{ $cta['eyebrow'] }}</p>
                    <h2 id="cta-panel-heading" class="mt-4 font-serif text-3xl font-bold leading-tight text-white sm:text-4xl">{{ $cta['heading'] }}</h2>
                    <p class="mt-4 max-w-xl text-sm leading-relaxed text-white/75 sm:text-base">{{ $cta['body'] }}</p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-3 md:max-w-72 md:flex-col">
                    <x-waggies.button href="{{ $cta['primaryHref'] }}" tone="dark" class="w-full px-1.5 gap-1 text-sm sm:px-8 sm:gap-2 sm:text-base">{{ $cta['primaryLabel'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    <x-waggies.button href="{{ $cta['secondaryHref'] }}" variant="secondary" tone="dark" class="w-full">{{ $cta['secondaryLabel'] }}</x-waggies.button>
                </div>
            </div>
        </div>
    </div>
</section>
