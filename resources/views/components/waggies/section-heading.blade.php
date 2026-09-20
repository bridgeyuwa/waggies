@props(['eyebrow' => null, 'title', 'subtitle' => null, 'spacing' => 'mb-16'])

<div class="text-center {{ $spacing }}">
    @if($eyebrow)<span class="text-eyebrow mb-4 block">{{ $eyebrow }}</span>@endif
    <h2 class="font-serif text-3xl md:text-[2.5rem] md:leading-[1.12] font-bold text-primary-dark leading-tight mb-4 text-balance">{!! $title !!}</h2>
    @if($subtitle)<div class="text-primary-dark/60 max-w-2xl mx-auto text-pretty">{{ $subtitle }}</div>@endif
</div>
