@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Guides']]" class="bg-white border-b border-primary/5" />

    <section class="w-page-intro section-pad">
        <div class="w-page-intro__content max-w-5xl mx-auto px-4 md:px-10 lg:px-12">
            <div class="flex flex-col gap-5 text-center items-center max-w-3xl mx-auto">
                <span class="text-eyebrow inline-flex items-center gap-2"><x-waggies.icon name="guide" size="16" class="text-secondary" />Guides</span>
                <h1 class="text-display text-primary-dark">Pet Care Guides</h1>
                <p class="text-body text-lg leading-relaxed max-w-2xl">Step-by-step guides to help you give your pets the best care.</p>
            </div>
        </div>
    </section>

    <section x-data="waggiesGuidesIndex('{{ $initialCategory }}')" class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-20">
        <div class="flex flex-wrap gap-2 mb-10">
            @foreach(array_merge(['All'], $categories) as $filter)
                <button type="button" @click="select('{{ $filter }}')" class="rounded-full px-4 py-2 transition-colors" :class="activeCategory === '{{ $filter }}' ? 'bg-primary text-white' : 'bg-surface-purple text-primary hover:bg-primary/10'">{{ $filter }}</button>
            @endforeach
        </div>

        @foreach(array_merge(['All'], $categories) as $filter)
            @php($filterItems = $filter === 'All' ? $items : array_values(array_filter($items, static fn (array $guide): bool => $guide['category'] === $filter)))
            <div @if($filter !== 'All') x-cloak @endif x-show="activeCategory === '{{ $filter }}'">
                @if(count($filterItems) > 0)
                    @php($featured = $filterItems[0])
                    @php($rest = array_slice($filterItems, 1))
                    <x-waggies.article-card :guide="$featured" featured class="mb-8" />

                    @if(count($rest) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($rest as $guide)
                                <x-waggies.article-card :guide="$guide" />
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="text-center py-16"><p class="text-text-muted text-body">No guides found in this category.</p></div>
                @endif
            </div>
        @endforeach

    </section>

@endsection
