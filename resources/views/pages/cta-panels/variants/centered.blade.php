<section class="bg-surface-purple/40 py-8 sm:py-10" aria-labelledby="cta-panel-heading">
    <div class="page-container">
        <div class="mx-auto max-w-5xl rounded-3xl bg-primary-dark px-6 py-10 text-center text-white sm:px-10 sm:py-14">
            <p class="text-xs font-bold uppercase tracking-[0.14em] text-secondary">{{ $cta['eyebrow'] }}</p>
            <h2 id="cta-panel-heading" class="mx-auto mt-4 max-w-3xl font-serif text-3xl font-bold leading-tight text-white sm:text-4xl">{{ $cta['heading'] }}</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-relaxed text-white/75 sm:text-base">{{ $cta['body'] }}</p>
            <div class="mt-7 flex flex-wrap justify-center gap-3">
                <x-waggies.button href="{{ $cta['primaryHref'] }}" tone="dark">{{ $cta['primaryLabel'] }} <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                <x-waggies.button href="{{ $cta['secondaryHref'] }}" variant="secondary" tone="dark">{{ $cta['secondaryLabel'] }}</x-waggies.button>
            </div>
        </div>
    </div>
</section>
