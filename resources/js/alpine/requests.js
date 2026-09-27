const contactRequestV2 = (schema, context, whatsapp) => ({
    schema,
    context,
    whatsapp,
    step: window.location.hash === '#review' ? 'review' : 'form',
    values: {},
    pets: [],
    serviceBlocks: [],
    servicePicker: false,
    serviceOptions: [
        { value: 'boarding', label: 'Boarding' },
        { value: 'vet-care', label: 'Veterinary Care' },
        { value: 'relocation', label: 'Relocation' },
    ],
    cartItems: [],
    cartEmpty: true,
    reference: 'WGX-' + Math.random().toString(36).slice(2, 6).toUpperCase() + '-' + Math.random().toString(36).slice(2, 6).toUpperCase(),
    submitting: false,
    serverError: '',
    savedMessage: '',
    init() {
        const contextKey = JSON.stringify([this.context.intent, this.context.service, this.context.variant, this.context.productId, this.context.productName, this.context.source]);
        const stored = JSON.parse(sessionStorage.getItem('waggies-contact-draft') || '{}');
        const saved = stored.contextKey === contextKey ? stored : {};
        const defaults = Object.fromEntries(this.schema.fields.filter(field => field.defaultValue !== null && field.defaultValue !== undefined).map(field => [field.name, String(field.defaultValue)]));
        this.values = { ...defaults, ...(saved.values || {}) };
        this.pets = saved.pets || (this.schema.usesPets ? [this.newPet(this.schema.contextSpecies || '')] : []);
        this.serviceBlocks = saved.serviceBlocks || [];

        try {
            const storedCart = JSON.parse(localStorage.getItem('waggies-cart') || '{}');
            this.cartItems = Array.isArray(storedCart.state?.items) ? storedCart.state.items : [];
        } catch {
            this.cartItems = [];
        }

        this.cartEmpty = this.cartItems.length === 0;
        this.$watch('values', () => this.saveDraft(), { deep: true });
        this.$watch('pets', () => this.saveDraft(), { deep: true });
        this.$watch('serviceBlocks', () => this.saveDraft(), { deep: true });
    },
    saveDraft() {
        const contextKey = JSON.stringify([this.context.intent, this.context.service, this.context.variant, this.context.productId, this.context.productName, this.context.source]);
        sessionStorage.setItem('waggies-contact-draft', JSON.stringify({ contextKey, values: this.values, pets: this.pets, serviceBlocks: this.serviceBlocks }));
    },
    newPet(species = '') {
        return { id: 'pet-' + Date.now() + '-' + Math.random().toString(36).slice(2, 7), name: '', species, breed: '', age: '', sex: '', notes: {} };
    },
    addPet() { this.pets.push(this.newPet()); },
    removePet(index) { this.pets.splice(index, 1); },
    petValid(pet) { return Boolean(String(pet.name || '').trim() && pet.species); },
    firstInvalidPetIndex() { return this.pets.findIndex(pet => !this.petValid(pet)); },
    petsValid() { return !this.schema.usesPets || (this.pets.length > 0 && this.pets.every(pet => this.petValid(pet))); },
    lockedSpecies() { return this.schema.allowedSpecies?.length === 1; },
    lockedSpeciesLabel() { return this.schema.allowedSpecies?.[0] === 'dog' ? 'dog' : 'cat'; },
    lockedSpeciesOptionLabel() { const label = this.lockedSpeciesLabel(); return label.charAt(0).toUpperCase() + label.slice(1); },
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
    canContinue() { return this.missingFields().length === 0 && this.petsValid() && !(this.schema.intent === 'CART_ORDER' && this.cartEmpty); },
    estimate() { return { status: 'not_applicable' }; },
    estimateText() { return 'To be confirmed by Waggies'; },
    review() {
        if (!this.canContinue()) return;
        this.step = 'review';
    },
    edit() { this.step = 'form'; },
    labelFor(field, value) { return this.fieldOptions(field).find(option => option.value === value)?.label || value; },
    addService(option) {
        this.serviceBlocks.push({ id: 'svc-' + Date.now(), service: option.value, title: option.label + ' request', fields: [], values: {} });
        this.servicePicker = false;
    },
    reviewLines() {
        const lines = this.visibleFields().filter(field => String(this.values[field.name] || '').trim() && field.showInSummary !== false).map(field => ({ key: field.name, label: field.label, value: this.labelFor(field, this.values[field.name]) }));
        this.pets.forEach((pet, index) => {
            if (this.petValid(pet)) lines.push({ key: 'pet-' + index, label: 'Pet ' + (index + 1), value: [pet.name, pet.species, pet.breed].filter(Boolean).join(' · ') });
        });
        return lines;
    },
    whatsappUrl() { return this.whatsapp + '?text=' + encodeURIComponent(this.requestMessageCanonical()); },
    requestMessageCanonical() {
        const lines = ['Hello Waggies,', ''];
        const service = { boarding: 'boarding', 'vet-care': 'veterinary care', relocation: 'pet relocation' }[this.context.service] || 'a service request';
        lines.push('I would like to request ' + service + '.');
        if (this.context.variant) lines.push('Request type: ' + this.context.variant.replaceAll('-', ' '));
        lines.push('');
        this.reviewLines().forEach(line => lines.push(line.label + ': ' + line.value));
        if (this.values.message) lines.push('', 'Message: ' + this.values.message);
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
                headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' },
                body: JSON.stringify({ name: this.values.customerName || null, phone: this.values.whatsappNumber || null, subject: this.schema.title, service: this.context.service || null, intent: this.schema.intent, message: this.requestMessageCanonical(), reference: this.reference, website: '' }),
            });
            const payload = await response.json().catch(() => ({}));

            if (!response.ok) {
                popup?.close();
                this.serverError = payload.message || 'We could not save your request. Please try again.';
                return;
            }

            this.savedMessage = payload.message || 'Your request has been saved.';
            this.clearDraft();
            if (popup) popup.location = this.whatsappUrl(); else window.location.href = this.whatsappUrl();
        } catch {
            popup?.close();
            this.serverError = 'We could not save your request. Please check your connection and try again.';
        } finally {
            this.submitting = false;
        }
    },
    clearDraft() { sessionStorage.removeItem('waggies-contact-draft'); },
});

const testimonialForm = (submitUrl) => ({
    step: 0,
    submitted: false,
    submitting: false,
    serverError: '',
    errors: {},
    services: ['Boarding', 'Veterinary Care', 'Relocation'],
    data: { rating: 0, service: '', story: '', authorName: '', authorLocation: '', consent: false },
    get stepIndex() { return this.step; },
    clear(field) { delete this.errors[field]; },
    ratingKeydown(event, value) {
        const delta = event.key === 'ArrowRight' || event.key === 'ArrowDown' ? 1 : event.key === 'ArrowLeft' || event.key === 'ArrowUp' ? -1 : 0;
        const next = event.key === 'Home' ? 1 : event.key === 'End' ? 5 : delta ? ((value - 1 + delta + 5) % 5) + 1 : null;
        if (next === null) return;
        event.preventDefault();
        this.data.rating = next;
        this.clear('rating');
    },
    validateExperience() { const errors = {}; if (!Number.isInteger(this.data.rating) || this.data.rating < 1 || this.data.rating > 5) errors.rating = 'Please select a star rating.'; if (!this.services.includes(this.data.service)) errors.service = 'Please choose the service you used.'; if (this.data.story.length < 50) errors.story = 'Please share at least 50 characters about your experience.'; return errors; },
    validateAbout() { const errors = {}; if (this.data.authorName.trim().length < 2) errors.authorName = 'Please enter your name.'; if (this.data.authorLocation.trim().length < 2) errors.authorLocation = 'Please enter your area.'; return errors; },
    next() { this.errors = this.step === 0 ? this.validateExperience() : this.validateAbout(); if (!Object.keys(this.errors).length) this.step += 1; },
    back() { if (this.step > 0) { this.step -= 1; this.errors = {}; } },
    async submit(event) {
        if (this.submitting) return;
        this.errors = this.data.consent ? {} : { consent: 'Please agree to let Waggies use your testimonial.' };
        if (Object.keys(this.errors).length) return;
        this.submitting = true;

        try {
            const response = await fetch(submitUrl, { method: 'POST', body: new FormData(event.target), headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('The testimonial could not be submitted. Please try again.');
            this.submitted = true;
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
