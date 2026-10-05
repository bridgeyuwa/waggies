@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Tools', 'route' => 'tools.index'], ['label' => 'Behavior & Training Tips']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="behavior" title="Behavior &amp; Training Tips" description="Choose your pet and explore practical, non-diagnostic guidance for everyday behavior concerns." />
    <section class="bg-surface pb-20 md:pb-28">
        <div x-data="behaviorTips(@js($behaviorTips))" class="mx-auto max-w-4xl px-4 md:px-10">
            <div class="flex flex-col gap-4 border-b border-primary/10 pb-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-eyebrow text-primary/60">Choose a companion</p>
                    <div class="mt-3 flex flex-wrap gap-2" role="group" aria-label="Pet species">
                        <button type="button" @click="species = 'dog'" :aria-pressed="species === 'dog'" class="min-h-[44px] rounded-full px-5 text-sm font-semibold transition-[background-color,color,box-shadow] duration-180" :class="species === 'dog' ? 'bg-primary text-white shadow-sm' : 'bg-surface-purple text-primary hover:bg-primary/10'">Dog</button>
                        <button type="button" @click="species = 'cat'" :aria-pressed="species === 'cat'" class="min-h-[44px] rounded-full px-5 text-sm font-semibold transition-[background-color,color,box-shadow] duration-180" :class="species === 'cat' ? 'bg-primary text-white shadow-sm' : 'bg-surface-purple text-primary hover:bg-primary/10'">Cat</button>
                    </div>
                </div>
                <label class="w-full sm:max-w-xs">
                    <span class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.12em] text-primary-dark/55">Search tips</span>
                    <input x-model="query" type="text" placeholder="Search by concern" class="min-h-[44px] w-full rounded-md border border-primary/20 bg-white px-4 text-sm text-primary-dark placeholder:text-primary-dark/45 focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/35">
                </label>
            </div>

            <p class="mt-6 text-sm text-primary-dark/60" role="status">Showing <span x-text="filteredTips().length"></span> <span x-text="species"></span> tips. This is general educational guidance; sudden or concerning changes should be discussed with a veterinarian.</p>
            <div x-show="filteredTips().length === 0" x-cloak class="w-card mt-6 border-dashed border-primary/25 p-8 text-center">
                <p class="font-serif text-xl font-semibold text-primary-dark">No tips match that search.</p>
                <p class="mt-2 text-sm text-primary-dark/60">Try a broader term or clear the search.</p>
            </div>
            <div x-show="filteredTips().length > 0" class="mt-6 space-y-5">
                <template x-for="tip in filteredTips()" :key="tip.stableId">
                    <article class="w-card p-5 sm:p-6">
                        <div class="flex items-start justify-between gap-4">
                            <div><p class="text-xs font-semibold uppercase tracking-[0.12em] text-primary/55" x-text="tip.category"></p><h2 class="mt-1 font-serif text-xl font-bold text-primary-dark" x-text="tip.title"></h2></div>
                            <span x-show="tip.escalate" role="img" aria-label="Professional guidance may be needed" class="shrink-0 text-amber-700"><x-waggies.icon name="warning" size="21" /></span>
                        </div>
                        <p class="mt-3 text-sm leading-relaxed text-primary-dark/65" x-text="tip.description"></p>
                        <div class="mt-5 grid gap-5 md:grid-cols-[1fr_0.82fr]">
                            <div><h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.12em] text-primary/60"><x-waggies.icon name="contact" size="15" />Practical advice</h3><ul class="mt-3 space-y-2"><template x-for="advice in tip.practicalAdvice" :key="advice"><li class="flex items-start gap-2 text-sm leading-relaxed text-primary-dark/65"><x-waggies.icon name="safety" size="14" class="mt-1 shrink-0 text-primary/45" /><span x-text="advice"></span></li></template></ul></div>
                            <div class="rounded-xl p-4" :class="tip.escalate ? 'border border-amber-200/70 bg-amber-50' : 'border border-primary/10 bg-surface-purple'"><h3 class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.12em]" :class="tip.escalate ? 'text-amber-800' : 'text-primary/60'"><x-waggies.icon name="warning" size="15" />When to seek help</h3><p class="mt-2 text-sm leading-relaxed" :class="tip.escalate ? 'text-amber-950' : 'text-primary-dark/65'" x-text="tip.whenToSeekHelp"></p><a x-show="tip.escalate" href="{{ route('services.vet-care') }}" class="mt-3 inline-flex min-h-[44px] items-center text-sm font-semibold text-primary underline-offset-4 hover:underline">View vet care</a></div>
                        </div>
                    </article>
                </template>
            </div>
            <x-waggies.tool-cta tool-route="tools.behavior-tips" />
        </div>
    </section>
@endsection
