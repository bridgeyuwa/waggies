@props(['hero', 'titleId' => 'hero-image-title'])

<section class="relative flex min-h-[560px] flex-col justify-end overflow-hidden" aria-labelledby="{{ $titleId }}">
    <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $hero['imageSrc'] }}')" aria-hidden="true"></div>
    <div class="absolute inset-0" style="background:linear-gradient(rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%)" aria-hidden="true"></div>
    <div class="pointer-events-none absolute inset-0" style="background:radial-gradient(ellipse 70% 60% at 50% 50%, transparent 50%, rgba(0,0,0,0.3) 100%)" aria-hidden="true"></div>
    <div class="relative z-10 max-w-7xl mx-auto w-full px-4 md:px-10 lg:px-12 py-12 md:py-16">
        <div class="relative z-10 max-w-xl">
            @if(!empty($hero['eyebrow']))<span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full mb-5 bg-primary-dark/70 border border-white/25 text-white text-[0.6875rem] font-semibold uppercase tracking-[0.14em]"><x-waggies.icon name="{{ $hero['eyebrowIcon'] ?? 'star' }}" size="14" class="text-secondary" />{{ $hero['eyebrow'] }}</span>@endif
            <h1 id="{{ $titleId }}" class="font-serif text-4xl md:text-5xl lg:text-[3.25rem] lg:leading-[1.08] font-bold text-white leading-tight mb-4 text-balance">{!! $hero['title'] !!}</h1>
            @if(!empty($hero['subtitle']))<p class="text-white/75 text-base mb-6 leading-relaxed">{{ $hero['subtitle'] }}</p>@endif
            @if(!empty($hero['primaryCta']))<div class="flex flex-wrap gap-3"><a href="{{ str_starts_with($hero['primaryCta']['href'], '#') ? $hero['primaryCta']['href'] : url($hero['primaryCta']['href']) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $hero['primaryCta']['label'] }} <x-waggies.icon name="arrow-forward" size="18" /></a></div>@endif
        </div>
    </div>
    <span class="sr-only">{{ $hero['imageAlt'] }}</span>
</section>
