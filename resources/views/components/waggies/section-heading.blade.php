@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'center', 'spacing' => 'mb-16'])

@php
    $alignmentClasses = $align === 'left' ? 'text-left' : 'text-center';
    $subtitleClasses = $align === 'left' ? 'max-w-2xl' : 'mx-auto max-w-2xl';
@endphp

<div {{ $attributes->class([$alignmentClasses, $spacing]) }}>
    @if($eyebrow)<span class="text-eyebrow mb-4 block">{{ $eyebrow }}</span>@endif
    <div class="flex flex-col gap-4 {{ $align === 'left' ? 'items-start' : 'items-center' }}">
        <h2 class="text-h2 text-primary-dark text-balance">{!! $title !!}</h2>
        @if($subtitle)<div class="{{ $subtitleClasses }} text-primary-dark/60 text-pretty">{{ $subtitle }}</div>@endif
        @if(isset($action) && $action->isNotEmpty())
            <div>{{ $action }}</div>
        @endif
    </div>
</div>
