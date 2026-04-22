<x-layouts.utility title="Frequently Asked Questions" nav-section="">

    <x-section-heading
        eyebrow="FAQs"
        title="Frequently Asked Questions"
        subtitle="Quick answers to the questions we hear most often. Can't find what you need? Contact us directly."
        class="mb-10"
    />

    @if($categories->isEmpty())
        <p class="text-primary-dark/50">No FAQs published yet — check back soon.</p>
    @else
        <div x-data="{ active: '{{ $categories->first()['slug'] }}' }">

            {{-- Category tabs --}}
            <div class="flex flex-wrap gap-2 mb-8" role="tablist" aria-label="FAQ categories">
                @foreach($categories as $cat)
                <button
                    type="button"
                    role="tab"
                    :aria-selected="active === '{{ $cat['slug'] }}'"
                    @click="active = '{{ $cat['slug'] }}'"
                    :class="active === '{{ $cat['slug'] }}'
                        ? 'bg-primary text-white shadow-glow'
                        : 'bg-surface-purple text-primary-dark hover:bg-primary/10'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition">
                    {{ $cat['label'] }}
                </button>
                @endforeach
            </div>

            {{-- FAQ sections --}}
            @foreach($grouped as $slug => $faqs)
            <div
                x-show="active === '{{ $slug }}'"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                role="tabpanel"
                class="mb-10">

                <x-faq-accordion :faqs="$faqs->map(fn($f) => ['question' => $f->question, 'answer' => e($f->answer)])->all()" />

            </div>
            @endforeach

        </div>
    @endif

    <div class="mt-12 p-6 bg-surface-purple rounded-2xl">
        <h3 class="font-serif text-lg font-bold text-primary-dark mb-2">Still have a question?</h3>
        <p class="text-sm text-primary-dark/60 mb-4">Get in touch and we'll get back to you as quickly as possible.</p>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold text-sm transition shadow-glow hover:-translate-y-1">
            Contact Us <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>


@push('head')
@php
$schemaItems = $grouped->flatten()->map(fn ($f) =>
    \Spatie\SchemaOrg\Schema::question()
        ->name($f->question)
        ->acceptedAnswer(\Spatie\SchemaOrg\Schema::answer()->text($f->answer))
)->values()->all();
echo \Spatie\SchemaOrg\Schema::fAQPage()->mainEntity($schemaItems)->toScript();
@endphp
@endpush
</x-layouts.utility>
