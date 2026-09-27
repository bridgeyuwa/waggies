const pricingCalculator = (data, initial = {}, contactUrl, bookingUrl) => ({
    service: initial.service || '',
    variant: initial.variant || '',
    size: initial.size || '',
    quantity: Number(initial.quantity || 1),
    result: { service: '', display: '', cta: 'Submit Booking Request', href: bookingUrl, notice: '' },
    serviceOptions: Object.entries(data?.services || {}).map(([value, service]) => ({ value, label: service.label })),
    selectService(value) {
        this.service = value;
        this.variant = '';
        this.size = '';
        this.result = { service: '', display: '', cta: 'Submit Booking Request', href: bookingUrl, notice: '' };
    },
    get currentService() { return data?.services?.[this.service] || null; },
    get variantOptions() { return Object.entries(this.currentService?.variants || {}).map(([value, option]) => ({ value, label: option.label })); },
    get sizeOptions() { return Object.entries(this.currentService?.variants?.[this.variant]?.size_rates || {}).map(([value, option]) => ({ value, label: option.booking_label || option.label, guidance: option.guidance })); },
    get needsSize() { return this.service === 'boarding' && this.variant === 'dogs'; },
    get canCalculate() { return Boolean(this.service && this.variant && (!this.needsSize || this.size)); },
    calculate() {
        if (!this.canCalculate) return;
        this.result = { service: [this.currentService.label, this.currentService.variants?.[this.variant]?.label].filter(Boolean).join(' · '), display: 'Staff review required', cta: 'Submit Booking Request', href: `${bookingUrl}?service=${encodeURIComponent(this.service)}&variant=${encodeURIComponent(this.variant)}&source=pricing`, notice: 'This guidance is indicative only. Waggies confirms availability and the final quote manually.' };
    },
    reset() { this.service = ''; this.variant = ''; this.size = ''; this.quantity = 1; this.result = { service: '', display: '', cta: 'Submit Booking Request', href: bookingUrl, notice: '' }; },
});

export function registerPricingCalculator(Alpine) {
    Alpine.data('pricingCalculator', pricingCalculator);
}
