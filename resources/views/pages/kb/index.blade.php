<x-layouts.listing title="Knowledge Base" nav-section="blog">

    <x-slot:header>
        <span class="text-xs font-bold uppercase tracking-widest text-primary/60 block mb-3">Knowledge Base</span>
        <h1 class="font-serif text-4xl md:text-5xl font-bold text-primary-dark mb-3">How Can We Help?</h1>
        <p class="text-primary-dark/60 max-w-xl">Quick answers to common questions about our services, policies, and pet care.</p>
    </x-slot:header>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-4">Topics</h3>
            <ul class="flex flex-col gap-2">
                <li><a href="{{ route('kb.index') }}" class="text-sm font-semibold text-primary">All Topics</a></li>
                @foreach($categories as $cat)
                <li>
                    <a href="{{ route('kb.category', $cat) }}" class="text-sm text-primary-dark/70 hover:text-primary transition capitalize">
                        {{ str_replace('-', ' ', $cat) }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-2">Still need help?</h3>
            <p class="text-sm text-primary-dark/60 mb-4">Can't find what you're looking for? Our team is happy to help.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-full text-sm font-semibold hover:bg-primary-dark transition">
                Contact Us
            </a>
        </div>
    </x-slot:sidebar>

    @if($featured->isNotEmpty())
        <div class="mb-8">
            <h2 class="font-serif text-xl font-bold text-primary-dark mb-4">Featured Articles</h2>
            <div class="flex flex-col gap-4">
                @foreach($featured as $article)
                <a href="{{ route('kb.show', $article->slug) }}" class="group flex items-center justify-between gap-4 bg-white rounded-2xl px-6 py-5 shadow-sm hover:shadow-soft transition-shadow">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-widest text-primary/50 block mb-1 capitalize">{{ str_replace('-', ' ', $article->category) }}</span>
                        <h3 class="font-semibold text-primary-dark group-hover:text-primary transition-colors">{{ $article->title }}</h3>
                    </div>
                    <span class="material-symbols-outlined text-primary-dark/30 group-hover:text-primary transition-colors shrink-0">chevron_right</span>
                </a>
                @endforeach
            </div>
        </div>
    @endif

    @if($articles->isEmpty())
        <p class="text-primary-dark/50 text-sm">No articles published yet — check back soon.</p>
    @else
        <h2 class="font-serif text-xl font-bold text-primary-dark mb-4">All Articles</h2>
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
    ->name('Knowledge Base — Waggies Pet Care Help')
    ->description('Quick answers to common questions about Waggies services, policies, and pet care in Abuja.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.listing>
