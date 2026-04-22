<x-layouts.content :title="$guide->title" nav-section="blog">

    <x-slot:breadcrumb>
        <x-breadcrumb :items="[
            ['label' => 'Guides', 'href' => route('guides.index')],
            ['label' => $guide->category_label, 'href' => route('guides.category', $guide->category)],
            ['label' => $guide->title],
        ]" />
    </x-slot:breadcrumb>

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
        <header class="mb-8">
            <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3 capitalize">
                {{ str_replace('-', ' ', $guide->category) }}
            </span>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-3">
                {{ $guide->title }}
            </h1>
            <p class="text-sm text-primary-dark/40">
                By {{ $guide->author }}
                · {{ $guide->published_at->format('F j, Y') }}
                · {{ $guide->reading_time }} min read
            </p>
        </header>

        @if($guide->image)
            <img src="{{ $guide->image }}" alt="{{ $guide->title }}"
                 class="w-full h-64 object-cover rounded-2xl mb-8" />
        @else
            <div class="w-full h-64 bg-surface-purple rounded-2xl mb-8"></div>
        @endif

        @if($guide->excerpt)
            <p class="text-lg text-primary-dark/70 leading-relaxed font-medium mb-6 italic">{{ $guide->excerpt }}</p>
        @endif

        <div class="prose max-w-none">
            {!! nl2br(e($guide->body)) !!}
        </div>
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
if ($guide->image) { $_schema->image($guide->image); }
echo $_schema->toScript();
@endphp
@endpush
</x-layouts.content>
