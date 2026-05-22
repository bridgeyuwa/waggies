<x-layouts.listing title="{{ ucwords(str_replace('-', ' ', $slug)) }} Guides" nav-section="blog">

    <x-slot:hero>
        <x-hero.hub
            type="guide"
            eyebrow="Guides · Category"
            :title="ucwords(str_replace('-', ' ', $slug))"
            min-height="420px"
        />
    </x-slot:hero>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-4">Topics</h3>
            <ul class="flex flex-col gap-2">
                <li><a href="{{ route('guides.index') }}" class="text-sm text-primary-dark/70 hover:text-primary transition">All Guides</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('guides.category', $cat) }}"
                       class="text-sm transition capitalize {{ $cat === $slug ? 'text-primary font-semibold' : 'text-primary-dark/70 hover:text-primary' }}">
                        {{ str_replace('-', ' ', $cat) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </x-slot:sidebar>

    @if($featured)
        <x-content.featured-story :post="$featured" type="guide" />
    @endif

    @if($guides->isEmpty() && ! $featured)
        <p class="text-primary-dark/50 text-sm">No guides in this category yet — check back soon.</p>
    @elseif($guides->isNotEmpty())
        @if($featured)
            <x-section-heading eyebrow="Topic" title="More guides" class="mb-8" />
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($guides as $guide)
            <a href="{{ route('guides.show', $guide->slug) }}"
               class="group bg-white rounded-2xl shadow-sm hover:shadow-soft transition-shadow overflow-hidden flex flex-col">
                <div class="h-44 bg-surface-purple group-hover:bg-primary/10 transition-colors overflow-hidden">
                    @if($guide->image_url)
                        <img src="{{ $guide->image_url }}" alt="{{ $guide->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy" />
                    @endif
                </div>
                <div class="p-5 flex flex-col gap-2 flex-1">
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60 capitalize">
                        {{ str_replace('-', ' ', $guide->category) }}
                    </span>
                    <h2 class="font-serif text-lg font-bold text-primary-dark leading-snug group-hover:text-primary transition-colors">
                        {{ $guide->title }}
                    </h2>
                    @if($guide->excerpt)
                        <p class="text-sm text-primary-dark/60 line-clamp-2">{{ $guide->excerpt }}</p>
                    @endif
                    <span class="text-xs text-primary font-semibold mt-auto inline-flex items-center gap-1">
                        Read guide <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $guides->links() }}</div>
    @endif


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::collectionPage()
    ->name(ucwords(str_replace('-', ' ', $slug)) . ' Guides — Waggies')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.listing>
