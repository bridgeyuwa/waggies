@props(['hero', 'titleId' => 'home-cover-hero-title', 'center' => false, 'heightContract' => 'canonical', 'mode' => 'legacy', 'alignment' => 'center', 'minHeight' => null, 'mobileMinHeight' => null])

@php
    $eyebrowIcon = $hero['eyebrowIcon'] ?? ($mode === 'canonical' ? 'pets' : 'star');
    $primaryCta = $hero['cta'] ?? $hero['primaryCta'] ?? null;
    $secondaryCta = $hero['secondaryCta'] ?? null;
    $ctaDestination = static fn (array $cta): string => isset($cta['route']) ? route($cta['route'], $cta['params'] ?? []) : url($cta['href']);
    $heightClass = match ($heightContract) {
        'grooming', 'vet-care' => 'min-h-[428px] md:min-h-[420px]',
        'training' => 'min-h-[454px] md:min-h-[449px]',
        default => 'min-h-[390px] md:min-h-[560px]',
    };
    $heroMinHeight = $minHeight ?? match ($heightContract) {
        'grooming', 'vet-care' => '420px',
        'training' => '449px',
        default => '560px',
    };
    $heroMobileMinHeight = $mobileMinHeight ?? match ($heightContract) {
        'grooming', 'vet-care' => '428px',
        'training' => '454px',
        default => '560px',
    };
    $contentClass = in_array($heightContract, ['grooming', 'training', 'vet-care'], true)
        ? 'mx-auto w-full max-w-7xl px-4 pb-[calc(6rem+env(safe-area-inset-bottom,0px))] pt-16 md:px-10 md:pb-16 lg:px-12 lg:pt-24'
        : 'mx-auto w-full max-w-7xl px-4 py-12 md:px-10 md:py-16 lg:px-12';
@endphp

@if ($mode === 'canonical')
    <section class="relative isolate flex min-h-[var(--hero-mobile-min-height)] overflow-hidden bg-primary-dark md:min-h-[var(--hero-min-height)]" style="--hero-min-height: {{ $heroMinHeight }}; --hero-mobile-min-height: {{ $heroMobileMinHeight }}" aria-labelledby="{{ $titleId }}">
        <img src="{{ $hero['imageSrc'] }}" alt="{{ $hero['imageAlt'] }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
        <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.28) 52%, rgba(0, 0, 0, 0.70) 100%)" aria-hidden="true"></div>
        <div class="relative z-10 flex w-full items-center"><div class="mx-auto w-full max-w-7xl px-4 pb-[calc(6rem+env(safe-area-inset-bottom,0px))] pt-16 md:px-10 md:pb-16 lg:px-12 lg:pt-24"><div class="max-w-xl">
            @if(!empty($hero['eyebrow']))<span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white"><x-waggies.icon name="{{ $hero['eyebrowIcon'] ?? 'pets' }}" size="14" class="text-secondary" />{{ $hero['eyebrow'] }}</span>@endif
            <h1 id="{{ $titleId }}" class="mb-4 text-balance font-serif text-4xl font-bold leading-tight text-white md:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">{!! $hero['title'] ?? '' !!}</h1>
            @if (!empty($hero['subtitle']))<p class="mb-7 max-w-xl text-base leading-relaxed text-white/80 md:text-lg">{{ $hero['subtitle'] }}</p>@endif
            @if($primaryCta || $secondaryCta)<div class="flex flex-wrap gap-3">
                @if($primaryCta)<a href="{{ $ctaDestination($primaryCta) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $primaryCta['label'] }} <x-waggies.icon name="{{ $primaryCta['icon'] ?? 'arrow-forward' }}" size="18" /></a>@endif
                @if($secondaryCta)<a href="{{ $ctaDestination($secondaryCta) }}" class="w-cta w-cta--secondary border-white/30 bg-transparent text-white hover:bg-white/10">{{ $secondaryCta['label'] }}</a>@endif
            </div>@endif
        </div></div></div>
    </section>
@elseif ($center)
    <section class="relative flex min-h-[560px] flex-col justify-center overflow-hidden" aria-labelledby="{{ $titleId }}">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $hero['imageSrc'] }}')" aria-hidden="true"></div>
        <div class="absolute inset-0" style="background:linear-gradient(rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%)" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0" style="background:radial-gradient(ellipse 70% 60% at 50% 50%, transparent 50%, rgba(0,0,0,0.3) 100%)" aria-hidden="true"></div>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 py-12 md:px-10 md:py-16 lg:px-12"><div class="relative z-10 max-w-xl">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-white"><x-waggies.icon name="{{ $eyebrowIcon }}" size="14" class="text-secondary" />{{ $hero['eyebrow'] ?? '' }}</span>
            <h1 id="{{ $titleId }}" class="mb-4 text-balance font-serif text-4xl font-bold leading-tight text-white md:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">{!! $hero['title'] ?? '' !!}</h1>
            @if (!empty($hero['subtitle']))<p class="mb-6 text-base leading-relaxed text-white/75">{{ $hero['subtitle'] }}</p>@endif
            @if($primaryCta || $secondaryCta)<div class="flex flex-wrap gap-3">
                @if($primaryCta)<a href="{{ $ctaDestination($primaryCta) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $primaryCta['label'] }} <x-waggies.icon name="{{ $primaryCta['icon'] ?? 'arrow-forward' }}" size="18" /></a>@endif
                @if($secondaryCta)<a href="{{ $ctaDestination($secondaryCta) }}" class="w-cta w-cta--secondary border-white/30 bg-transparent text-white hover:bg-white/10">@if(!empty($secondaryCta['iconBefore']))<x-waggies.icon name="{{ $secondaryCta['iconBefore'] }}" size="18" class="text-white" />@endif{{ $secondaryCta['label'] }}</a>@endif
            </div>@endif
        </div></div><span class="sr-only">{{ $hero['imageAlt'] }}</span>
    </section>
@elseif ($alignment === 'bottom')
    <section class="relative flex min-h-[var(--hero-mobile-min-height)] flex-col justify-end overflow-hidden md:min-h-[var(--hero-min-height)]" style="--hero-min-height: {{ $heroMinHeight }}; --hero-mobile-min-height: {{ $heroMobileMinHeight }}" aria-labelledby="{{ $titleId }}">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('{{ $hero['imageSrc'] }}')" aria-hidden="true"></div>
        <div class="absolute inset-0" style="background:linear-gradient(rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.65) 100%)" aria-hidden="true"></div>
        <div class="pointer-events-none absolute inset-0" style="background:radial-gradient(ellipse 70% 60% at 50% 50%, transparent 50%, rgba(0,0,0,0.3) 100%)" aria-hidden="true"></div>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 py-12 md:px-10 md:py-16 lg:px-12"><div class="relative z-10 max-w-xl">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-[0.6875rem] font-semibold uppercase tracking-[0.14em] text-white"><x-waggies.icon name="{{ $eyebrowIcon }}" size="14" class="text-secondary" />{{ $hero['eyebrow'] ?? '' }}</span>
            <h1 id="{{ $titleId }}" class="mb-4 text-balance font-serif text-4xl font-bold leading-tight text-white md:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">{!! $hero['title'] ?? '' !!}</h1>
            @if (!empty($hero['subtitle']))<p class="mb-6 text-base leading-relaxed text-white/75">{{ $hero['subtitle'] }}</p>@endif
            @if($primaryCta || $secondaryCta)<div class="flex flex-wrap gap-3">
                @if($primaryCta)<a href="{{ $ctaDestination($primaryCta) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $primaryCta['label'] }} @if(!empty($primaryCta['icon']))<x-waggies.icon name="{{ $primaryCta['icon'] }}" size="18" />@endif</a>@endif
                @if($secondaryCta)<a href="{{ $ctaDestination($secondaryCta) }}" class="w-cta w-cta--secondary border-white/30 bg-transparent text-white hover:bg-white/10">@if(!empty($secondaryCta['iconBefore']))<x-waggies.icon name="{{ $secondaryCta['iconBefore'] }}" size="18" class="text-white" />@endif{{ $secondaryCta['label'] }}</a>@endif
            </div>@endif
        </div></div><span class="sr-only">{{ $hero['imageAlt'] }}</span>
    </section>
@else
    <section class="relative isolate flex {{ $minHeight ? 'min-h-[var(--hero-mobile-min-height)] md:min-h-[var(--hero-min-height)]' : $heightClass }} overflow-hidden bg-primary-dark" @if($minHeight) style="--hero-min-height: {{ $heroMinHeight }}; --hero-mobile-min-height: {{ $heroMobileMinHeight }}" @endif aria-labelledby="{{ $titleId }}">
        <img src="{{ $hero['imageSrc'] }}" alt="{{ $hero['imageAlt'] }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
        <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0, 0, 0, 0.10) 0%, rgba(0, 0, 0, 0.28) 52%, rgba(0, 0, 0, 0.70) 100%)" aria-hidden="true"></div>
        <div class="relative z-10 flex w-full items-center"><div class="{{ $contentClass }}"><div class="max-w-xl">
            <span class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/25 bg-primary-dark/70 px-4 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-white"><x-waggies.icon name="{{ $eyebrowIcon }}" size="14" class="text-secondary" />{{ $hero['eyebrow'] ?? '' }}</span>
            <h1 id="{{ $titleId }}" class="mb-4 text-balance font-serif text-4xl font-bold leading-tight text-white md:text-5xl lg:text-[3.25rem] lg:leading-[1.08]">{!! $hero['title'] ?? '' !!}</h1>
            @if (!empty($hero['trust']))<div class="mb-5 inline-flex items-center gap-2 rounded-full border border-surface-purple bg-white px-4 py-1.5 shadow-sm"><x-waggies.icon name="star" variant="filled" size="18" class="text-gold" /><span class="text-xs font-bold text-primary-dark">4.9 · 500+ Reviews</span><span class="h-2 w-2 rounded-full bg-primary" aria-hidden="true"></span></div>@endif
            @if (!empty($hero['subtitle']))<p class="mb-7 max-w-xl text-base leading-relaxed text-white/80 md:text-lg">{{ $hero['subtitle'] }}</p>@endif
            @if($primaryCta || $secondaryCta)<div class="flex flex-wrap gap-3">
                @if($primaryCta)<a href="{{ $ctaDestination($primaryCta) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $primaryCta['label'] }} <x-waggies.icon name="{{ $primaryCta['icon'] ?? 'arrow-forward' }}" size="18" /></a>@endif
                @if($secondaryCta)<a href="{{ $ctaDestination($secondaryCta) }}" class="w-cta w-cta--secondary border-white/30 bg-transparent text-white hover:bg-white/10">@if(!empty($secondaryCta['iconBefore']))<x-waggies.icon name="{{ $secondaryCta['iconBefore'] }}" size="18" class="text-white" />@endif{{ $secondaryCta['label'] }}</a>@endif
            </div>@endif
        </div></div></div>
    </section>
@endif
