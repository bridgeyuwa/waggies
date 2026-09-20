@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'FAQ']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.cover-hero :hero="$hero" title-id="hero-image-title" :center="true" />

    <div x-data="faqPage(@js($faqs), @js($categories))" class="mx-auto max-w-5xl px-4 py-12 md:px-10 md:py-20">
        <div class="mx-auto mb-10 max-w-2xl">
            <div class="relative">
                <span class="pointer-events-none absolute left-5 top-1/2 -translate-y-1/2 text-primary-dark/60"><x-waggies.icon name="search" size="20" /></span>
                <input type="search" x-model="query" placeholder="Search questions..." aria-label="Search FAQs" class="h-14 w-full rounded-full border bg-white pl-14 pr-12 font-medium text-primary-dark shadow-soft transition placeholder:text-primary-dark/60 focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/40" :class="query.trim() ? 'border-primary/60' : 'border-primary/15'">
                <button x-show="query.trim()" x-transition type="button" @click="query = ''" aria-label="Clear search" class="absolute right-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full text-primary-dark/50 transition hover:bg-primary/10 hover:text-primary"><x-waggies.icon name="close" size="18" /></button>
            </div>
        </div>

        <div x-show="!query.trim()" x-transition class="mx-auto mb-10 max-w-3xl overflow-hidden">
            <div class="flex flex-wrap items-center gap-2">
                <span class="mr-1 text-xs font-bold uppercase tracking-widest text-primary/60">Popular:</span>
                <template x-for="item in popular" :key="item.id"><button type="button" @click="popularClick(item.id)" class="inline-flex min-h-[44px] items-center gap-1.5 rounded-full bg-surface-purple px-4 py-2.5 text-xs font-semibold text-primary-dark shadow-sm transition hover:bg-primary hover:text-white"><x-waggies.icon name="progress" size="14" /> <span x-text="item.label"></span></button></template>
            </div>
        </div>

        <div x-show="!query.trim()" x-transition class="mb-10 flex flex-wrap gap-2" role="tablist" @keydown="tabKeydown($event)">
            <button type="button" role="tab" @click="select('all')" :aria-selected="active === 'all'" class="min-h-[44px] rounded-full px-5 py-2.5 text-sm font-semibold transition" :class="active === 'all' ? 'bg-primary text-white shadow-sm' : 'bg-surface-purple text-primary-dark hover:bg-primary/10'">All</button>
            <template x-for="category in categories" :key="category"><button type="button" role="tab" @click="select(category)" :aria-selected="active === category" class="min-h-[44px] rounded-full px-5 py-2.5 text-sm font-semibold capitalize transition" :class="active === category ? 'bg-primary text-white shadow-sm' : 'bg-surface-purple text-primary-dark hover:bg-primary/10'" x-text="category.replace('-', ' ')"></button></template>
        </div>

        <div x-show="query.trim()" x-transition class="mx-auto max-w-3xl">
            <p x-show="results().length" class="mb-4 text-sm text-primary-dark/60">Showing <span class="font-semibold text-primary" x-text="results().length"></span> <span x-text="results().length === 1 ? 'result' : 'results'"></span> for <span class="font-semibold text-primary-dark" x-text="'“' + query.trim() + '”'"></span></p>
            <div x-show="!results().length" class="rounded-2xl border border-primary/10 bg-white px-6 py-16 text-center"><div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-surface-purple text-primary"><x-waggies.icon name="search" size="24" /></div><p class="mb-1 font-serif text-xl font-bold text-primary-dark">No results found</p><p class="mb-6 text-sm text-primary-dark/60">We couldn&apos;t find anything matching &ldquo;<span x-text="query.trim()"></span>&rdquo;. Try a different search or browse the categories.</p><button type="button" @click="query = ''" class="w-cta w-cta--primary"> <x-waggies.icon name="arrow-back" size="16" /> Browse all FAQs</button></div>
            <div class="space-y-3"><template x-for="faq in results()" :key="faq.id"><details :data-faq-id="faq.id" class="group scroll-mt-32 rounded-2xl border border-primary/10 bg-white transition hover:border-primary/30 hover:shadow-sm"><summary class="flex w-full cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark transition-colors hover:text-primary"><span x-html="highlight(faq.question)"></span><x-waggies.icon name="chevron-down" size="20" class="shrink-0 text-primary transition-transform duration-200 group-open:rotate-180" /></summary><div class="px-6 pb-5 pt-3 text-sm leading-relaxed text-primary-dark/65" x-html="highlight(faq.answer)"></div></details></template></div>
        </div>

        <div x-show="!query.trim()" x-transition>
            <template x-for="category in visibleCategories()" :key="category">
                <section role="tabpanel" class="mx-auto mb-10 max-w-3xl"><h3 x-show="active === 'all'" class="mb-4 font-serif text-lg font-bold capitalize text-primary-dark" x-text="category.replace('-', ' ')"></h3><div class="space-y-3"><template x-for="faq in faqs.filter(item => item.category === category)" :key="faq.id"><details :data-faq-id="faq.id" class="group scroll-mt-32 rounded-2xl border border-primary/10 bg-white transition hover:border-primary/30 hover:shadow-sm"><summary class="flex w-full cursor-pointer list-none items-center justify-between gap-4 px-6 py-5 font-semibold text-primary-dark transition-colors hover:text-primary"><span x-text="faq.question"></span><x-waggies.icon name="chevron-down" size="20" class="shrink-0 text-primary transition-transform duration-200 group-open:rotate-180" /></summary><div class="px-6 pb-5 pt-3 text-sm leading-relaxed text-primary-dark/65" x-text="faq.answer"></div></details></template></div></section>
            </template>
        </div>

        <div class="mt-14 text-center"><p class="mb-4 text-sm text-primary-dark/60">Still unsure? Talk to our care team before booking.</p><a href="{{ route('contact') }}" class="w-cta w-cta--primary">Contact Us <x-waggies.icon name="arrow-forward" size="16" /></a></div>
    </div>

@endsection
