@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Tools', 'route' => 'tools.index'], ['label' => 'Breed Info Finder']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="search" title="Breed Info Finder" description="Browse popular dog and cat breeds with general information on size, energy, grooming, and temperament." />
    <section class="bg-surface pb-20 md:pb-28">
        <div x-data="breedFinder(@js($breeds))" class="mx-auto max-w-6xl px-4 md:px-10">
            <div class="space-y-6">
                <div class="w-card space-y-5 p-5 sm:p-6">
                    <fieldset>
                        <legend class="mb-2 block text-sm font-semibold text-primary-dark">Species</legend>
                        <div class="grid grid-cols-2 gap-3" role="radiogroup" aria-label="Species">
                            <template x-for="option in ['dog', 'cat']" :key="option">
                                <button type="button" role="radio" :aria-checked="species === option" @click="changeSpecies(option)" class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl border px-3 py-2.5 text-sm font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40" :class="species === option ? 'border-primary bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/60 hover:border-primary/30'">
                                    <template x-if="option === 'dog'"><x-waggies.icon name="pets" size="18" variant="filled" /></template>
                                    <template x-if="option === 'cat'"><x-waggies.icon name="cat" size="18" variant="filled" /></template>
                                    <span x-text="option === 'dog' ? 'Dogs' : 'Cats'"></span>
                                </button>
                            </template>
                        </div>
                    </fieldset>

                    <div>
                        <label for="breed-search" class="mb-2 block text-sm font-semibold text-primary-dark">Search Breeds</label>
                        <div class="relative">
                            <x-waggies.icon name="search" size="18" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-primary-dark/40" />
                            <x-waggies.input id="breed-search" type="search" autocomplete="off" x-model="search" placeholder="e.g. Labrador, Friendly, Active…" class="min-h-11! rounded-xl! border-primary/10! bg-white! py-2.5! pl-10! pr-4! text-sm placeholder:text-primary-dark/30! focus:outline-none! focus:ring-primary/40!" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                        <x-waggies.select id="filter-size" label="Size" x-model="size" class="mt-2 w-full rounded-md border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium text-primary-dark focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40"><option value="">All sizes</option><template x-for="option in sizeOptions()" :key="option"><option :value="option" x-text="sizeLabel(option)"></option></template></x-waggies.select>
                        <x-waggies.select id="filter-exercise" label="Exercise Needs" x-model="exerciseNeeds" class="mt-2 w-full rounded-md border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium text-primary-dark focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40"><option value="">All levels</option><template x-for="option in exerciseOptions()" :key="option"><option :value="option" x-text="exerciseLabel(option)"></option></template></x-waggies.select>
                        <x-waggies.select id="filter-grooming" label="Grooming Needs" x-model="groomingNeeds" class="mt-2 w-full rounded-md border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium text-primary-dark focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40"><option value="">All levels</option><template x-for="option in groomingOptions()" :key="option"><option :value="option" x-text="groomingLabel(option)"></option></template></x-waggies.select>
                    </div>

                    <button type="button" x-show="hasActiveFilters()" x-cloak @click="clearFilters()" class="inline-flex min-h-[44px] items-center gap-1.5 rounded-md px-1 text-xs font-semibold text-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40 hover:underline">
                        <x-waggies.icon name="refresh" size="14" aria-hidden="true" /> Clear filters
                    </button>
                </div>

                <p class="text-xs text-primary-dark/50" aria-live="polite">Showing <span class="font-semibold text-primary-dark/70" x-text="filteredBreeds().length"></span> of <span class="font-semibold text-primary-dark/70" x-text="speciesTotal()"></span> <span x-text="species === 'dog' ? 'dog' : 'cat'"></span> breed<span x-show="speciesTotal() !== 1">s</span></p>

                <div x-show="filteredBreeds().length === 0" x-cloak class="w-card py-12 text-center">
                    <x-waggies.icon name="pets" size="32" class="mx-auto mb-3 text-primary-dark/20" />
                    <p class="text-sm text-primary-dark/60">No breeds match your filters. Try adjusting your search criteria.</p>
                </div>

                <div x-show="filteredBreeds().length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <template x-for="breed in filteredBreeds()" :key="breed.id">
                        <article class="w-card flex flex-col gap-3 p-5">
                            <header>
                                <h3 class="font-serif text-base font-bold text-primary-dark sm:text-lg" x-text="breed.name"></h3>
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    <span class="rounded-full border border-primary/20 bg-primary/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-primary" x-text="sizeLabel(breed.size)"></span>
                                    <span class="rounded-full border border-emerald-200/60 bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-emerald-700" x-text="'Exercise: ' + exerciseLabel(breed.exerciseNeeds)"></span>
                                    <span class="rounded-full border border-amber-200/60 bg-amber-50 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-amber-700" x-text="'Grooming: ' + groomingLabel(breed.groomingNeeds)"></span>
                                </div>
                            </header>
                            <p class="text-sm leading-relaxed text-primary-dark/60" x-text="breed.overview"></p>
                            <div>
                                <h3 class="mb-1.5 text-[10px] font-bold uppercase tracking-wider text-primary/50">Temperament</h3>
                                <div class="flex flex-wrap gap-1.5"><template x-for="temperament in breed.temperament" :key="temperament"><span class="rounded-md border border-primary/10 bg-surface-purple/60 px-2 py-0.5 text-[11px] font-medium text-primary-dark/70" x-text="temperament"></span></template></div>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div class="rounded-lg border border-primary/5 bg-surface px-3 py-2"><div class="text-[10px] font-semibold uppercase tracking-wider text-primary/50">Weight</div><div class="mt-0.5 text-sm font-semibold text-primary-dark" x-text="breed.weightRange"></div></div>
                                <div class="rounded-lg border border-primary/5 bg-surface px-3 py-2"><div class="text-[10px] font-semibold uppercase tracking-wider text-primary/50">Lifespan</div><div class="mt-0.5 text-sm font-semibold text-primary-dark" x-text="breed.lifespan"></div></div>
                            </div>
                            <details class="group mt-1">
                                <summary class="flex min-h-[44px] cursor-pointer list-none items-center justify-between gap-2 rounded-md px-1 py-1 select-none transition hover:bg-surface-purple/40 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary/40">
                                    <span class="flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider text-primary/60"><x-waggies.icon name="veterinary-care" size="14" />Health Considerations</span><x-waggies.icon name="chevron-down" size="16" class="text-primary/50 transition-transform group-open:rotate-180" />
                                </summary>
                                <p class="mt-2 pl-1 text-sm leading-relaxed text-primary-dark/60" x-text="breed.generalHealthConsiderations"></p>
                            </details>
                        </article>
                    </template>
                </div>

                <x-waggies.medical-disclaimer />
            </div>
            <x-waggies.tool-cta tool-route="tools.breed-finder" />
        </div>
    </section>
@endsection
