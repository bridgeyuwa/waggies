@extends('layouts.app')

@section('content')
<x-waggies.breadcrumb-strip :items="[['label' => 'Shop']]" class="border-b border-primary/5 bg-white" />

<x-waggies.page-header class="bg-surface" alignment="center" eyebrow="Pet Shop" title="Pet Supplies &amp; Products" description="Food, toys, grooming essentials, health products, and accessories for your pets." />

<section x-data="waggiesShopIndex(@js($activeCategory), @js($categories))" class="bg-surface pb-20 md:pb-28">
    <div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12">
        <div class="mb-10 flex flex-wrap justify-center gap-2">
            <button type="button" @click="select('All')" :aria-pressed="activeCategory === 'All'" :class="activeCategory === 'All' ? 'bg-primary text-white' : 'border border-primary/10 bg-white text-primary-dark/60 hover:bg-surface-purple hover:text-primary-dark'" class="min-h-[44px] rounded-full px-5 py-2.5 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">All</button>
            @foreach($categories as $category)
                <button type="button" @click="select('{{ $category }}')" :aria-pressed="activeCategory === '{{ $category }}'" :class="activeCategory === '{{ $category }}' ? 'bg-primary text-white' : 'border border-primary/10 bg-white text-primary-dark/60 hover:bg-surface-purple hover:text-primary-dark'" class="min-h-[44px] rounded-full px-5 py-2.5 text-sm font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">{{ $category }}</button>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            @foreach($products as $product)
                <x-waggies.shop-product-card :product="$product" visibility="activeCategory === 'All' || activeCategory === '{{ $product['category'] }}'" />
            @endforeach
        </div>

        <div x-show="visibleCount() === 0" x-cloak class="py-20 text-center">
            <x-waggies.icon name="search" size="36" class="text-primary-dark/20" />
            <p class="mt-3 text-sm text-primary-dark/50">No products found in this category.</p>
        </div>
    </div>
</section>
@endsection
