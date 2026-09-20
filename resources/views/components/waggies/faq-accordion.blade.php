@props(['faqs' => []])
<div class="mx-auto max-w-3xl space-y-3">
@foreach($faqs as $faq)<details class="group rounded-2xl border border-primary/10 bg-white transition hover:border-primary/30 hover:shadow-sm"><summary class="flex w-full cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark hover:text-primary"><span>{{ $faq['question'] }}</span><x-waggies.icon name="chevron-down" size="20" class="shrink-0 text-primary transition-transform duration-200 group-open:rotate-180" /></summary><div class="px-6 pb-5 pt-3 text-sm leading-relaxed text-primary-dark/60">{{ $faq['answer'] }}</div></details>@endforeach
</div>
