<x-layouts.content :title="$guide->title" nav-section="blog">

    <x-slot:hero>
        <x-hero.image
            :image-src="$guide->hero_image_url"
            :image-alt="$guide->title"
            :eyebrow="$guide->category_label"
            eyebrow-icon="menu_book"
            :title="$guide->title"
            :subtitle="$guide->excerpt"
            min-height="420px"
        />
    </x-slot:hero>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-3">More Guides</h3>
            @if($related->isEmpty())
                <p class="text-sm text-primary-dark/50">No related guides yet.</p>
            @else
                <ul class="flex flex-col gap-3">
                    @foreach($related as $r)
                    <li>
                        <a href="{{ route('guides.show', $r->slug) }}"
                           class="text-sm text-primary-dark/70 hover:text-primary transition leading-snug block">
                            {{ $r->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </x-slot:sidebar>

    <article>
        <p class="text-sm text-primary-dark/40 mb-8">
            By {{ $guide->author }}
            · {{ $guide->published_at->format('F j, Y') }}
            · {{ $guide->reading_time }} min read
        </p>

        <x-ui.markdown :content="$guide->body" />
    </article>


@push('head')
@php
$_schema = \Spatie\SchemaOrg\Schema::article()
    ->headline($guide->title)
    ->description($guide->excerpt ?? '')
    ->author(\Spatie\SchemaOrg\Schema::person()->name($guide->author))
    ->datePublished($guide->published_at->toIso8601String())
    ->url(url()->current())
    ->publisher(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')));
if ($guide->image_url) { $_schema->image($guide->image_url); }
echo $_schema->toScript();
@endphp
@endpush
</x-layouts.content>
