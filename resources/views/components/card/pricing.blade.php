{{--
    Pricing card (B13).

    Props:
      $variant    — standard | featured
      $tierLabel  — e.g. "Standard", "Premium"
      $badgeLabel — text on the floating badge (featured variant only). e.g. "Most Popular"
      $price      — e.g. "₦15,000"
      $unit       — e.g. "/night"
      $features   — array of ['label' => string, 'included' => bool]
      $ctaLabel   — CTA button text
      $ctaHref    — CTA button href
--}}
@props([
    'variant' => 'standard',
    'tierLabel' => '',
    'badgeLabel' => 'Most Popular',
    'price' => '',
    'unit' => '/night',
    'features' => [],
    'ctaLabel' => 'Get Started',
    'ctaHref' => '#',
])

@php
    $isFeatured = $variant === 'featured';
    $wrapperClasses = $isFeatured
        ? 'relative bg-white rounded-2xl border-2 border-primary shadow-soft p-8'
        : 'relative bg-white rounded-2xl border border-surface-purple shadow-sm p-8 hover:shadow-soft transition-shadow';
@endphp

<div class="{{ $wrapperClasses }}">

    {{-- Featured badge --}}
    @if ($isFeatured)
        <div class="absolute -top-4 left-1/2 -translate-x-1/2">
            <span
                class="bg-primary text-white text-xs font-bold px-4 py-1.5 rounded-full shadow-glow uppercase tracking-wider whitespace-nowrap">
                {{ $badgeLabel }}
            </span>
        </div>
    @endif

    {{-- Tier label --}}
    <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-3">{{ $tierLabel }}</p>

    {{-- Price --}}
    <div class="flex items-baseline gap-1 mb-6">
        <span class="font-serif text-3xl font-bold text-primary-dark">{{ $price }}</span>
        <span class="text-base font-sans font-normal text-primary-dark/40">{{ $unit }}</span>
    </div>

    {{-- Feature list --}}
    <ul class="flex flex-col gap-3 mb-8">
        @foreach ($features as $feature)
            <li
                class="flex items-center gap-3 text-sm {{ $feature['included'] ? 'text-primary-dark' : 'text-primary-dark/30' }}">
                <span
                    class="material-symbols-outlined icon-filled text-base shrink-0
                             {{ $feature['included'] ? 'text-success' : 'text-primary-dark/30' }}">
                    {{ $feature['included'] ? 'check_circle' : 'remove' }}
                </span>
                {{ $feature['label'] }}
            </li>
        @endforeach
    </ul>

    {{-- CTA --}}
    @if ($isFeatured)
        <a href="{{ $ctaHref }}"
            class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white
                  py-3 rounded-full font-semibold transition shadow-glow hover:-translate-y-1
                  focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
            {{ $ctaLabel }}
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    @else
        <a href="{{ $ctaHref }}"
            class="w-full flex items-center justify-center gap-2 border-2 border-primary text-primary
                  hover:bg-primary hover:text-white py-3 rounded-full font-semibold transition
                  focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
            {{ $ctaLabel }}
        </a>
    @endif

</div>
