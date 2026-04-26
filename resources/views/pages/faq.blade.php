<x-layouts.utility title="Frequently Asked Questions" nav-section="">

    <x-section-heading eyebrow="FAQs" title="Frequently Asked Questions"
        subtitle="Quick answers to the questions we hear most often. Can't find what you need? Contact us directly."
        class="mb-10" />

    @if ($categories->isEmpty())

        <p class="text-primary-dark/50">No FAQs published yet — check back soon.</p>
    @else
        <div x-data="{ active: '{{ $categories->first()['slug'] }}' }">

            {{-- Tabs --}}
            <div class="flex flex-wrap gap-2 mb-10" role="tablist">

                @foreach ($categories as $cat)
                    <button type="button" role="tab" @click="active = '{{ $cat['slug'] }}'"
                        :aria-selected="active === '{{ $cat['slug'] }}'"
                        :class="active === '{{ $cat['slug'] }}'
                            ?
                            'bg-primary text-white shadow-glow' :
                            'bg-surface-purple text-primary-dark hover:bg-primary/10'"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition">
                        {{ $cat['label'] }}
                    </button>
                @endforeach

            </div>

            {{-- FAQ panels --}}
            @foreach ($grouped as $slug => $faqs)
                <div x-show="active === '{{ $slug }}'" x-transition.opacity role="tabpanel"
                    class="max-w-3xl mx-auto">

                    {{-- BOXED ACCORDION STYLE (UPGRADED) --}}
                    <div class="space-y-3">

                        @foreach ($faqs as $faq)
                            <details
                                class="group bg-white border border-primary/10 rounded-2xl px-6 py-5 hover:border-primary/30 hover:shadow-sm transition">

                                <summary
                                    class="flex cursor-pointer items-center justify-between font-semibold text-primary-dark hover:text-primary transition-colors list-none [&::-webkit-details-marker]:hidden">

                                    {{ $faq->question }}

                                    <span
                                        class="material-symbols-outlined text-primary shrink-0 transition-transform duration-200 group-open:rotate-180">
                                        expand_more
                                    </span>

                                </summary>

                                <div class="mt-3 text-sm text-primary-dark/65 leading-relaxed">
                                    {{ $faq->answer }}
                                </div>

                            </details>
                        @endforeach

                    </div>

                </div>
            @endforeach

        </div>

    @endif

    {{-- CTA (clean conversion block, not decorative) --}}
    <div class="mt-14 text-center">

        <p class="text-sm text-primary-dark/60 mb-4">
            Still unsure? Talk to our care team before booking.
        </p>

        <a href="{{ route('contact') }}"
            class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-7 py-3 rounded-full font-bold text-sm transition shadow-glow hover:-translate-y-1">

            Contact Us

            <span class="material-symbols-outlined text-base">arrow_forward</span>

        </a>

    </div>

    {{-- SEO (unchanged but cleaner) --}}
    @push('head')
        @php
            $schemaItems = $grouped
                ->flatten()
                ->map(
                    fn($f) => \Spatie\SchemaOrg\Schema::question()
                        ->name($f->question)
                        ->acceptedAnswer(\Spatie\SchemaOrg\Schema::answer()->text($f->answer)),
                )
                ->values()
                ->all();

            echo \Spatie\SchemaOrg\Schema::fAQPage()->mainEntity($schemaItems)->toScript();
        @endphp
    @endpush

</x-layouts.utility>
