{{--
    Centered section heading block (B3).

    Props:
      $eyebrow  — small uppercase label above the title
      $title    — h2 text
      $subtitle — optional paragraph below title (or use $slot for rich content)
      $spacing  — Tailwind margin-bottom class. Default: mb-16
--}}
@props([
    'eyebrow'  => '',
    'title'    => '',
    'subtitle' => null,
    'spacing'  => 'mb-16',
])

<div class="text-center {{ $spacing }}">
    @if($eyebrow)
        <span class="text-primary font-bold tracking-widest uppercase text-xs mb-3 block">
            {{ $eyebrow }}
        </span>
    @endif

    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
        {!! $title !!}
    </h2>

    @if($subtitle || $slot->isNotEmpty())
        <div class="text-primary-dark/50 max-w-2xl mx-auto">
            {{ $slot->isNotEmpty() ? $slot : $subtitle }}
        </div>
    @endif
</div>
