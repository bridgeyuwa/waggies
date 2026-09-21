@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Knowledge Base']]" class="bg-white border-b border-primary/5" />

    <x-waggies.page-header alignment="center" eyebrow="Knowledge Base" eyebrow-icon="training" title="Pet Care Answers" description="Find quick, practical answers to common questions about pet care services and more." />

    <section x-data="waggiesKnowledgeBase(@js($filterItems), @js($categories), @js($initialCategory), @js($initialSearch), {{ $currentPage }})" class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-12 md:py-20">
        <div class="flex flex-col sm:flex-row gap-4 mb-10">
            <div class="relative flex-1 max-w-md">
                <x-waggies.icon name="search" size="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-text-muted" />
                <input type="text" placeholder="Search articles..." x-model="search" class="w-full pl-10 pr-4 py-2.5 rounded-full border border-border-subtle bg-white text-sm text-primary-dark placeholder:text-text-muted focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" @click="select('All')" class="rounded-full px-4 py-2 text-label" :class="activeCategory === 'All' ? 'bg-primary text-white' : 'bg-surface-purple text-primary hover:bg-primary/10'">All</button>
                @foreach($categories as $category)
                    <button type="button" @click="select('{{ $category }}')" class="rounded-full px-4 py-2 text-label" :class="activeCategory === '{{ $category }}' ? 'bg-primary text-white' : 'bg-surface-purple text-primary hover:bg-primary/10'">{{ $category }}</button>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $index => $article)
                <div x-show="matches({{ $index }})" x-transition.opacity>
                    <x-waggies.article-card :guide="$article" route-name="knowledge-base.show" />
                </div>
            @endforeach
        </div>

        <div x-show="visibleCount() === 0" x-cloak class="text-center py-16">
            <x-waggies.icon name="search" size="48" class="mb-4 text-text-muted/40" />
            <p class="text-text-muted text-body">No articles found. Try a different search term or category.</p>
        </div>

        <nav x-show="totalPages() > 1" role="navigation" aria-label="Pagination Navigation" class="mt-12 flex items-center justify-between gap-4 flex-wrap">
            <div class="flex items-center gap-2 sm:hidden">
                <a x-show="page > 1" :href="pageHref(page - 1)" rel="prev" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-primary/30 rounded-full hover:bg-primary/5 transition">« Previous</a>
                <span x-show="page <= 1" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-full cursor-not-allowed">« Previous</span>
                <a x-show="page < totalPages()" :href="pageHref(page + 1)" rel="next" class="inline-flex items-center px-4 py-2 text-sm font-medium text-primary bg-white border border-primary/30 rounded-full hover:bg-primary/5 transition">Next »</a>
                <span x-show="page >= totalPages()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-full cursor-not-allowed">Next »</span>
            </div>
            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between gap-4 flex-wrap">
                <p class="text-sm text-gray-500">Showing <span class="font-semibold text-gray-700" x-text="rangeStart()"></span> - <span class="font-semibold text-gray-700" x-text="rangeEnd()"></span> of <span class="font-semibold text-gray-700" x-text="filteredItems().length"></span> results</p>
                <div class="flex items-center gap-1">
                    <a x-show="page > 1" :href="pageHref(page - 1)" rel="prev" aria-label="Previous page" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-primary rounded-full hover:bg-primary/10 transition"><x-waggies.icon name="chevron-left" size="20" /></a>
                    <span x-show="page <= 1" aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-gray-300 rounded-full cursor-not-allowed"><x-waggies.icon name="chevron-left" size="20" /></span>
                    <template x-for="number in pageNumbers()" :key="number"><span class="contents"><template x-if="number === page"><span class="inline-flex items-center justify-center w-9 h-9 text-sm font-semibold text-white bg-primary rounded-full shadow-sm" aria-current="page" x-text="number"></span></template><template x-if="number !== page"><a :href="pageHref(number)" :aria-label="`Go to page ${number}`" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-primary rounded-full hover:bg-primary/10 transition" x-text="number"></a></template></span></template>
                    <a x-show="page < totalPages()" :href="pageHref(page + 1)" rel="next" aria-label="Next »" class="inline-flex items-center justify-center w-9 h-9 text-sm font-medium text-primary rounded-full hover:bg-primary/10 transition"><x-waggies.icon name="chevron-right" size="20" /></a>
                    <span x-show="page >= totalPages()" aria-disabled="true" class="inline-flex items-center justify-center w-9 h-9 text-gray-300 rounded-full cursor-not-allowed"><x-waggies.icon name="chevron-right" size="20" /></span>
                </div>
            </div>
        </nav>
    </section>

@endsection
