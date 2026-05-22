{{--
    Single pricing calculator — context-aware via query params or standalone service selection.
    Outputs: fixed (Book Now) | estimate (Proceed) | quote (Request Consultation)
    Decision step: Book Now | Save Estimate | Talk to Expert
--}}
@props([
    'calculator' => [],
])

@php
    $payload = $calculator ?: \App\Support\PricingQuote::calculatorPayload();
@endphp

<section id="pricing-calculator" class="py-16 bg-surface-purple scroll-mt-24"
    x-data="pricingCalculator(@js($payload), @js(route('contact')))">
    <div class="max-w-3xl mx-auto px-4 md:px-10 lg:px-12">
        <x-section-heading
            align="center"
            eyebrow="Pricing Tool"
            title="Get Your Estimate"
            subtitle="Select your service and package — we'll show a clear price or quote range and next steps."
        />

        <div class="mt-10 bg-white rounded-2xl border border-surface-purple shadow-soft p-6 md:p-8"
            x-show="step === 'form'" x-cloak>
            <div class="flex flex-col gap-5">
                <div x-show="!lockedService">
                    <label for="calc-service" class="block text-sm font-semibold text-primary-dark mb-2">Service</label>
                    <select id="calc-service" x-model="service" @change="onServiceChange()"
                        class="w-full rounded-xl border border-surface-purple px-4 py-3 text-primary-dark focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <option value="">Choose a service…</option>
                        <template x-for="(svc, key) in services" :key="key">
                            <option :value="key" x-text="svc.label"></option>
                        </template>
                    </select>
                </div>

                <div x-show="lockedService && currentService">
                    <p class="text-sm font-semibold text-primary-dark/60 mb-1">Service</p>
                    <p class="text-lg font-bold text-primary-dark" x-text="currentService?.label"></p>
                </div>

                <div x-show="hasVariants">
                    <label for="calc-variant" class="block text-sm font-semibold text-primary-dark mb-2">Pet type</label>
                    <select id="calc-variant" x-model="variant" @change="tier = ''"
                        class="w-full rounded-xl border border-surface-purple px-4 py-3 text-primary-dark focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <option value="">Choose…</option>
                        <template x-for="(v, key) in currentService?.variants" :key="key">
                            <option :value="key" x-text="v.label"></option>
                        </template>
                    </select>
                </div>

                <div x-show="hasTierOptions">
                    <label for="calc-tier" class="block text-sm font-semibold text-primary-dark mb-2">Package</label>
                    <select id="calc-tier" x-model="tier"
                        class="w-full rounded-xl border border-surface-purple px-4 py-3 text-primary-dark focus:ring-2 focus:ring-primary/40 focus:border-primary">
                        <option value="">Choose a package…</option>
                        <template x-for="(t, key) in tierOptions" :key="key">
                            <option :value="key" x-text="t.label"></option>
                        </template>
                    </select>
                </div>

                <div x-show="showQuantity">
                    <label for="calc-qty" class="block text-sm font-semibold text-primary-dark mb-2"
                        x-text="currentService?.quantity_label"></label>
                    <input id="calc-qty" type="number" x-model.number="quantity" :min="quantityMin" :max="quantityMax"
                        class="w-full rounded-xl border border-surface-purple px-4 py-3 text-primary-dark focus:ring-2 focus:ring-primary/40 focus:border-primary" />
                </div>

                <button type="button" @click="calculate()"
                    :disabled="!canCalculate"
                    class="inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark disabled:opacity-50 disabled:pointer-events-none
                           text-white px-8 py-4 rounded-full font-bold transition shadow-glow hover:-translate-y-1 w-full sm:w-auto">
                    <span x-text="primaryActionLabel"></span>
                    <span class="material-symbols-outlined" aria-hidden="true">calculate</span>
                </button>
            </div>
        </div>

        <div class="mt-10" x-show="step === 'result'" x-cloak>
            <x-pricing.decision />
        </div>
    </div>
</section>
