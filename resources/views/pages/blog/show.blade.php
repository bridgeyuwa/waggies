<x-layouts.content :title="$post->title" nav-section="blog">

    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Blog', 'href' => route('blog.index')],
            ['label' => $post->category_label, 'href' => route('blog.category', $post->category)],
            ['label' => $post->title],
        ]" />
    </x-slot:breadcrumb>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-3">More Articles</h3>
            @if($related->isEmpty())
                <p class="text-sm text-primary-dark/50">No related articles yet.</p>
            @else
                <ul class="flex flex-col gap-3">
                    @foreach($related as $r)
                    <li>
                        <a href="{{ route('blog.show', $r->slug) }}"
                           class="text-sm text-primary-dark/70 hover:text-primary transition leading-snug block">
                            {{ $r->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="bg-primary rounded-2xl p-6">
            <h3 class="font-semibold text-white mb-2">Get Care Tips</h3>
            <p class="text-white/70 text-sm mb-4">Subscribe for expert pet care advice from the Waggies team.</p>
            <x-newsletter title="" subtitle="" button-label="Subscribe" />
        </div>
    </x-slot:sidebar>

    <article>
        <header class="mb-8">
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3 capitalize">
                {{ str_replace('-', ' ', $post->category) }}
            </span>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-3">
                {{ $post->title }}
            </h1>
            <p class="text-sm text-primary-dark/40">
                By {{ $post->author }}
                · {{ $post->published_at->format('F j, Y') }}
                · {{ $post->reading_time }} min read
            </p>
        </header>

        @if($post->image_url)
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}"
                 class="w-full h-64 object-cover rounded-2xl mb-8" />
        @else
            <div class="w-full h-64 bg-surface-purple rounded-2xl mb-8"></div>
        @endif

        @if($post->excerpt)
            <p class="text-lg text-primary-dark/70 leading-relaxed font-medium mb-6 italic">{{ $post->excerpt }}</p>
        @endif

        <x-ui.markdown :content="$post->body" />
    </article>


@push('head')
@php
$_schema = \Spatie\SchemaOrg\Schema::blogPosting()
    ->headline($post->title)
    ->description($post->excerpt ?? '')
    ->author(\Spatie\SchemaOrg\Schema::person()->name($post->author))
    ->datePublished($post->published_at->toIso8601String())
    ->url(url()->current())
    ->publisher(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')));
if ($post->image_url) { $_schema->image($post->image_url); }
echo $_schema->toScript();
@endphp
@endpush
</x-layouts.content>
