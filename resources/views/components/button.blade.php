{{--
    Button component — all Waggies button variants.

    Props:
      $href      — if provided, renders an <a> tag instead of <button>
      $type      — button type attribute (button | submit | reset). Default: button
      $variant   — primary | secondary | ghost-outline | ghost-secondary | nav | pill-active | pill-inactive
      $size      — sm | md | lg  (only applies to primary variant)
      $icon      — Material Symbol name appended after label. Pass null to suppress.
      $label     — visible text content (alternative to $slot)
      $disabled  — boolean
--}}
@props([
    'href'     => null,
    'type'     => 'button',
    'variant'  => 'primary',
    'size'     => 'md',
    'icon'     => 'arrow_forward',
    'label'    => null,
    'disabled' => false,
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold transition
             focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2
             rounded-full';

    $classes = match($variant) {
        'primary' => $base . ' bg-primary hover:bg-primary-dark text-white shadow-glow hover:-translate-y-1 ' . match($size) {
            'sm'    => 'px-6 py-2.5 text-sm',
            'lg'    => 'px-10 py-4 text-lg font-bold',
            default => 'px-8 py-3',
        },
        'secondary'      => $base . ' bg-secondary hover:bg-secondary-hover text-primary-dark font-bold shadow-glow hover:-translate-y-1 px-10 py-4',
        'ghost-outline'  => $base . ' border border-white/30 text-white hover:bg-white/10 transition-colors px-4 py-2 text-xs',
        'ghost-secondary'=> $base . ' bg-secondary text-primary-dark hover:bg-secondary-hover transition-colors px-4 py-2 text-xs',
        'nav'            => 'inline-flex items-center gap-1 px-4 py-2 text-sm font-medium text-primary-dark/60
                             hover:text-primary uppercase tracking-wide rounded-lg hover:bg-surface-purple transition-colors
                             focus:outline-none focus:ring-2 focus:ring-primary/60',
        'pill-active'    => 'inline-flex items-center justify-center border-2 border-primary bg-secondary text-primary
                             font-bold rounded-full px-5 py-2 text-sm
                             focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2',
        'pill-inactive'  => 'inline-flex items-center justify-center border border-primary/30 text-primary-dark/70
                             font-medium rounded-full px-5 py-2 text-sm hover:border-primary/50 hover:text-primary transition-colors
                             focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2',
        'outline-primary'=> $base . ' border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors py-3 px-8',
        'outline-secondary' => $base . ' border-2 border-secondary text-secondary hover:bg-secondary hover:text-primary-dark transition-colors py-3 px-8',
        default          => $base . ' bg-primary hover:bg-primary-dark text-white shadow-glow hover:-translate-y-1 px-8 py-3',
    };

    $disabledClasses = $disabled ? ' opacity-50 cursor-not-allowed pointer-events-none' : '';
@endphp

@if($href)
    <a href="{{ $disabled ? null : $href }}"
       {{ $attributes->class([$classes . $disabledClasses]) }}
       @if($disabled) aria-disabled="true" tabindex="-1" @endif>
        {{ $label ?? $slot }}
        @if($icon)
            <span class="material-symbols-outlined text-base">{{ $icon }}</span>
        @endif
    </a>
@else
    <button type="{{ $type }}"
            {{ $attributes->class([$classes . $disabledClasses]) }}
            @if($disabled) disabled @endif>
        {{ $label ?? $slot }}
        @if($icon)
            <span class="material-symbols-outlined text-base">{{ $icon }}</span>
        @endif
    </button>
@endif
