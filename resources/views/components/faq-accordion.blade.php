@props([
    'faqs' => [],
])

<div class="max-w-3xl mx-auto space-y-3">

    @forelse($faqs as $faq)
        <details
            class="group bg-white border border-primary/10 rounded-2xl px-6 py-5
                   hover:border-primary/30 hover:shadow-sm transition">

            <summary
                class="flex cursor-pointer items-center justify-between
                       font-semibold text-primary-dark hover:text-primary
                       transition-colors list-none [&::-webkit-details-marker]:hidden">

                {{ $faq['question'] }}

                <span
                    class="material-symbols-outlined text-primary shrink-0
                           transition-transform duration-200 group-open:rotate-180"
                    aria-hidden="true">
                    expand_more
                </span>
            </summary>

            <div class="mt-3 text-sm text-primary-dark/60 leading-relaxed">
                {!! $faq['answer'] !!}
            </div>

        </details>
    @empty
        {{ $slot }}
    @endforelse

</div>
