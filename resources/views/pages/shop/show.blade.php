@extends('layouts.app')

@php($cartItem = ['productId' => $product['id'], 'name' => $product['name'], 'price' => $product['price'], 'imageSrc' => $product['image']])
@php($galleryImages = count($product['gallery']) ? $product['gallery'] : [['url' => $product['image'], 'thumb' => $product['image'], 'srcset' => $product['imageSrcset'] ?? null, 'alt' => $product['alt']]])

@section('content')
<x-waggies.breadcrumb-strip :items="[['label' => 'Shop', 'route' => 'shop.index', 'params' => []], ['label' => $product['name']]]" class="border-b border-primary/5 bg-white" />
<div x-data="waggiesProductDetail(@js($product['id']), @js($cartItem))">
    <section class="bg-surface pb-16 md:pb-24"><div class="page-container"><div class="grid grid-cols-1 gap-10 md:grid-cols-2 md:gap-16">
        <div x-data="waggiesProductGallery(@js($galleryImages))" @keydown="handleGalleryKeydown($event)" @keydown.window="handleLightboxKeydown($event)" class="min-w-0">
            <div class="flex flex-col gap-4 md:flex-row">
                <div class="order-2 flex min-w-0 items-center gap-3 md:order-1 md:w-20 md:flex-col">
                    <div x-ref="thumbnailRail" class="flex min-w-0 flex-1 gap-3 overflow-x-auto overscroll-x-contain pb-1 scrollbar-none md:max-h-124 md:w-full md:flex-col md:overflow-x-hidden md:overflow-y-auto md:pb-0">
                        <template x-for="(image, index) in images" :key="image.url + '-' + index">
                            <button type="button" @click="select(index)" :aria-current="activeIndex === index ? 'true' : 'false'" :aria-label="'Show image ' + (index + 1) + ': ' + image.alt" :class="activeIndex === index ? 'ring-2 ring-primary ring-offset-2' : 'ring-1 ring-primary/10 hover:ring-primary/40'" class="h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-white transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2 md:h-16 md:w-16">
                                <img :src="image.thumb || image.url" :alt="'Thumbnail ' + (index + 1) + ': ' + image.alt" class="h-full w-full object-cover" loading="lazy" />
                            </button>
                        </template>
                    </div>
                    <span class="shrink-0 text-xs font-semibold text-primary-dark/55 md:hidden" aria-live="polite"><span x-text="activeIndex + 1"></span>/<span x-text="images.length"></span></span>
                </div>
                <div class="relative order-1 min-w-0 flex-1">
                    <button type="button" x-ref="mainImage" @click="activateMainImage()" @touchstart="startTouch($event)" @touchend="endTouch($event)" class="group relative block aspect-square w-full overflow-hidden rounded-2xl bg-white ring-1 ring-primary/10 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" :aria-label="'Open ' + currentImage().alt + ' in larger view'">
                        <img src="{{ $galleryImages[0]['url'] }}" alt="{{ $galleryImages[0]['alt'] }}" class="h-full w-full object-contain p-5 transition-transform duration-300 group-hover:scale-[1.02] sm:p-8" :src="currentImage().url" :srcset="currentImage().srcset || null" :alt="currentImage().alt" />
                        <span class="pointer-events-none absolute bottom-4 left-4 inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-2 text-xs font-semibold text-primary-dark shadow-sm"><x-waggies.icon name="zoom-in" size="15" />View larger</span>
                    </button>
                    @if($product['badge'])<span class="absolute left-4 top-4 rounded-full bg-primary px-3 py-1.5 text-xs font-bold uppercase tracking-wider text-white">{{ $product['badge'] }}</span>@endif
                    <button type="button" x-show="images.length > 1" @click="previous()" aria-label="Previous image" class="absolute left-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-primary-dark shadow-md transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" x-cloak><x-waggies.icon name="chevron-left" size="20" /></button>
                    <button type="button" x-show="images.length > 1" @click="next()" aria-label="Next image" class="absolute right-3 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-primary-dark shadow-md transition hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2" x-cloak><x-waggies.icon name="chevron-right" size="20" /></button>
                    <p class="mt-3 text-center text-xs font-semibold text-primary-dark/55" aria-live="polite">Photo <span x-text="activeIndex + 1"></span> of <span x-text="images.length"></span></p>
                </div>
            </div>
            <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-layer-lightbox flex flex-col bg-primary-dark/95" role="dialog" aria-modal="true" aria-label="Product image lightbox" @click.self="closeLightbox()" @touchstart="startTouch($event)" @touchend="endTouch($event)">
                <div class="flex items-center justify-between px-4 py-4 text-white/80 sm:px-8">
                    <p class="text-sm font-semibold"><span x-text="activeIndex + 1"></span> <span class="text-white/40">/</span> <span x-text="images.length"></span></p>
                    <button type="button" x-ref="lightboxClose" @click="closeLightbox()" aria-label="Close product image lightbox" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"><x-waggies.icon name="close" size="20" /></button>
                </div>
                <div class="relative flex min-h-0 flex-1 items-center justify-center px-4 pb-8 sm:px-20">
                    <button type="button" x-show="images.length > 1" @click="previous()" aria-label="Previous image" class="absolute left-2 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:left-6" x-cloak><x-waggies.icon name="chevron-left" size="24" /></button>
                    <img :src="currentImage().url" :srcset="currentImage().srcset || null" :alt="currentImage().alt" class="max-h-[78vh] max-w-full rounded-lg object-contain shadow-2xl" />
                    <button type="button" x-show="images.length > 1" @click="next()" aria-label="Next image" class="absolute right-2 top-1/2 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white transition hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white sm:right-6" x-cloak><x-waggies.icon name="chevron-right" size="24" /></button>
                </div>
                <p class="pb-4 text-center text-xs text-white/45 sm:hidden">Swipe to navigate · tap outside to close</p>
            </div>
        </div>
        <div class="flex flex-col gap-5"><div><p class="mb-2 text-xs font-medium uppercase tracking-wider text-primary/60">{{ $product['category'] }}</p><h1 class="font-serif text-2xl font-bold leading-tight text-primary-dark md:text-3xl">{{ $product['name'] }}</h1></div><p class="text-2xl font-bold text-primary-dark">₦{{ number_format($product['price']) }}</p><p class="text-sm leading-relaxed text-primary-dark/60">{{ $product['description'] }}</p>
            @if(count($product['features']))<ul class="flex flex-col gap-2">@foreach($product['features'] as $feature)<li class="flex items-start gap-2 text-sm text-primary-dark/70"><x-waggies.icon name="check-circle" size="16" class="mt-0.5 shrink-0 text-primary" />{{ $feature }}</li>@endforeach</ul>@endif
            <p class="inline-flex w-fit rounded-full bg-surface-purple px-3 py-1 text-xs font-semibold text-primary-dark">{{ $product['availabilityLabel'] }}</p><div class="mt-2 flex flex-wrap items-center gap-4"><div class="flex items-center overflow-hidden rounded-full border border-primary/10"><button type="button" @click="decrease()" aria-label="Decrease quantity" class="flex h-11 w-11 items-center justify-center text-primary-dark transition-colors hover:bg-surface-purple focus:outline-none focus:ring-2 focus:ring-primary/60"><x-waggies.icon name="remove" size="18" /></button><span class="w-10 select-none text-center text-sm font-semibold text-primary-dark" aria-live="polite" x-text="quantity" :aria-label="`Quantity: ${quantity}`">1</span><button type="button" @click="increase()" aria-label="Increase quantity" class="flex h-11 w-11 items-center justify-center text-primary-dark transition-colors hover:bg-surface-purple focus:outline-none focus:ring-2 focus:ring-primary/60"><x-waggies.icon name="add" size="18" /></button></div><x-waggies.button type="button" @click="addToCart()"><x-waggies.icon name="add-to-cart" size="16" />Add to saved list</x-waggies.button><x-waggies.button href="{{ route('contact', ['intent' => 'product-inquiry', 'product' => $product['id'], 'productName' => $product['name']]) }}" variant="secondary">Ask About This Product</x-waggies.button></div>
            <a href="{{ route('shop.index') }}" class="mt-4 inline-flex w-fit items-center gap-1.5 text-sm font-medium text-primary transition-colors hover:text-primary-dark"><x-waggies.icon name="arrow-back" size="16" />Back to Shop</a>
        </div>
    </div></div></section>
    @if(count($relatedProducts))<section class="bg-white pb-20 md:pb-28"><div class="page-container"><h2 class="mb-10 text-center font-serif text-2xl font-bold text-primary-dark md:text-3xl">You May Also Like</h2><div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">@foreach($relatedProducts as $related)<x-waggies.shop-product-card :product="$related" />@endforeach</div></div></section>@endif
    <section x-data="waggiesRecentlyViewed(@js($product['id']))" x-show="hasVisible()" x-cloak class="bg-surface pb-20 md:pb-28"><div class="page-container"><h2 class="mb-8 font-serif text-2xl font-bold text-primary-dark">Recently Viewed</h2><div class="scrollbar-thin flex gap-6 overflow-x-auto pb-4">@foreach($recentProducts as $recent)<div x-show="isVisible('{{ $recent['id'] }}')" x-cloak class="w-64 shrink-0"><x-waggies.shop-product-card :product="$recent" /></div>@endforeach</div></div></section>
</div>
@endsection
