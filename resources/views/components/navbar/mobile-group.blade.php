{{-- Grouped section inside a mobile accordion panel --}}
@props([
    'heading' => '',
])

<div @if($heading) role="group" aria-label="{{ $heading }}" @endif class="py-2 first:pt-0">
    @if($heading)
        <p class="px-3 py-1.5 text-xs font-bold uppercase tracking-widest text-primary/60">{{ $heading }}</p>
    @endif
    <div class="flex flex-col gap-0.5">
        {{ $slot }}
    </div>
</div>
