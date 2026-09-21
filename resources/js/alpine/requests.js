window.waggiesTransportEstimate = (inputs, pricing) => {
    const transport = pricing?.transport || {}, product = transport.products?.[inputs.productId], messages = transport.messages || {}, rules = transport.rules || {}, result = { currency: pricing?.currency || 'NGN', productId: inputs.productId || null, amount: null, missingInputs: [], reason: null, customerMessage: null };
    if (!product) return { ...result, state: 'QUOTE_ONLY', reason: 'This transport option requires a confirmed quote.' };

    const required = ['pickup', 'dropoff', 'tripType', 'petCount', 'petSpecies'];
    if (inputs.productId !== 'transport-airport-transfer') required.push('distanceKm'); else if (!String(inputs.airportDetails || '').trim()) required.push('airportDetails');
    result.missingInputs = required.filter(key => { const value = inputs[key]; return value === undefined || value === null || value === '' || (Array.isArray(value) && value.length === 0); });
    if (result.missingInputs.length) return { ...result, state: 'MISSING_INPUTS' };

    const isTrue = value => value === true || value === 'true';
    if (isTrue(inputs.afterHours)) return { ...result, state: 'QUOTE_ONLY', reason: messages.after_hours };
    if (String(inputs.specialRequirements || '').trim()) return { ...result, state: 'QUOTE_ONLY', reason: messages.special_handling };
    if (Number(inputs.petCount) > 1 && !isTrue(inputs.additionalPetSafe)) return { ...result, state: 'QUOTE_ONLY', reason: messages.multiple_pets };
    if (Number(inputs.stopCount) > 0 && !isTrue(inputs.stopsWithinCorridor)) return { ...result, state: 'QUOTE_ONLY', reason: messages.outside_corridor };

    const distance = Number(inputs.distanceKm || 0), transportPricing = product.pricing || {};
    let oneWay = transportPricing.type === 'fixed' ? transportPricing.amount : transportPricing.rates?.find(rate => distance > 0 && distance <= rate.max_distance_km)?.amount;
    if (oneWay === undefined) return { ...result, state: 'UNAVAILABLE_ROUTE', reason: messages.outside_service_area };
    const waitingMinutes = Number(inputs.waitingMinutes || 0);
    if (waitingMinutes > 0 && product.tier === 'city') return { ...result, state: 'QUOTE_ONLY', reason: messages.city_waiting };
    const petMultiplier = 1 + Math.max(Number(inputs.petCount || 1) - 1, 0) * rules.additional_pet_multiplier;
    let total = oneWay * petMultiplier;
    if (waitingMinutes > rules.waiting_free_minutes) total += Math.ceil((waitingMinutes - rules.waiting_free_minutes) / rules.waiting_increment_minutes) * rules.waiting_increment_amount;
    if (Number(inputs.stopCount)) total += Number(inputs.stopCount) * rules.additional_stop_amount;
    if (inputs.tripType === 'return') total *= waitingMinutes < 60 ? rules.return_multiplier_before_waiting_minutes : rules.return_multiplier_after_waiting_minutes;
    if (inputs.transportUrgency === 'same-day') total *= rules.same_day_multiplier;

    return { ...result, state: 'ESTIMATE', amount: Math.round(total), customerMessage: messages.customer_estimate };
};

const contactRequest = (schema, context, whatsapp) => ({
    schema, context, whatsapp, step: window.location.hash === "#review" ? "review" : "form", values: {}, pets: [], serviceBlocks: [], servicePicker: false, cartItems: [], cartEmpty: true, reference: "WGX-" + Math.random().toString(36).slice(2, 6).toUpperCase() + "-" + Math.random().toString(36).slice(2, 6).toUpperCase(),
    init() {
        const contextKey = JSON.stringify([this.context.intent, this.context.service, this.context.variant, this.context.tier, this.context.productId, this.context.productName, this.context.transportProduct, this.context.transportRoute]);
        const initialHash = this.step === "review" ? "#review" : "#form";
        if (window.location.hash !== initialHash) history.replaceState(null, "", window.location.pathname + window.location.search + initialHash);
        const stored = JSON.parse(sessionStorage.getItem("waggies-request-draft") || "{}");
        const saved = stored.contextKey === contextKey ? stored : {};
        const defaults = Object.fromEntries(this.schema.fields.filter(field => field.defaultValue !== null && field.defaultValue !== undefined).map(field => [field.name, String(field.defaultValue)]));
        this.values = { ...defaults, ...this.transportRouteValues(), ...(saved.values || {}) };
        try { const storedCart = JSON.parse(localStorage.getItem("waggies-cart") || "{}"); this.cartItems = Array.isArray(storedCart.state?.items) ? storedCart.state.items : []; } catch { this.cartItems = []; }
        this.cartEmpty = this.cartItems.length === 0;
        const initialSpecies = this.schema.allowedSpecies?.length === 1 ? this.schema.allowedSpecies[0] : this.schema.contextSpecies;
        const seedPet = this.schema.usesPets && initialSpecies !== "exotic";
        this.pets = saved.pets?.length ? saved.pets.map(pet => ({ ...this.newPet(pet.species || ""), ...pet, extra: { exoticPetType: "", ...(pet.extra || {}) } })) : (seedPet ? [this.newPet(initialSpecies)] : []);
        this.serviceBlocks = (saved.serviceBlocks || []).map(block => ({ ...block, fields: block.fields || this.blockFields(block.service), values: { ...this.blockDefaultValues(block.service), ...(block.values || {}) } }));
        this.syncBoardingPackage();
        this.$watch("values", () => this.saveDraft(), { deep: true });
        this.$watch("pets", () => { this.syncBoardingPackage(); this.saveDraft(); }, { deep: true });
        this.$watch("serviceBlocks", () => this.saveDraft(), { deep: true });
        this.$watch("step", value => { const hash = value === "review" ? "#review" : "#form"; if (window.location.hash !== hash) history.replaceState(null, "", window.location.pathname + window.location.search + hash); });
        window.addEventListener("hashchange", () => { this.step = window.location.hash === "#review" ? "review" : "form"; });
    },
    saveDraft() {
        const contextKey = JSON.stringify([this.context.intent, this.context.service, this.context.variant, this.context.tier, this.context.productId, this.context.productName, this.context.transportProduct, this.context.transportRoute]);
        sessionStorage.setItem("waggies-request-draft", JSON.stringify({ contextKey, values: this.values, pets: this.pets, serviceBlocks: this.serviceBlocks }));
    },
    transportRouteValues() {
        if (!this.context.transportRoute) return {};
        try {
            const parsed = JSON.parse(this.context.transportRoute), value = key => parsed[key] === undefined || parsed[key] === null ? undefined : Array.isArray(parsed[key]) ? parsed[key].join(", ") : String(parsed[key]);
            return { ...(value("origin") ? { pickup: value("origin"), origin: value("origin") } : {}), ...(value("destination") ? { dropoff: value("destination"), destination: value("destination") } : {}), ...(value("journeyType") ? { tripType: value("journeyType") } : {}), ...(value("specialHandling") ? { specialRequirements: value("specialHandling") } : {}), ...(value("urgency") ? { transportUrgency: value("urgency") } : {}), ...Object.fromEntries(["distanceKm", "petCount", "petSpecies", "additionalPetSafe", "waitingMinutes", "stopCount", "stopsWithinCorridor", "afterHours", "airportDetails"].flatMap(key => value(key) ? [[key, value(key)]] : [])) };
        } catch { return {}; }
    },
    newPet(species = "") { return { id: "pet-" + Date.now() + "-" + Math.random().toString(36).slice(2, 7), name: "", species, breed: "", age: "", sex: "", notes: "", extra: { exoticPetType: "" } }; },
    addPet() { this.pets.push(this.newPet()); },
    removePet(index) { this.pets.splice(index, 1); },
    petValid(pet) { return Boolean(String(pet.name || "").trim() && pet.species); },
    firstInvalidPetIndex() { return this.pets.findIndex(pet => !this.petValid(pet)); },
    petsValid() { return !this.schema.usesPets || (this.pets.length > 0 && this.pets.every(pet => this.petValid(pet))); },
    lockedSpecies() { return this.schema.allowedSpecies?.length === 1; },
    lockedSpeciesLabel() { return this.schema.allowedSpecies?.[0] === "dog" ? "dog" : this.schema.allowedSpecies?.[0] === "cat" ? "cat" : "exotic pet"; },
    lockedSpeciesOptionLabel() { const label = this.lockedSpeciesLabel(); return label ? label.charAt(0).toUpperCase() + label.slice(1) : ""; },
    effectiveVariant() {
        if (this.schema.contextSpecies === "dog") return "dogs";
        if (this.schema.contextSpecies === "cat") return "cats";
        if (this.schema.contextSpecies === "exotic") return "exotic";
        const species = this.pets[0]?.species;
        return species === "dog" ? "dogs" : species === "cat" ? "cats" : species === "exotic" ? "exotic" : null;
    },
    syncBoardingPackage() {
        if (!this.schema.fields.some(field => field.name === "boardingPackage")) return;
        const selected = String(this.values.boardingPackage || "");
        if (selected && !this.fieldOptions({ name: "boardingPackage" }).some(option => option.value === selected)) this.values.boardingPackage = "";
    },
    fieldOptions(field) {
        if (field.name === "boardingPackage") return Object.entries(this.schema.pricingData.services.boarding.variants[this.effectiveVariant() || "dogs"]?.tiers || {}).map(([value, tier]) => ({ value, label: tier.label }));
        if (field.name === "transportProduct") return Object.entries(this.schema.pricingData.transport.products || {}).map(([value, product]) => ({ value, label: this.schema.pricingData.services["local-transport"]?.tiers?.[product.tier]?.label || value }));
        return field.options || [];
    },
    transportProductLabel(product) { const tier = this.schema.pricingData.transport.products?.[product]?.tier; return this.schema.pricingData.services["local-transport"]?.tiers?.[tier]?.label || product; },
    visibleFields() { return this.schema.fields.filter(field => (!field.conditionalOn || this.values[field.conditionalOn.field] === field.conditionalOn.value) && (!field.conditionalOnAny || field.conditionalOnAny.values.includes(this.values[field.conditionalOnAny.field] || ""))); },
    requiredWhen(field) { return Boolean(field.requiredWhen && this.values[field.requiredWhen.field] === field.requiredWhen.value); },
    missingFields() { return this.visibleFields().filter(field => (field.required || this.requiredWhen(field)) && !String(this.values[field.name] || "").trim()).map(field => field.label); },
    canContinue() { return this.missingFields().length === 0 && this.petsValid() && !(this.schema.intent === "CART_ORDER" && this.cartEmpty); },
    formatNaira(value) { return "₦" + Number(value).toLocaleString("en-NG"); },
    estimate() {
        const v = this.values, data = this.schema.pricingData.services, service = this.context.resolvedService || this.context.service;
        if (this.schema.intent === "TRANSPORT_REQUEST") return this.transportEstimate();
        if (service === "boarding") {
            const variant = this.effectiveVariant(), tier = data.boarding.variants[variant || "dogs"]?.tiers?.[v.boardingPackage];
            if (!tier || !v.checkIn || !v.checkOut) return { status: "incomplete" };
            const nights = Math.round((new Date(v.checkOut + "T00:00:00") - new Date(v.checkIn + "T00:00:00")) / 86400000);
            if (!Number.isFinite(nights) || nights < 1) return { status: "incomplete" };
            if (tier.type === "quote") return { status: "not_applicable" };
            const qty = Math.min(Math.max(nights, 1), 30), min = tier.amount * qty, max = tier.max_amount ? tier.max_amount * qty : undefined;
            return { status: "calculated", display: max && max > min ? this.formatNaira(min) + " – " + this.formatNaira(max) : this.formatNaira(min), basis: tier.label + " × " + qty + " " + (qty === 1 ? "night" : "nights") };
        }
        if (service === "grooming") {
            const key = { "bath-brush": "bath", "full-groom": "full", "luxury-spa": "spa" }[v.groomingPackage], tier = data.grooming.tiers[key];
            if (!tier) return { status: "incomplete" };
            return tier.type === "quote" ? { status: "not_applicable" } : { status: "calculated", display: this.formatNaira(tier.amount), basis: tier.label + " × 1 session" };
        }
        if (service === "training") {
            const key = { "basic-obedience": "obedience", "puppy-socialization": "puppy", "behavioural-correction": "behaviour" }[v.trainingObjective], tier = data.training.tiers[key];
            if (!tier) return { status: "incomplete" };
            return tier.type === "quote" ? { status: "not_applicable" } : { status: "calculated", display: this.formatNaira(tier.amount) + " – " + this.formatNaira(tier.max_amount), basis: tier.label + " programme" };
        }
        return { status: "not_applicable" };
    },
    transportEstimate() {
        const route = window.waggiesTransportEstimate({ ...this.values, productId: this.values.transportProduct }, this.schema.pricingData);
        if (route.state === "MISSING_INPUTS") return { status: "incomplete" };
        if (route.state !== "ESTIMATE") return { status: "not_applicable", reason: route.reason };
        return { status: "calculated", display: this.formatNaira(route.amount), basis: "Provisional Local Transport pilot rate card", customerWording: route.customerMessage };
    },
    estimateText() { const e = this.estimate(); return e.status === "calculated" ? e.display + " · " + e.basis + "\nFinal price confirmed on WhatsApp." : e.status === "incomplete" ? "Complete the required information to calculate your estimate" : "To be confirmed on WhatsApp"; },
    review() {
        if (!this.canContinue()) return;
        this.step = "review";
        this.$nextTick(() => { document.querySelector("#request-review h2")?.focus(); document.getElementById("request-review")?.scrollIntoView({ behavior: matchMedia("(prefers-reduced-motion: reduce)").matches ? "auto" : "smooth", block: "start" }); });
    },
    edit() { this.step = "form"; this.$nextTick(() => document.getElementById("request-form")?.scrollIntoView({ behavior: matchMedia("(prefers-reduced-motion: reduce)").matches ? "auto" : "smooth", block: "start" })); },
    labelFor(field, value) { return this.fieldOptions(field).find(option => option.value === value)?.label || value; },
    reviewLines() {
        const lines = [];
        if (this.context.service) lines.push({ key: "service", label: "Service", value: this.serviceName(this.context.service) });
        this.schema.fields.forEach(field => { const value = this.values[field.name]; if (String(value || "").trim() && field.showInSummary !== false) lines.push({ key: field.name, label: field.label, value: this.labelFor(field, value) }); });
        if (this.schema.usesPets) this.pets.forEach((pet, index) => { const type = pet.species === "dog" ? "Dog" : pet.species === "cat" ? "Cat" : pet.species === "exotic" ? "Exotic pet" : pet.species; const detail = [type, pet.breed || pet.extra?.exoticPetType].filter(Boolean).join(", "); lines.push({ key: "pet-" + index, label: "Pet " + (index + 1), value: [pet.name, detail ? "(" + detail + ")" : ""].filter(Boolean).join(" ") || "(not specified)" }); });
        this.serviceBlocks.forEach((block, index) => { lines.push({ key: "additional-" + index, label: "Additional service " + (index + 1), value: this.serviceName(block.service) }); (block.fields || []).forEach(field => { const value = block.values?.[field.name]; if (String(value || "").trim()) lines.push({ key: "additional-" + index + "-" + field.name, label: "  " + field.label, value: this.labelFor(field, value) }); }); });
        const estimate = this.estimate(); if (estimate.status === "calculated" && estimate.customerWording) lines.push({ key: "pricing-note", label: "Pricing note", value: estimate.customerWording }); if (estimate.status === "not_applicable" && estimate.reason) lines.push({ key: "transport-pricing", label: "Transport pricing", value: estimate.reason });
        return lines;
    },
    serviceName(service) { return ({ boarding: "boarding", "boarding-dogs": "dog boarding", "boarding-cats": "cat boarding", "boarding-exotic": "exotic pet boarding", grooming: "grooming", "vet-care": "veterinary care", training: "training", relocation: "relocation", "relocation-import": "pet import", "relocation-export": "pet export", "local-transport": "local transport", transport: "local transport" }[service] || String(service || "a service").replaceAll("-", " ")); },
    blockDefaultValues(service) { if (service === "transport") return { tripType: "one-way", petCount: "1", additionalPetSafe: "false", waitingMinutes: "0", stopCount: "0", stopsWithinCorridor: "false", transportUrgency: "standard", afterHours: "false" }; return {}; },
    blockFields(service) {
        if (service === "boarding") return [{ name: "checkIn", label: "Check-in date", type: "date", required: true, group: "Stay dates" }, { name: "checkOut", label: "Check-out date", type: "date", required: true, group: "Stay dates" }, { name: "boardingPackage", label: "Boarding package", type: "select", required: false, options: [{ value: "basic", label: "Basic" }, { value: "premium", label: "Premium" }, { value: "deluxe", label: "Deluxe" }] }, { name: "feedingRequirements", label: "Feeding requirements (optional)", type: "textarea", required: false }, { name: "medications", label: "Medications (optional)", type: "textarea", required: false }, { name: "behavioralConsiderations", label: "Behavioral considerations (optional)", type: "textarea", required: false }, { name: "specialCareNeeds", label: "Special care needs (optional)", type: "textarea", required: false }, { name: "additionalNotes", label: "Additional notes (optional)", type: "textarea", required: false }];
        if (service === "grooming") return [{ name: "groomingPackage", label: "Grooming package", type: "select", options: [{ value: "bath-brush", label: "Bath & Brush" }, { value: "full-groom", label: "Full Groom" }, { value: "luxury-spa", label: "Luxury Spa" }] }, { name: "addOns", label: "Add-ons (optional)", type: "text" }, { name: "preferredDate", label: "Preferred date", type: "date" }, { name: "additionalNotes", label: "Additional notes", type: "textarea" }];
        if (service === "training") return [{ name: "trainingObjective", label: "Training objective", type: "select", required: true, options: [{ value: "basic-obedience", label: "Basic obedience" }, { value: "puppy-socialization", label: "Puppy socialization" }, { value: "behavioural-correction", label: "Behavioral correction" }, { value: "advanced-training", label: "Advanced training" }] }, { name: "behaviouralConcern", label: "Behavioral concern (optional)", type: "textarea", required: false }, { name: "priorTrainingExperience", label: "Prior training experience (optional)", type: "textarea", required: false }, { name: "preferredDate", label: "Preferred start date", type: "date", required: false }, { name: "additionalNotes", label: "Additional notes", type: "textarea", required: false }];
        return [{ name: "transportProduct", label: "Transport service", type: "select", required: true, options: [{ value: "transport-city-transfer", label: "City Pet Transfer" }, { value: "transport-vet-transfer", label: "Vet Transfer" }, { value: "transport-airport-transfer", label: "Airport Transfer" }] }, { name: "pickup", label: "Pickup location", type: "text", required: true }, { name: "dropoff", label: "Drop-off location", type: "text", required: true }, { name: "preferredDate", label: "Date", type: "date", required: true }, { name: "preferredTime", label: "Preferred time", type: "time", required: false }, { name: "tripType", label: "Trip type", type: "select", required: false, options: [{ value: "one-way", label: "One-way" }, { value: "return", label: "Return" }], defaultValue: "one-way" }, { name: "distanceKm", label: "Route distance (km)", type: "number", required: true, min: 1 }, { name: "petSpecies", label: "Pet species", type: "text", required: true }, { name: "petCount", label: "Number of pets", type: "number", required: true, min: 1, defaultValue: "1" }, { name: "additionalPetSafe", label: "Shared vehicle/crate plan is safe for additional pets", type: "select", required: false, options: [{ value: "false", label: "Needs assessment" }, { value: "true", label: "Confirmed safe" }], defaultValue: "false" }, { name: "waitingMinutes", label: "Expected waiting time (minutes)", type: "number", required: false, min: 0, defaultValue: "0" }, { name: "stopCount", label: "Additional stops", type: "number", required: false, min: 0, defaultValue: "0" }, { name: "stopsWithinCorridor", label: "Additional stops are within the route corridor", type: "select", required: false, options: [{ value: "false", label: "No / not confirmed" }, { value: "true", label: "Yes" }], defaultValue: "false" }, { name: "transportUrgency", label: "Timing", type: "select", required: false, options: [{ value: "standard", label: "Standard" }, { value: "same-day", label: "Same-day urgent" }], defaultValue: "standard" }, { name: "afterHours", label: "After-hours request", type: "select", required: false, options: [{ value: "false", label: "No" }, { value: "true", label: "Yes" }], defaultValue: "false" }, { name: "airportDetails", label: "Airport details", type: "text", required: false, requiredWhen: { field: "transportProduct", value: "transport-airport-transfer" }, conditionalOn: { field: "transportProduct", value: "transport-airport-transfer" } }, { name: "specialRequirements", label: "Special requirements (optional)", type: "textarea", required: false }];
    },
    serviceOptions: [{ value: "boarding", label: "Boarding" }, { value: "grooming", label: "Grooming" }, { value: "transport", label: "Transport" }, { value: "training", label: "Training" }],
    addService(option) { const fields = this.blockFields(option.value); const values = { ...this.blockDefaultValues(option.value), ...Object.fromEntries(fields.filter(field => field.defaultValue !== undefined).map(field => [field.name, String(field.defaultValue)])) }; this.serviceBlocks.push({ id: "svc-" + Date.now() + "-" + Math.random().toString(36).slice(2, 5), service: option.value, title: option.label + " request", fields, values }); this.servicePicker = false; },
    requestMessageCanonical() {
        const v = this.values, intent = this.context.intent || this.schema.intent, lines = ["Hello Waggies,", ""];
        if (intent === "SERVICE_REQUEST" || intent === "BOOKING_REQUEST") lines.push("I'd like to request " + this.serviceName(this.context.service) + " for my pet.");
        else if (intent === "QUOTE_REQUEST") lines.push("I'd like to request a quote for " + this.serviceName(this.context.service) + ".");
        else if (intent === "PRODUCT_INQUIRY") lines.push("I have a question about: " + (v.productName || "a product") + ".");
        else if (intent === "CART_ORDER") lines.push("I'd like to place the following order:");
        else if (intent === "RELOCATION_REQUEST") lines.push("I'd like help with pet relocation.");
        else if (intent === "TRANSPORT_REQUEST") lines.push("I'd like to request local pet transport.");
        else if (intent === "VETERINARY_REQUEST") lines.push("I'd like to request a veterinary appointment.");
        else if (intent === "TOOL_ASSISTANCE") lines.push("I used one of your tools and would like some help.");
        else lines.push("I'd like to get in touch with Waggies.");
        lines.push("");
        if (this.pets.length === 1) {
            const pet = this.pets[0], parts = [];
            if (pet.name) parts.push("Name: " + pet.name); if (pet.species) parts.push("Species: " + pet.species); if (pet.breed) parts.push("Breed: " + pet.breed); if (pet.age) parts.push("Age: " + pet.age); if (pet.sex) parts.push("Sex: " + pet.sex);
            Object.entries(pet.extra || {}).forEach(([key, value]) => { if (value) parts.push((key === "exoticPetType" ? "Exotic type" : key) + ": " + value); });
            if (pet.notes) parts.push("Notes: " + pet.notes);
            if (parts.length) { lines.push("Pet:"); parts.forEach(part => lines.push("  " + part)); lines.push(""); }
        } else if (this.pets.length > 1) {
            lines.push("Pets:"); this.pets.forEach((pet, index) => { lines.push("  Pet " + (index + 1) + ":"); if (pet.name) lines.push("    Name: " + pet.name); if (pet.species) lines.push("    Species: " + pet.species); if (pet.breed) lines.push("    Breed: " + pet.breed); if (pet.age) lines.push("    Age: " + pet.age); if (pet.sex) lines.push("    Sex: " + pet.sex); Object.entries(pet.extra || {}).forEach(([key, value]) => { if (value) lines.push("    " + (key === "exoticPetType" ? "Exotic type" : key) + ": " + value); }); if (pet.notes) lines.push("    Notes: " + pet.notes); }); lines.push("");
        }
        if (this.serviceBlocks.length) {
            lines.push("Also requesting:"); this.serviceBlocks.forEach(block => { const header = [this.serviceName(block.service)]; if (block.values?.preferredDate) header.push("preferred: " + block.values.preferredDate); lines.push("  - " + header.join(" · ")); (block.fields || []).forEach(field => { const value = block.values?.[field.name]; if (value) lines.push("    " + field.label + ": " + this.labelFor(field, value)); }); }); lines.push("");
        }
        if (v.checkIn && v.checkOut) { lines.push("Check-in: " + v.checkIn, "Check-out: " + v.checkOut, ""); }
        else if (v.preferredDate) { lines.push("Preferred date: " + v.preferredDate); if (v.preferredTime) lines.push("Preferred time: " + v.preferredTime); lines.push(""); }
        if (v.pickup && v.dropoff) lines.push("Pickup: " + v.pickup, "Dropoff: " + v.dropoff, ""); else if (v.pickup) lines.push("Pickup: " + v.pickup, "");
        if (v.origin) lines.push("Origin: " + v.origin); if (v.destination && intent !== "CART_ORDER") lines.push("Destination: " + v.destination); if (v.travelDate) lines.push("Travel date: " + v.travelDate); if (v.documentationStatus) lines.push("Documentation: " + v.documentationStatus); if (v.origin || v.destination || v.travelDate) lines.push("");
        if (v.reasonForVisit) lines.push("Reason for visit: " + v.reasonForVisit); if (v.urgency) lines.push("Urgency: " + v.urgency); if (v.reasonForVisit) lines.push("");
        if (v.trainingConcern) lines.push("Training concern: " + v.trainingConcern); if (v.desiredOutcome) lines.push("Desired outcome: " + v.desiredOutcome); if (v.trainingConcern) lines.push("");
        if (v.boardingPackage || v.groomingPackage) { lines.push("Package: " + (v.boardingPackage || v.groomingPackage)); lines.push(""); }
        if (this.context.intent === "CART_ORDER" && this.cartItems.length) { lines.push("Order:"); this.cartItems.forEach(item => lines.push("  " + item.name + " × " + item.quantity)); const total = this.cartItems.reduce((sum, item) => sum + item.price * item.quantity, 0); if (total) lines.push("Estimated cart total: ₦" + total.toLocaleString()); if (v.deliveryLocation) lines.push("Delivery location: " + v.deliveryLocation); if (v.preferredDeliveryTiming) lines.push("Preferred delivery timing: " + v.preferredDeliveryTiming); lines.push(""); }
        const estimate = this.estimate();
        if (this.schema.pricingMode === "QUOTE_REQUIRED") lines.push("Pricing: To be confirmed by Waggies."); else if (estimate.status === "calculated") lines.push("Pricing: " + estimate.display + " (" + (this.schema.pricingMode === "FIXED" ? "fixed" : "estimated") + ")");
        if (estimate.status === "calculated" && estimate.customerWording) lines.push("Pricing note: " + estimate.customerWording); if (this.schema.pricingMode === "QUOTE_REQUIRED" || estimate.status === "calculated") lines.push("");
        if (intent === "TRANSPORT_REQUEST" && (v.transportProduct || v.pickup || v.dropoff)) { const productLabel = this.transportProductLabel(v.transportProduct); lines.push("Transport product: " + productLabel, "Transport pricing: Provisional Local Transport pilot rate card", "Transport status: Provisional pricing; confirmation required."); if (estimate.customerWording) lines.push(estimate.customerWording); if (estimate.reason) lines.push("Transport outcome: " + estimate.reason); lines.push(""); }
        if (v.customerName) lines.push("Name: " + v.customerName); if (v.whatsappNumber) lines.push("WhatsApp: " + v.whatsappNumber); if (v.customerName || v.whatsappNumber) lines.push("");
        if (v.message || v.additionalNotes) lines.push("Additional instructions:", v.message || v.additionalNotes, "");
        lines.push("---", "Reference: " + this.reference); return lines.join("\n");
    },
    whatsappUrl() { return this.whatsapp + "?text=" + encodeURIComponent(this.requestMessageCanonical()); },
    clearDraft() { sessionStorage.removeItem("waggies-request-draft"); },
});

const testimonialForm = (submitUrl) => ({
    step: 0, submitted: false, submitting: false, serverError: '', errors: {}, services: ['Boarding', 'Grooming', 'Vet Care', 'Training', 'Transport', 'Relocation'], petTypes: ['Dog', 'Cat', 'Bird', 'Rabbit', 'Reptile', 'Other'], data: { rating: 0, service: '', title: '', story: '', authorName: '', authorLocation: '', petName: '', petType: '', photoUrl: undefined, consent: false },
    get stepIndex() { return this.step; },
    clear(field) { delete this.errors[field]; },
    validateExperience() { const errors = {}; if (!Number.isInteger(this.data.rating) || this.data.rating < 1 || this.data.rating > 5) errors.rating = 'Please select a star rating.'; if (!this.services.includes(this.data.service)) errors.service = this.data.service ? 'Please choose a valid service.' : 'Please select the service you used.'; if (!this.data.title) errors.title = 'Please give your experience a short title.'; else if (this.data.title.length > 80) errors.title = 'Title should be 80 characters or less.'; if (this.data.story.length < 50) errors.story = 'Please share at least 50 characters about your experience.'; else if (this.data.story.length > 2000) errors.story = 'Story should be 2000 characters or less.'; return errors; },
    validateAbout() { const errors = {}; if (this.data.authorName.trim().length < 2) errors.authorName = 'Please enter your name (min 2 characters).'; else if (this.data.authorName.length > 60) errors.authorName = 'Name should be 60 characters or less.'; if (this.data.authorLocation.trim().length < 2) errors.authorLocation = 'Please enter your area (e.g. Maitama, Abuja).'; else if (this.data.authorLocation.length > 80) errors.authorLocation = 'Location should be 80 characters or less.'; if (this.data.petName.length > 60) errors.petName = 'Pet name should be 60 characters or less.'; if (!this.petTypes.includes(this.data.petType)) errors.petType = this.data.petType ? 'Please choose a valid pet type.' : 'Please select your pet type.'; return errors; },
    next() { const errors = this.step === 0 ? this.validateExperience() : this.validateAbout(); this.errors = errors; if (!Object.keys(errors).length) this.step += 1; },
    back() { if (this.step > 0) { this.step -= 1; this.errors = {}; } },
    photoChange(event) { const file = event.target.files?.[0]; if (!file) return; const allowedTypes = ['image/jpeg', 'image/png', 'image/webp']; if (!allowedTypes.includes(file.type)) { this.errors.photo = 'Please choose a JPG, PNG, or WebP image.'; event.target.value = ''; return; } if (file.size > 4 * 1024 * 1024) { this.errors.photo = 'Please choose an image under 4 MB.'; event.target.value = ''; return; } const reader = new FileReader(); reader.onload = () => { this.data.photoUrl = typeof reader.result === 'string' ? reader.result : undefined; this.clear('photo'); }; reader.readAsDataURL(file); },
    removePhoto() { this.data.photoUrl = undefined; const input = document.getElementById('testimonial-photo'); if (input) input.value = ''; },
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
                    const fieldNames = { author_name: 'authorName', author_location: 'authorLocation', pet_name: 'petName', pet_type: 'petType' };
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
                this.data = { rating: 0, service: '', title: '', story: '', authorName: '', authorLocation: '', petName: '', petType: '', photoUrl: undefined, consent: false };
                this.removePhoto();
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
    Alpine.data('contactRequest', contactRequest);
    Alpine.data('testimonialForm', testimonialForm);
}
