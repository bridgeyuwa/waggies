{{--
    FAQ accordion (B10). Uses native <details>/<summary> — no JS required.
    The wag-sweep animation fires via CSS on details[open].

    Props:
      $faqs — array of ['question' => string, 'answer' => string (HTML allowed)]

    Or use the slot for full HTML control over a single item.
--}}
@props([
    'faqs' => [],
])

<div class="divide-y divide-surface-purple">
    @forelse($faqs as $faq)
        <details class="group">
            <summary class="flex cursor-pointer items-center justify-between py-5
                           text-base font-semibold text-primary-dark hover:text-primary
                           transition-colors list-none [&::-webkit-details-marker]:hidden">
                {{ $faq['question'] }}
                <span class="material-symbols-outlined text-primary shrink-0
                             transition-transform duration-200 group-open:rotate-180"
                      aria-hidden="true">expand_more</span>
            </summary>
            <div class="pb-5 text-primary-dark/70 leading-relaxed text-sm">
                {!! $faq['answer'] !!}
            </div>
        </details>
    @empty
        {{ $slot }}
    @endforelse
</div>
