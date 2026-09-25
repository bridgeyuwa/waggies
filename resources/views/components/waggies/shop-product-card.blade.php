@props(['product', 'visibility' => null])

@php
    $cartItem = [
        'productId' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'imageSrc' => $product['image'],
    ];
@endphp

<article data-shop-product-category="{{ $product['category'] }}" @if($visibility) x-show="{{ $visibility }}" @endif @if($visibility) x-cloak @endif {{ $attributes }}>
    <x-waggies.card hover class="group flex h-full flex-col overflow-hidden border border-primary/5 p-0">
        <a href="{{ route('shop.show', ['product' => $product['id']]) }}" class="flex flex-1 flex-col">
            <div class="relative aspect-square overflow-hidden bg-surface">
                <x-waggies.image src="{{ $product['image'] }}" alt="{{ $product['alt'] }}" class="w-card-media w-card-media--soft h-full w-full object-cover motion-reduce:transform-none" />
                @if($product['badge'])
                    <span class="absolute left-3 top-3 rounded-full bg-primary px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-white">{{ $product['badge'] }}</span>
                @endif
            </div>

            <div class="flex flex-1 flex-col gap-2 p-5">
                <p class="text-xs font-medium uppercase tracking-wider text-primary/60">{{ $product['category'] }}</p>
                <h3 class="line-clamp-2 font-serif text-base font-bold leading-snug text-primary-dark transition-colors group-hover:text-primary">{{ $product['name'] }}</h3>
                <p class="line-clamp-2 text-sm leading-relaxed text-primary-dark/50">{{ $product['description'] }}</p>
                <span class="mt-auto pt-1 text-lg font-bold text-primary-dark">₦{{ number_format($product['price']) }}</span>
            </div>
        </a>

        <div class="flex justify-end p-5 pt-0">
            <x-waggies.button type="button" @click="window.dispatchEvent(new CustomEvent('waggies:add-item', { detail: {{ Js::from($cartItem) }} }))" aria-label="Save {{ $product['name'] }} for enquiry" class="px-3 py-2 text-sm">
                <x-waggies.icon name="add-to-cart" size="16" />
                Save for enquiry
            </x-waggies.button>
        </div>
    </x-waggies.card>
</article>
