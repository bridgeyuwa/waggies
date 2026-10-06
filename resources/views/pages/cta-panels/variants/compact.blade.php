<section class="bg-surface-purple/40 py-8 sm:py-10" aria-labelledby="cta-panel-heading">
    <div class="page-container">
        <div class="rounded-2xl bg-surface-purple px-6 py-7 sm:px-8 sm:py-8">
            <div class="mx-auto flex max-w-6xl flex-col gap-6 sm:flex-row sm:items-center sm:justify-between sm:gap-10">
                <div class="max-w-3xl">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-primary">{{ $cta['eyebrow'] }}</p>
                    <h2 id="cta-panel-heading" class="mt-2 font-serif text-2xl font-bold leading-tight text-primary-dark sm:text-3xl">{{ $cta['heading'] }}</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-relaxed text-primary-dark/70">{{ $cta['body'] }}</p>
                </div>
                <div class="flex shrink-0 flex-col items-start gap-1 sm:items-end">
                    <x-waggies.button href="{{ $cta['primaryHref'] }}">{{ $cta['primaryLabel'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    <x-waggies.button href="{{ $cta['secondaryHref'] }}" variant="link" class="text-sm">{{ $cta['secondaryLabel'] }}</x-waggies.button>
                </div>
            </div>
        </div>
    </div>
</section>
