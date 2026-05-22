document.addEventListener('alpine:init', () => {
    Alpine.data('pricingCalculator', (payload, contactBase) => ({
        services: payload.services,
        service: payload.selectedService || '',
        variant: payload.selectedVariant || '',
        tier: payload.selectedTier || '',
        quantity: 1,
        step: 'form',
        result: null,
        contactBase,
        initialService: payload.selectedService || '',

        init() {
            if (this.service) {
                this.onServiceChange();
            }

            if (this.initialService) {
                this.$nextTick(() => {
                    document.getElementById('pricing-calculator')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
            }

            if (payload.selectedTier && this.tier) {
                this.$nextTick(() => {
                    if (this.canCalculate) {
                        this.calculate();
                    }
                });
            }
        },

        get lockedService() {
            return Boolean(this.initialService);
        },

        get currentService() {
            return this.service ? this.services[this.service] : null;
        },

        get hasVariants() {
            return this.currentService?.variants && Object.keys(this.currentService.variants).length > 0;
        },

        get tierOptions() {
            if (!this.currentService) {
                return {};
            }
            if (this.hasVariants) {
                return this.variant && this.currentService.variants[this.variant]
                    ? this.currentService.variants[this.variant].tiers
                    : {};
            }

            return this.currentService.tiers || {};
        },

        get currentTier() {
            return this.tier ? this.tierOptions[this.tier] : null;
        },

        get showQuantity() {
            return Boolean(this.currentService?.quantity_label);
        },

        get quantityMin() {
            return this.currentService?.quantity_min || 1;
        },

        get quantityMax() {
            return this.currentService?.quantity_max || 99;
        },

        get canCalculate() {
            if (!this.service || !this.tier) {
                return false;
            }
            if (this.hasVariants && !this.variant) {
                return false;
            }

            return true;
        },

        get primaryActionLabel() {
            const type = this.currentTier?.type;
            if (type === 'quote') {
                return 'Request Consultation';
            }
            if (type === 'estimate') {
                return 'Get Estimate Range';
            }

            return 'Calculate Price';
        },

        onServiceChange() {
            this.variant = '';
            this.tier = '';
        },

        formatNaira(amount) {
            return '₦' + Number(amount).toLocaleString('en-NG');
        },

        calculate() {
            const tier = this.currentTier;
            if (!tier) {
                return;
            }

            const qty = this.showQuantity ? Math.max(this.quantityMin, this.quantity) : 1;
            let display = '';
            let summary = '';
            let primaryCta = 'Book Now';
            let primaryIntent = 'book';

            if (tier.type === 'fixed' && tier.amount > 0) {
                const total = tier.amount * qty;
                display = this.formatNaira(total);
                if (qty > 1) {
                    display += ` <span class="text-base font-normal text-primary-dark/50">(${qty} × ${this.formatNaira(tier.amount)})</span>`;
                }
                summary = `${tier.label}: ${this.formatNaira(total)}${this.currentService?.unit || ''}`;
                primaryCta = 'Book Now';
                primaryIntent = 'book';
            } else if (tier.type === 'estimate') {
                const low = tier.amount * (this.showQuantity ? qty : 1);
                const high = (tier.amount_max || tier.amount) * (this.showQuantity ? qty : 1);
                display = `${this.formatNaira(low)} – ${this.formatNaira(high)}`;
                summary = `${tier.label}: ${this.formatNaira(low)} – ${this.formatNaira(high)}`;
                primaryCta = 'Proceed';
                primaryIntent = 'book';
            } else {
                display = 'Custom quote required';
                summary = `${tier.label}: custom quote`;
                primaryCta = 'Request Consultation';
                primaryIntent = 'consult';
            }

            this.result = {
                display,
                summary,
                type: tier.type,
                primaryCta,
                primaryIntent,
                serviceLabel: this.buildServiceLabel(),
                tierLabel: tier.label,
            };
            this.step = 'result';
            this.$nextTick(() => {
                document.getElementById('pricing-decision')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        },

        buildServiceLabel() {
            let label = this.currentService?.label || '';
            if (this.variant && this.currentService?.variants?.[this.variant]) {
                label += ' — ' + this.currentService.variants[this.variant].label;
            }

            return label;
        },

        reset() {
            this.step = 'form';
            this.result = null;
        },

        contactUrl(intent) {
            const params = new URLSearchParams();
            params.set('intent', intent);
            if (this.service) {
                params.set('service', this.service);
            }
            if (this.variant) {
                params.set('variant', this.variant);
            }
            if (this.tier) {
                params.set('tier', this.tier);
            }
            if (this.showQuantity) {
                params.set('quantity', String(this.quantity));
            }
            if (this.result?.summary) {
                params.set('summary', this.result.summary);
            }

            return this.contactBase + '?' + params.toString();
        },
    }));
});
