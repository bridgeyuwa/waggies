@props(['card'])

@php
    $href = isset($card['route'])
        ? route($card['route'], $card['params'] ?? [])
        : ($card['href'] ?? '#');
@endphp

<a href="{{ $href }}"
    {{ $attributes->class(['w-card w-card-hover group flex h-full flex-col overflow-hidden p-0']) }}>
    <div class="relative aspect-3/2 overflow-hidden">
        <x-waggies.image src="{{ $card['imageSrc'] }}" alt="{{ $card['imageAlt'] ?? $card['title'] }}"
            class="w-card-media w-card-media--zoom h-full w-full object-cover motion-reduce:transform-none" />
    </div>

    <div class="flex flex-1 flex-col gap-4 p-6">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2.5">
                @if (!empty($card['icon']))
                    <x-waggies.icon name="{{ $card['icon'] }}" size="20" class="shrink-0 text-primary" aria-hidden="true" />
                @endif
                <h3 class="font-serif text-lg font-bold text-primary-dark">{{ $card['title'] }}</h3>
            </div>
            <p class="text-body-sm">{{ $card['description'] }}</p>
        </div>

        <span class="inline-flex w-fit items-center gap-1.5 text-sm font-semibold text-primary transition-colors group-hover:text-primary-dark motion-reduce:transition-none">
            Learn more
            <x-waggies.icon name="arrow-forward" size="16"
                class="transition-transform group-hover:translate-x-0.5 motion-reduce:transition-none" aria-hidden="true" />
        </span>
    </div>
</a>
