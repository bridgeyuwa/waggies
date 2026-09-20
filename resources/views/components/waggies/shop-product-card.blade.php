@props(['product', 'visibility' => null])

@php
    $cartItem = [
        'productId' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'imageSrc' => $product['image'],
    ];
@endphp

<a href="{{ route('shop.show', ['id' => $product['id']]) }}" data-shop-product-category="{{ $product['category'] }}" @if($visibility) x-show="{{ $visibility }}" @endif class="group w-card w-card-hover block h-full overflow-hidden border border-primary/5" @if($visibility) x-cloak @endif>
    <div class="relative aspect-square overflow-hidden rounded-t-[1rem] bg-surface">
        <img src="{{ $product['image'] }}" alt="{{ $product['alt'] }}" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.025]" loading="lazy" />
        @if($product['badge'])
            <span class="absolute left-3 top-3 rounded-full bg-primary px-2.5 py-1 text-xs font-bold uppercase tracking-wider text-white">{{ $product['badge'] }}</span>
        @endif
    </div>

    <div class="flex flex-col gap-2 p-5">
        <p class="text-xs font-medium uppercase tracking-wider text-primary/60">{{ $product['category'] }}</p>
        <h3 class="line-clamp-2 font-serif text-base font-bold leading-snug text-primary-dark">{{ $product['name'] }}</h3>
        <p class="line-clamp-2 text-sm leading-relaxed text-primary-dark/50">{{ $product['description'] }}</p>
        <div class="mt-1 flex items-center justify-between gap-2">
            <span class="text-lg font-bold text-primary-dark">₦{{ number_format($product['price']) }}</span>
            <button type="button" @click.prevent.stop="window.dispatchEvent(new CustomEvent('waggies:add-item', { detail: {{ Js::from($cartItem) }} }))" aria-label="Add {{ $product['name'] }} to cart" class="w-cta w-cta--primary px-3 py-2 text-sm">
                <x-waggies.icon name="add-to-cart" size="16" />
                Add to Cart
            </button>
        </div>
    </div>
</a>
