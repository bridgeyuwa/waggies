@extends('layouts.app')
@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Tools', 'route' => 'tools.index'], ['label' => 'Pet Age Calculator']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="cake" title="Pet Age Calculator" description="Find out how old your pet is in human years. Dogs and cats age differently depending on their size." />
    <section class="bg-surface pb-20 md:pb-28">
        <div x-data="petAgeCalculator" class="mx-auto max-w-2xl px-4 md:px-10">
            <div class="flex flex-col gap-8">
                <div class="rounded-2xl border border-primary/10 bg-white p-6">
                    <h3 class="mb-5 font-serif text-lg font-bold text-primary-dark">Pet Type</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="changePetType('dog')" :aria-pressed="petType === 'dog'" class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl border-2 p-4 font-semibold transition-colors" :class="petType === 'dog' ? 'border-primary bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/60 hover:border-primary/30'"><x-waggies.icon name="pets" size="24" />Dog</button>
                        <button type="button" @click="changePetType('cat')" :aria-pressed="petType === 'cat'" class="flex min-h-[44px] items-center justify-center gap-2 rounded-xl border-2 p-4 font-semibold transition-colors" :class="petType === 'cat' ? 'border-primary bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/60 hover:border-primary/30'"><x-waggies.icon name="cat" size="24" />Cat</button>
                    </div>
                </div>

                <div x-show="petType === 'dog'" class="rounded-2xl border border-primary/10 bg-white p-6">
                    <h3 class="mb-1 font-serif text-lg font-bold text-primary-dark">Dog Size</h3>
                    <p class="mb-5 text-xs text-primary-dark/50">Size affects aging rate and lifespan. These categories are Waggies product-level definitions, not universal veterinary standards.</p>
                    <div class="grid grid-cols-2 gap-3">
                        <template x-for="size in dogSizes" :key="size.id">
                            <button type="button" @click="dogSize = size.id" :aria-pressed="dogSize === size.id" class="flex min-h-[44px] flex-col items-start gap-1 rounded-xl border-2 p-4 text-left transition-colors" :class="dogSize === size.id ? 'border-primary bg-surface-purple' : 'border-primary/10 bg-white hover:border-primary/30'"><span class="font-semibold" :class="dogSize === size.id ? 'text-primary' : 'text-primary-dark/80'" x-text="size.label"></span><span class="text-xs font-medium text-primary-dark/60" x-text="size.kgRange + ' (' + size.lbRange + ')' "></span><span class="text-xs text-primary-dark/50" x-text="size.description"></span></button>
                        </template>
                    </div>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6">
                    <h3 class="mb-5 font-serif text-lg font-bold text-primary-dark">Your Pet&rsquo;s Age</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="block"><span class="mb-2 block text-sm font-medium text-primary-dark/80">Years</span><select x-model.number="years" id="pet-age-years" class="min-h-[44px] w-full rounded-md border border-primary/20 bg-white px-4 py-3 text-sm font-medium text-primary-dark focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40"><template x-for="year in yearsOptions()" :key="year"><option :value="year" x-text="year"></option></template></select></label>
                        <label class="block"><span class="mb-2 block text-sm font-medium text-primary-dark/80">Months</span><select x-model.number="months" id="pet-age-months" class="min-h-[44px] w-full rounded-md border border-primary/20 bg-white px-4 py-3 text-sm font-medium text-primary-dark focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40"><template x-for="month in monthsOptions()" :key="month"><option :value="month" x-text="month"></option></template></select></label>
                    </div>
                </div>

                <div class="rounded-2xl border-2 border-primary/20 bg-white p-8 text-center">
                    <div x-show="isDefault()" class="py-6">
                        <div class="mb-4 flex justify-center"><x-waggies.icon name="pets" size="36" class="text-primary/40" /></div>
                        <p class="mb-2 font-serif text-xl font-bold text-primary-dark/60">Enter your pet&apos;s age to see an estimated human-equivalent.</p>
                        <p class="text-xs text-primary-dark/40">Select years and months above to calculate.</p>
                    </div>
                    <div x-show="!isDefault()" x-cloak>
                        <div class="mb-6">
                            <p class="mb-2 text-sm font-bold uppercase tracking-wider text-primary-dark/50">Estimated human-equivalent age</p>
                            <p class="mb-2 font-serif text-5xl font-bold text-primary-dark md:text-6xl"><span x-text="'~' + humanAge()"></span><span class="ml-1 text-2xl font-normal text-primary-dark/60 md:text-3xl">years</span></p>
                        </div>
                        <div class="mb-5 border-b border-primary/10 pb-5">
                            <p class="mb-2 text-sm font-bold uppercase tracking-wider text-primary-dark/50">Approximate life stage</p>
                            <span class="inline-block rounded-full px-4 py-2 text-sm font-bold" :class="lifeStage().color" x-text="lifeStage().label"></span>
                            <p class="mx-auto mt-3 max-w-sm text-xs leading-relaxed text-primary-dark/60" x-text="lifeStage().description"></p>
                        </div>
                        <div class="space-y-2">
                            <p class="mx-auto max-w-md text-xs leading-relaxed text-primary-dark/60">The human-equivalent age is an educational estimate and varies by size, breed, individual health and life stage. It is not a medically exact conversion.</p>
                            <p class="mx-auto max-w-md text-xs leading-relaxed text-primary-dark/50">Life stages vary by breed, size, lifestyle and individual health. This tool provides an educational estimate and is not a veterinary diagnosis.</p>
                        </div>
                        <div class="mt-4 text-xs text-primary-dark/50"><p x-show="petType === 'dog'">Size: <span x-text="dogSizes.find((size) => size.id === dogSize)?.label"></span> &middot; Age: <span x-text="years"></span>y <span x-text="months"></span>m</p><p x-show="petType === 'cat'">Age: <span x-text="years"></span>y <span x-text="months"></span>m</p></div>
                    </div>
                </div>
            </div>
            <x-waggies.tool-cta tool-route="tools.pet-age" />
        </div>
    </section>
@endsection
