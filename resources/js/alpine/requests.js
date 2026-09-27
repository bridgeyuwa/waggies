const contactRequestV2 = (schema, context, whatsapp) => ({
    schema,
    context,
    whatsapp,
    step: window.location.hash === '#review' ? 'review' : 'form',
    values: {},
    cartItems: [],
    cartEmpty: true,
    reference: 'WGX-' + Math.random().toString(36).slice(2, 6).toUpperCase() + '-' + Math.random().toString(36).slice(2, 6).toUpperCase(),
    submitting: false,
    serverError: '',
    savedMessage: '',
    init() {
        const contextKey = JSON.stringify([this.context.intent, this.context.productId, this.context.productName, this.context.source]);
        const stored = JSON.parse(sessionStorage.getItem('waggies-contact-draft') || '{}');
        const saved = stored.contextKey === contextKey ? stored : {};
        const defaults = Object.fromEntries(this.schema.fields.filter(field => field.defaultValue !== null && field.defaultValue !== undefined).map(field => [field.name, String(field.defaultValue)]));
        this.values = { ...defaults, ...(saved.values || {}) };

        try {
            const storedCart = JSON.parse(localStorage.getItem('waggies-cart') || '{}');
            this.cartItems = Array.isArray(storedCart.state?.items) ? storedCart.state.items : [];
        } catch {
            this.cartItems = [];
        }

        this.cartEmpty = this.cartItems.length === 0;
        this.$watch('values', () => this.saveDraft(), { deep: true });
        this.$watch('step', value => {
            const hash = value === 'review' ? '#review' : '#form';
            if (window.location.hash !== hash) history.replaceState(null, '', window.location.pathname + window.location.search + hash);
        });
    },
    saveDraft() {
        const contextKey = JSON.stringify([this.context.intent, this.context.productId, this.context.productName, this.context.source]);
        sessionStorage.setItem('waggies-contact-draft', JSON.stringify({ contextKey, values: this.values }));
    },
    fieldOptions(field) { return field.options || []; },
    radioKeydown(event, fieldName, options, value) {
        const currentIndex = options.findIndex(option => option.value === value);
        const delta = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : event.key === 'ArrowLeft' || event.key === 'ArrowUp' ? -1 : 0;
        const nextIndex = event.key === 'Home' ? 0 : event.key === 'End' ? options.length - 1 : delta ? (currentIndex + delta + options.length) % options.length : null;
        if (nextIndex === null) return;

        event.preventDefault();
        this.values[fieldName] = options[nextIndex].value;
    },
    visibleFields() { return this.schema.fields.filter(field => !field.conditionalOn || this.values[field.conditionalOn.field] === field.conditionalOn.value); },
    requiredWhen(field) { return Boolean(field.requiredWhen && this.values[field.requiredWhen.field] === field.requiredWhen.value); },
    missingFields() { return this.visibleFields().filter(field => (field.required || this.requiredWhen(field)) && !String(this.values[field.name] || '').trim()).map(field => field.label); },
    canContinue() { return this.missingFields().length === 0 && !(this.schema.intent === 'CART_ORDER' && this.cartEmpty); },
    estimate() { return { status: 'not_applicable' }; },
    estimateText() { return 'To be confirmed by Waggies'; },
    review() {
        if (!this.canContinue()) return;
        this.step = 'review';
        this.$nextTick(() => document.querySelector('#request-review h2')?.focus());
    },
    edit() { this.step = 'form'; },
    labelFor(field, value) { return this.fieldOptions(field).find(option => option.value === value)?.label || value; },
    reviewLines() {
        return this.visibleFields()
            .filter(field => String(this.values[field.name] || '').trim() && field.showInSummary !== false)
            .map(field => ({ key: field.name, label: field.label, value: this.labelFor(field, this.values[field.name]) }));
    },
    whatsappUrl() { return this.whatsapp + '?text=' + encodeURIComponent(this.requestMessageCanonical()); },
    requestMessageCanonical() {
        const lines = ['Hello Waggies,', ''];

        if (this.schema.intent === 'PRODUCT_INQUIRY') lines.push('I have a question about: ' + (this.values.productName || 'a product') + '.');
        else if (this.schema.intent === 'CART_ORDER') lines.push('I would like to ask about my saved shop products.');
        else if (this.schema.intent === 'TOOL_ASSISTANCE') lines.push('I used one of your tools and would like some help.');
        else lines.push('I would like to get in touch with Waggies.');

        lines.push('');
        this.reviewLines().forEach(line => lines.push(line.label + ': ' + line.value));
        if (this.cartItems.length) {
            lines.push('', 'Selected products:');
            this.cartItems.forEach(item => lines.push('  ' + item.name + ' × ' + item.quantity));
        }
        lines.push('', '---', 'Reference: ' + this.reference);

        return lines.join('\n');
    },
    async saveAndContinue(event) {
        event.preventDefault();
        if (this.submitting) return;

        this.submitting = true;
        this.serverError = '';
        const popup = window.open('about:blank', '_blank');

        try {
            const response = await fetch(document.body.dataset.contactEnquiryUrl, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    name: this.values.customerName || null,
                    phone: this.values.whatsappNumber || null,
                    subject: this.schema.title,
                    service: null,
                    intent: this.schema.intent,
                    message: this.requestMessageCanonical(),
                    reference: this.reference,
                    website: '',
                }),
            });
            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                popup?.close();
                this.serverError = payload.message || 'We could not save your enquiry. Please try again.';
                return;
            }

            this.savedMessage = payload.message || 'Your enquiry has been saved.';
            this.clearDraft();
            if (popup) popup.location = this.whatsappUrl(); else window.location.href = this.whatsappUrl();
        } catch {
            popup?.close();
            this.serverError = 'We could not save your enquiry. Please check your connection and try again.';
        } finally {
            this.submitting = false;
        }
    },
    clearDraft() { sessionStorage.removeItem('waggies-contact-draft'); },
});

const testimonialForm = (submitUrl) => ({
    step: 0, submitted: false, submitting: false, serverError: '', errors: {}, services: ['Boarding', 'Veterinary Care', 'Relocation'], data: { rating: 0, service: '', story: '', authorName: '', authorLocation: '', consent: false },
    get stepIndex() { return this.step; },
    clear(field) { delete this.errors[field]; },
    ratingKeydown(event, value) {
        const delta = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : event.key === 'ArrowLeft' || event.key === 'ArrowUp' ? -1 : 0;
        const next = event.key === 'Home' ? 1 : event.key === 'End' ? 5 : delta ? ((value - 1 + delta + 5) % 5) + 1 : null;

        if (next === null) return;

        event.preventDefault();
        const currentTarget = event.currentTarget;
        this.data.rating = next;
        this.clear('rating');
        this.$nextTick(() => currentTarget.closest('[role="radiogroup"]')?.querySelector(`[data-rating="${next}"]`)?.focus());
    },
    validateExperience() { const errors = {}; if (!Number.isInteger(this.data.rating) || this.data.rating < 1 || this.data.rating > 5) errors.rating = 'Please select a star rating.'; if (!this.services.includes(this.data.service)) errors.service = this.data.service ? 'Please choose a valid service.' : 'Please select the service you used.'; if (this.data.story.length < 50) errors.story = 'Please share at least 50 characters about your experience.'; else if (this.data.story.length > 2000) errors.story = 'Story should be 2000 characters or less.'; return errors; },
    validateAbout() { const errors = {}; if (this.data.authorName.trim().length < 2) errors.authorName = 'Please enter your name (min 2 characters).'; else if (this.data.authorName.length > 60) errors.authorName = 'Name should be 60 characters or less.'; if (this.data.authorLocation.trim().length < 2) errors.authorLocation = 'Please enter your area (e.g. Maitama, Abuja).'; else if (this.data.authorLocation.length > 80) errors.authorLocation = 'Location should be 80 characters or less.'; return errors; },
    next() { const errors = this.step === 0 ? this.validateExperience() : this.validateAbout(); this.errors = errors; if (!Object.keys(errors).length) this.step += 1; },
    back() { if (this.step > 0) { this.step -= 1; this.errors = {}; } },
    async submit(event) {
        if (this.submitting) return;

        this.errors = this.data.consent ? {} : { consent: 'Please agree to let Waggies use your testimonial.' };
        this.serverError = '';
        if (Object.keys(this.errors).length) return;

        this.submitting = true;

        try {
            const response = await fetch(submitUrl, {
                method: 'POST',
                body: new FormData(event.target),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!response.ok) {
                const payload = await response.json().catch(() => ({}));
                if (response.status === 422) {
                    const fieldNames = { author_name: 'authorName', author_location: 'authorLocation' };
                    this.errors = Object.fromEntries(Object.entries(payload.errors ?? {}).map(([field, messages]) => [fieldNames[field] || field, messages[0]]));
                    return;
                }

                throw new Error('The testimonial could not be submitted. Please try again.');
            }

            this.submitted = true;
            this.$nextTick(() => this.$root.querySelector('[role="status"]')?.focus());
            window.dispatchEvent(new CustomEvent('waggies:toast', { detail: 'Thank you! Your testimonial has been submitted for review.' }));
            setTimeout(() => {
                this.submitted = false;
                this.step = 0;
                this.data = { rating: 0, service: '', story: '', authorName: '', authorLocation: '', consent: false };
                this.errors = {};
            }, 3000);
        } catch (error) {
            this.serverError = error.message;
        } finally {
            this.submitting = false;
        }
    },
});

export function registerRequestComponents(Alpine) {
    Alpine.data('contactRequestV2', contactRequestV2);
    Alpine.data('testimonialForm', testimonialForm);
}
