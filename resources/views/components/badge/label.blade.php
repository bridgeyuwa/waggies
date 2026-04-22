{{--
    Label / eyebrow badge.

    Props:
      $variant — surface | primary | eyebrow
      $label   — badge text
--}}
@props([
    'variant' => 'surface',
    'label'   => '',
])

@php
    $classes = match($variant) {
        'primary' => 'bg-primary text-white text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-wider shadow-glow',
        'eyebrow' => 'inline-flex items-center bg-primary-dark rounded-xl px-3 py-2',
        default   => 'bg-surface-purple text-primary-dark text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider',
    };
@endphp

@if($variant === 'eyebrow')
    <div class="{{ $classes }}">
        <span class="text-white border border-white/20 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest shadow-soft"
              style="background: rgba(255,255,255,0.15)">
            {{ $label }}
        </span>
    </div>
@else
    <span class="{{ $classes }}">{{ $label }}</span>
@endif
