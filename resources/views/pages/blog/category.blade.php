<x-layouts.listing title="{{ ucwords(str_replace('-', ' ', $slug)) }}" nav-section="blog">

    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Blog', 'href' => route('blog.index')],
            ['label' => ucwords(str_replace('-', ' ', $slug))],
        ]" />
    </x-slot:breadcrumb>

    <x-slot:header>
        <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Category</span>
        <h1 class="font-serif text-4xl font-bold text-primary-dark capitalize">
            {{ str_replace('-', ' ', $slug) }}
        </h1>
    </x-slot:header>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-4">Categories</h3>
            <ul class="flex flex-col gap-2">
                <li><a href="{{ route('blog.index') }}" class="text-sm text-primary-dark/70 hover:text-primary transition">All Posts</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('blog.category', $cat) }}"
                       class="text-sm transition capitalize {{ $cat === $slug ? 'text-primary font-semibold' : 'text-primary-dark/70 hover:text-primary' }}">
                        {{ str_replace('-', ' ', $cat) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </x-slot:sidebar>

    @if($posts->isEmpty())
        <p class="text-primary-dark/50 text-sm">No posts in this category yet — check back soon.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($posts as $post)
            <a href="{{ route('blog.show', $post->slug) }}"
               class="group bg-white rounded-2xl shadow-sm hover:shadow-soft transition-shadow overflow-hidden flex flex-col">
                <div class="h-44 bg-surface-purple group-hover:bg-primary/10 transition-colors"></div>
                <div class="p-5 flex flex-col gap-2 flex-1">
                    <h2 class="font-serif text-lg font-bold text-primary-dark leading-snug group-hover:text-primary transition-colors">
                        {{ $post->title }}
                    </h2>
                    <span class="text-xs text-primary-dark/40 mt-auto">{{ $post->published_at->format('F j, Y') }}</span>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $posts->links() }}</div>
    @endif


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::collectionPage()
    ->name(ucwords(str_replace('-', ' ', $slug)) . ' — Waggies Blog')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.listing>
