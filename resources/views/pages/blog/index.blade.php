<x-layouts.listing title="Blog" nav-section="blog">

    <x-slot:hero>
        <x-hero.hub type="blog" />
    </x-slot:hero>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-4">Categories</h3>
            <ul class="flex flex-col gap-2">
                <li><a href="{{ route('blog.index') }}" class="text-sm font-semibold text-primary">All Posts</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('blog.category', $cat) }}"
                       class="text-sm text-primary-dark/70 hover:text-primary transition capitalize">
                        {{ str_replace('-', ' ', $cat) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-primary rounded-2xl p-6">
            <h3 class="font-semibold text-white mb-2">Stay Updated</h3>
            <p class="text-white/70 text-sm mb-4">Get the latest pet care tips delivered to your inbox.</p>
            <x-newsletter title="" subtitle="" button-label="Subscribe" />
        </div>
    </x-slot:sidebar>

    @if($featured)
        <x-content.featured-story :post="$featured" type="blog" />
    @endif

    @if($posts->isEmpty() && ! $featured)
        <p class="text-primary-dark/50 text-sm">No posts published yet — check back soon.</p>
    @elseif($posts->isNotEmpty())
        <x-section-heading
            :eyebrow="$featured ? 'Latest' : null"
            title="{{ $featured ? 'More Articles' : 'All Articles' }}"
            class="mb-8"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}"
               class="group bg-white rounded-2xl shadow-sm hover:shadow-soft transition-shadow overflow-hidden flex flex-col">
                <div class="h-44 bg-surface-purple group-hover:bg-primary/10 transition-colors overflow-hidden">
                    @if($post->image_url)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                             loading="lazy" />
                    @endif
                </div>
                <div class="p-5 flex flex-col gap-2 flex-1">
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/60 capitalize">
                        {{ str_replace('-', ' ', $post->category) }}
                    </span>
                    <h2 class="font-serif text-lg font-bold text-primary-dark leading-snug group-hover:text-primary transition-colors">
                        {{ $post->title }}
                    </h2>
                    @if($post->excerpt)
                        <p class="text-sm text-primary-dark/60 line-clamp-2">{{ $post->excerpt }}</p>
                    @endif
                    <span class="text-xs text-primary-dark/40 mt-auto">
                        {{ $post->published_at->format('F j, Y') }} · {{ $post->reading_time }} min read
                    </span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-10">{{ $posts->links() }}</div>
    @endif


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::blog()
    ->name('Waggies Pet Care Blog')
    ->description('Expert pet care tips, advice, and stories from the Waggies team in Abuja, Nigeria.')
    ->url(url()->current())
    ->publisher(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->toScript();
@endphp
@endpush
</x-layouts.listing>
