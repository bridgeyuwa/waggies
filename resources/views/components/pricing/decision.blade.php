{{-- Decision step after pricing result — rendered inside pricingCalculator Alpine scope --}}
<div id="pricing-decision" class="bg-white rounded-2xl border-2 border-primary shadow-soft p-6 md:p-8">
    <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2">Your estimate</p>
    <p class="text-sm text-primary-dark/60 mb-1" x-text="result?.serviceLabel"></p>
    <p class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-2" x-html="result?.display"></p>
    <p class="text-sm text-primary-dark/50 mb-8" x-text="result?.tierLabel"></p>

    <div class="flex flex-col sm:flex-row flex-wrap gap-3">
        <a :href="contactUrl(result?.primaryIntent || 'book')"
            class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark text-white
                   px-6 py-3.5 rounded-full font-bold transition shadow-glow hover:-translate-y-1 flex-1 min-w-[140px]"
            x-text="result?.primaryCta || 'Book Now'">
        </a>
        <a :href="contactUrl('save')"
            class="inline-flex items-center justify-center gap-2 border-2 border-primary text-primary hover:bg-primary hover:text-white
                   px-6 py-3.5 rounded-full font-semibold transition flex-1 min-w-[140px]">
            Save Estimate
        </a>
        <a :href="contactUrl('consult')"
            class="inline-flex items-center justify-center gap-2 bg-surface-purple text-primary-dark hover:bg-primary/10
                   px-6 py-3.5 rounded-full font-semibold transition flex-1 min-w-[140px]">
            Talk to Expert
        </a>
    </div>

    <button type="button" @click="reset()"
        class="mt-6 text-sm text-primary font-medium hover:underline inline-flex items-center gap-1">
        <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_back</span>
        Start over
    </button>
</div>
