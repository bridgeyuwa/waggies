const pricingCalculator = (data, initial = {}, contactUrl, bookingUrl) => ({
    services: data.services || {},
    service: initial.service || '',
    variant: initial.variant || '',
    size: initial.size || '',
    quantity: Number(initial.quantity || 1),
    step: 'form',
    result: { service: '', option: '', features: [], display: '', cta: '', href: '#', notice: '' },

    init() {
        this.normaliseSelection();
    },

    get current() {
        return this.services[this.service] || null;
    },

    get variantOptions() {
        return Object.entries(this.current?.variants || {}).map(([key, item]) => ({ ...item, key }));
    },

    get currentVariant() {
        return this.current?.variants?.[this.variant] || null;
    },

    get sizeOptions() {
        return Object.entries(this.currentVariant?.size_rates || {}).map(([key, item]) => ({ ...item, key }));
    },

    get needsSize() {
        return this.service === 'boarding' && this.variant === 'dogs';
    },

    get canCalculate() {
        return Boolean(this.service && this.variant && (!this.needsSize || this.size));
    },

    selectService(service) {
        this.service = service;
        this.variant = '';
        this.size = '';
        this.step = 'form';
        this.normaliseSelection();
    },

    calculate() {
        if (!this.canCalculate) return;

        const option = this.currentVariant || {};
        const sizeRate = this.sizeOptions.find((item) => item.key === this.size);
        const rate = sizeRate || option;
        const nights = Math.max(1, Number(this.quantity) || 1);
        const quoteOnly = option.type === 'quote' || option.manual_quote || rate.manual_review || !Number.isFinite(Number(rate.amount));
        const amount = Number(rate.amount || 0) * (this.service === 'boarding' ? nights : 1);
        const maximum = Number(rate.max_amount || rate.amount || 0) * (this.service === 'boarding' ? nights : 1);

        this.result = {
            service: this.current.label,
            option: option.label || '',
            features: option.features || [],
            display: quoteOnly ? 'Custom quote' : (amount === maximum ? this.naira(amount) : `${this.naira(amount)}–${this.naira(maximum)}`),
            cta: 'Submit Booking Request',
            href: `${bookingUrl}?service=${encodeURIComponent(this.service)}&variant=${encodeURIComponent(this.variant)}`,
            notice: 'This is indicative information only. Waggies confirms availability and the final quotation manually.',
        };
        this.step = 'result';
    },

    reset() {
        this.step = 'form';
        this.result = { service: '', option: '', features: [], display: '', cta: '', href: '#', notice: '' };
    },

    naira(amount) {
        return `₦${Number(amount).toLocaleString('en-NG')}`;
    },

    normaliseSelection() {
        if (!this.current) {
            this.service = '';
            this.variant = '';
            this.size = '';
            return;
        }

        if (!this.currentVariant) {
            this.variant = '';
            this.size = '';
        }

        if (this.size && !this.sizeOptions.some((option) => option.key === this.size)) {
            this.size = '';
        }
    },
});

export function registerPricingCalculator(Alpine) {
    Alpine.data('pricingCalculator', pricingCalculator);
}
