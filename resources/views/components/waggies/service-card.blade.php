@props(['card'])
<article {{ $attributes }}>
    <x-waggies.card hover class="group flex h-full flex-col overflow-hidden border border-primary/5 p-0">
        <div class="relative aspect-3/2 overflow-hidden">
            <div class="absolute inset-0 transition-transform duration-200 ease-out group-hover:scale-[1.02] motion-reduce:transition-none motion-reduce:transform-none">
                <x-waggies.image src="{{ $card['imageSrc'] }}" alt="{{ $card['imageAlt'] ?? $card['title'] }}" class="h-full w-full object-cover" />
            </div>
        </div>
        <div class="flex flex-1 flex-col gap-4 p-6">
            <div class="flex flex-1 flex-col gap-2">
                <h3 class="font-serif text-xl font-bold text-primary-dark">{{ $card['title'] }}</h3>
                <p class="line-clamp-2 text-sm leading-relaxed text-primary-dark/60">{{ $card['description'] }}</p>
            </div>
            <x-waggies.button href="{{ isset($card['route']) ? route($card['route'], $card['params'] ?? []) : url($card['href']) }}" variant="link" class="w-fit">
                Learn more <x-waggies.icon name="arrow-forward" size="16" class="transition-transform group-hover:translate-x-0.5 motion-reduce:transition-none" />
            </x-waggies.button>
        </div>
    </x-waggies.card>
</article>
