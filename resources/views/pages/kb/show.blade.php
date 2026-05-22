<x-layouts.content :title="$article->title" nav-section="blog">

    <x-slot:hero>
        <x-hero.image
            :image-src="config('waggies.hero_images.kb')"
            :eyebrow="$article->category_label"
            eyebrow-icon="help"
            :title="$article->title"
            :subtitle="\Illuminate\Support\Str::limit(strip_tags($article->body), 160)"
            min-height="400px"
        />
    </x-slot:hero>

    <x-slot:sidebar>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-3">More in this topic</h3>
            @if($related->isEmpty())
                <p class="text-sm text-primary-dark/50">No related articles yet.</p>
            @else
                <ul class="flex flex-col gap-3">
                    @foreach($related as $r)
                    <li>
                        <a href="{{ route('kb.show', $r->slug) }}"
                           class="text-sm text-primary-dark/70 hover:text-primary transition leading-snug block">
                            {{ $r->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="bg-surface-purple rounded-2xl p-6">
            <h3 class="font-semibold text-primary-dark mb-2">Still need help?</h3>
            <p class="text-sm text-primary-dark/60 mb-3">Our team is happy to help with any questions.</p>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2.5 rounded-full text-sm font-semibold hover:bg-primary-dark transition">
                Contact Us
            </a>
        </div>
    </x-slot:sidebar>

    <article>
        <x-ui.markdown :content="$article->body" />
    </article>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::article()
    ->headline($article->title)
    ->description(\Illuminate\Support\Str::limit(strip_tags($article->body ?? ''), 160))
    ->url(url()->current())
    ->publisher(\Spatie\SchemaOrg\Schema::organization()->name('Waggies')->url(url('/')))
    ->toScript();
@endphp
@endpush
</x-layouts.content>
