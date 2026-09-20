const pricingCalculator = (data, initial = {}, contactUrl) => ({
    services: data.services,
    pricing: data,
    service: initial.service || '',
    variant: initial.variant || '',
    tier: initial.tier || '',
    quantity: 1,
    step: 'form',
    result: { service: '', tier: '', features: [], display: '', cta: '', href: '#', notice: '' },
    transportValues: { journeyType: 'one-way', petCount: '1', petSpecies: 'dog', additionalPetSafe: 'false', stopsWithinCorridor: 'false', afterHours: 'false' },
    locked: Boolean(initial.service),
    init() {
        if (this.tier && this.canCalculate) {
            this.$nextTick(() => this.calculate());
        }
    },
    get current() {
        return this.service ? this.services[this.service] : null;
    },
    get isLocalTransport() {
        return this.service === 'local-transport' || this.service === 'transport';
    },
    get hasVariants() {
        return Boolean(this.current && this.current.variants);
    },
    get tierOptions() {
        const tiers = this.hasVariants && this.variant && this.current.variants[this.variant]
            ? this.current.variants[this.variant].tiers
            : (this.current && !this.hasVariants ? this.current.tiers : []);

        return Object.entries(tiers || {}).map(([key, item]) => ({ ...item, key }));
    },
    get canCalculate() {
        return Boolean(this.service && this.tier && (!this.hasVariants || this.variant));
    },
    get actionLabel() {
        const item = this.tierOptions.find((option) => option.key === this.tier);

        return item?.type === 'quote' ? 'Request Consultation' : item?.type === 'estimate' ? 'Get Estimate Range' : 'Calculate Price';
    },
    price(item) {
        if (item.type === 'quote') {
            return 'Custom quote';
        }

        if (item.type === 'estimate') {
            return `${this.naira(item.amount)} - ${this.naira(item.max_amount || item.amount)}`;
        }

        return this.naira(item.amount);
    },
    naira(amount) {
        return `₦${Number(amount).toLocaleString('en-NG')}`;
    },
    productId() {
        return this.tier === 'vet' ? 'transport-vet-transfer' : this.tier === 'airport' ? 'transport-airport-transfer' : 'transport-city-transfer';
    },
    routeInputs() {
        const values = this.transportValues;

        return {
            pickup: values.origin,
            dropoff: values.destination,
            tripType: values.journeyType,
            distanceKm: values.distanceKm ? Number(values.distanceKm) : undefined,
            petCount: values.petCount ? Number(values.petCount) : undefined,
            petSpecies: values.petSpecies ? [values.petSpecies] : undefined,
            additionalPetSafe: values.additionalPetSafe === 'true',
            specialRequirements: values.specialHandling,
            waitingMinutes: values.waitingMinutes ? Number(values.waitingMinutes) : undefined,
            stopCount: values.stopCount ? Number(values.stopCount) : undefined,
            stopsWithinCorridor: values.stopsWithinCorridor === 'true',
            transportUrgency: values.urgency,
            afterHours: values.afterHours === 'true',
            airportDetails: values.airportDetails,
        };
    },
    localEstimate(productId, inputs) {
        return window.waggiesTransportEstimate({ ...inputs, productId }, this.pricing);
    },
    transportUrl(productId) {
        const params = new URLSearchParams({ intent: 'transport', service: 'local-transport', tier: this.tier, product: productId, transportProduct: productId, transportRoute: JSON.stringify(this.transportValues) });

        return `${contactUrl}?${params}`;
    },
    calculate() {
        const item = this.tierOptions.find((option) => option.key === this.tier);

        if (!item || !this.current) {
            return;
        }

        if (this.isLocalTransport) {
            const productId = this.productId();
            const route = this.localEstimate(productId, this.routeInputs());
            const serviceLabel = this.current.label;

            if (route.state === 'ESTIMATE') {
                this.result = { display: this.naira(route.amount), service: serviceLabel, tier: ({ city: 'City Pet Transfer', vet: 'Vet Transfer', airport: 'Airport Transfer' })[this.tier] || productId, features: item.features || [], cta: 'Request confirmation', href: this.transportUrl(productId), notice: route.customerMessage };
            } else {
                const reason = route.state === 'MISSING_INPUTS' ? `Complete route details: ${route.missingInputs.join(', ')}.` : route.reason;
                this.result = { display: route.state === 'MISSING_INPUTS' ? 'Complete route details' : 'Quote required', service: serviceLabel, tier: reason, features: item.features || [], cta: 'Request Quote', href: this.transportUrl(productId), notice: route.state === 'QUOTE_ONLY' ? reason : '' };
            }

            this.step = 'result';

            return;
        }

        const quantity = this.current.quantity_label ? Math.min(this.current.max, Math.max(this.current.min, Math.round(this.quantity) || this.current.min)) : 1;
        let display = item.type === 'quote' ? 'Custom quote required' : item.type === 'estimate' ? `${this.naira(item.amount * quantity)} - ${this.naira((item.max_amount || item.amount) * quantity)}` : this.naira(item.amount * quantity);

        if (item.type === 'fixed' && quantity > 1) {
            display += ` <span class="text-base font-normal text-primary-dark/50">(${quantity} × ${this.naira(item.amount)})</span>`;
        }

        const params = new URLSearchParams({ intent: item.type === 'quote' ? 'consult' : 'booking', service: this.service, ...(this.variant ? { variant: this.variant } : {}), tier: item.key });
        this.result = { display, service: this.current.label + (this.variant ? ` - ${this.current.variants[this.variant].label}` : ''), tier: item.label, features: item.features || [], cta: item.type === 'quote' ? 'Request Consultation' : 'Book Now', href: `${contactUrl}?${params}`, notice: '' };
        this.step = 'result';
    },
    reset() {
        this.step = 'form';
        this.result = { service: '', tier: '', features: [], display: '', cta: '', href: '#', notice: '' };
    },
});

export function registerPricingCalculator(Alpine) {
    Alpine.data('pricingCalculator', pricingCalculator);
}
