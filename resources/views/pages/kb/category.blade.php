<x-layouts.listing title="{{ ucwords(str_replace('-', ' ', $slug)) }}" nav-section="blog">

    <x-slot:hero>
        <x-hero.hub
            type="kb"
            eyebrow="Knowledge Base · Topic"
            :title="ucwords(str_replace('-', ' ', $slug))"
            min-height="420px"
        />
    </x-slot:hero>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-4">Topics</h3>
            <ul class="flex flex-col gap-2">
                <li><a href="{{ route('kb.index') }}" class="text-sm text-primary-dark/70 hover:text-primary transition">All Topics</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('kb.category', $cat) }}" class="text-sm transition capitalize {{ $cat === $slug ? 'text-primary font-semibold' : 'text-primary-dark/70 hover:text-primary' }}">
                        {{ str_replace('-', ' ', $cat) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </x-slot:sidebar>

    @if($articles->isEmpty())
        <p class="text-primary-dark/50 text-sm">No articles in this topic yet — check back soon.</p>
    @else
        <div class="flex flex-col gap-4">
            @foreach($articles as $article)
            <a href="{{ route('kb.show', $article->slug) }}" class="group flex items-center justify-between gap-4 bg-white rounded-2xl px-6 py-5 shadow-sm hover:shadow-soft transition-shadow">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-primary/50 block mb-1 capitalize">{{ str_replace('-', ' ', $article->category) }}</span>
                    <h2 class="font-semibold text-primary-dark group-hover:text-primary transition-colors">{{ $article->title }}</h2>
                </div>
                <span class="material-symbols-outlined text-primary-dark/30 group-hover:text-primary transition-colors shrink-0">chevron_right</span>
            </a>
            @endforeach
        </div>
        <div class="mt-10">{{ $articles->links() }}</div>
    @endif


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::collectionPage()
    ->name(ucwords(str_replace('-', ' ', $slug)) . ' — Waggies Knowledge Base')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.listing>
