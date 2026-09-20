import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import { registerToolComponents } from './tools-calculators';
import { registerPricingCalculator } from './pricing-calculator';

const waggiesChat = () => ({
    showTop: false,
    chatOpen: false,
    input: '',
    messages: [{
        role: 'bot',
        text: 'Hi! I’m the Waggies AI assistant. I can help with information about our services, booking, and pet care. (This is a preview — for instant replies, chat with us on WhatsApp.)',
    }],
    send() {
        const value = this.input.trim();
        if (!value) return;

        this.messages.push({ role: 'user', text: value });
        this.input = '';

        const lower = value.toLowerCase();
        let text = 'I’d love to help with that! For detailed questions, please contact us directly at +234 908 081 1902 or visit our contact page.';
        let link = null;

        if (/^(hi|hello|hey|good morning|good afternoon|good evening|howdy)\b/.test(lower)) {
            text = 'Hello! How can I help you today?';
        } else if (/\b(boarding|board|overnight|stay|kennel)\b/.test(lower)) {
            text = 'We offer comfortable boarding for dogs, cats, and exotic pets in Abuja. Our suites are climate-controlled with daily playtime and feeding.';
            link = { text: 'View Boarding Services', href: document.body.dataset.boardingUrl };
        } else if (/\b(groom|grooming|bath|haircut|nail|trim|wash)\b/.test(lower)) {
            text = 'Our professional groomers provide breed-specific treatments including baths, haircuts, nail trims, and more. We use pet-safe products.';
            link = { text: 'View Grooming Services', href: document.body.dataset.groomingUrl };
        } else if (/\b(pric|cost|how much|rate|fee|estimate|quote)\b/.test(lower)) {
            text = 'We have a pricing tool where you can get an estimate based on your needs.';
            link = { text: 'View Pricing', href: document.body.dataset.pricingUrl };
        } else if (/\b(contact|phone|whatsapp|call|reach|number|telephone)\b/.test(lower)) {
            text = 'You can reach us at +234 908 081 1902 or chat with us on WhatsApp.';
            link = { text: 'Contact Us', href: document.body.dataset.contactUrl };
        } else if (/\b(hour|open|close|time|schedule|when|available)\b/.test(lower)) {
            text = 'We’re open Mon-Fri 9am-5pm, Sat-Sun 10am-2pm. Boarding guests receive 24/7 supervision regardless of office hours.';
        } else if (/\b(vet|veterinary|doctor|health|medical|check.?up|vaccin)\b/.test(lower)) {
            text = 'We have on-site veterinary support for routine check-ups, vaccinations, and minor treatments.';
            link = { text: 'View Vet Care', href: document.body.dataset.vetCareUrl };
        } else if (/\b(train|obedience|behavio?r|puppy|class)\b/.test(lower)) {
            text = 'We offer positive-reinforcement dog training for puppies and adult dogs.';
            link = { text: 'View Training', href: document.body.dataset.trainingUrl };
        } else if (/\b(relocat|move|travel|flight|import|export|international|nigeria|abroad)\b/.test(lower)) {
            text = 'We help with international pet relocation including import, export, and document preparation.';
            link = { text: 'View Relocation', href: document.body.dataset.relocationHub };
        } else if (/\b(transport|pickup|drop.?off|delivery|door.?to.?door|shuttle)\b/.test(lower)) {
            text = 'We offer door-to-door pet transport within Abuja as part of our relocation services.';
            link = { text: 'View Local Transport', href: document.body.dataset.relocationTransport };
        }

        this.messages.push({ role: 'bot', text, link });
    },
});

const waggiesNavbar = () => ({
    mobileOpen: false, desktopMenu: null, pinnedMenu: null, timer: null, lastFocus: null,
    init() { this.$watch('mobileOpen', open => { document.body.classList.toggle('overflow-hidden', open); if (open) { this.lastFocus = document.activeElement; this.$nextTick(() => this.$refs.drawer?.querySelector('button, a')?.focus()); } else { this.lastFocus?.focus?.(); } }); this.$el.addEventListener('keydown', event => { if (!this.mobileOpen || event.key !== 'Tab') return; const nodes = [...this.$refs.drawer.querySelectorAll('a,button')].filter(node => !node.disabled && node.offsetParent !== null); if (!nodes.length) return; const first = nodes[0], last = nodes[nodes.length - 1]; if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); } else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); } }); window.addEventListener('resize', () => { if (this.desktopMenu) this.updateMegaOffset(this.desktopMenu); }); },
    cancelClose() { clearTimeout(this.timer); this.timer = null; },
    hoverOpen(menu) { this.cancelClose(); this.timer = setTimeout(() => { if (!this.pinnedMenu || this.pinnedMenu === menu) { this.updateMegaOffset(menu); this.desktopMenu = menu; } }, 150); },
    hoverClose(menu) { this.cancelClose(); this.timer = setTimeout(() => { if (!this.pinnedMenu && this.desktopMenu === menu) this.desktopMenu = null; }, 150); },
    toggle(menu) { this.cancelClose(); if (this.pinnedMenu === menu) { this.pinnedMenu = null; this.desktopMenu = null; } else { this.updateMegaOffset(menu); this.pinnedMenu = menu; this.desktopMenu = menu; } },
    updateMegaOffset(menu) { const panel = document.querySelector(`#desktop-menu-${menu}`), trigger = document.querySelector(`[data-nav-trigger="${menu}"]`), nav = this.$refs.nav; if (!panel || !trigger || !nav) return; const triggerRect = trigger.getBoundingClientRect(), navRect = nav.getBoundingClientRect(); panel.style.setProperty('--mega-offset', `${navRect.left + navRect.width / 2 - (triggerRect.left + triggerRect.width / 2)}px`); },
    openAndFocus(menu) { this.updateMegaOffset(menu); this.desktopMenu = menu; this.$nextTick(() => { document.querySelector(`#desktop-menu-${menu} a`)?.focus(); }); },
    closeDesktop() { this.cancelClose(); this.pinnedMenu = null; this.desktopMenu = null; },
    openMobile() { this.lastFocus = document.activeElement; this.mobileOpen = true; }, closeMobile() { this.mobileOpen = false; },
    escape() { if (this.mobileOpen) this.closeMobile(); else this.closeDesktop(); },
});
const waggiesSearch = () => ({
    open: false, query: '', results: [], loading: false,
    init() { this.listen(); },
    listen() { window.addEventListener('waggies:open-search', () => { this.open = true; this.query = ''; this.results = []; this.$nextTick(() => this.$refs.input?.focus()); }); },
    close() { this.open = false; this.query = ''; this.results = []; },
    async fetchResults() {
        const value = this.query.trim();
        if (!value) { this.results = []; return; }
        this.loading = true;
        try { const response = await fetch(`${document.body.dataset.searchUrl}?q=${encodeURIComponent(value)}`); const data = await response.json(); this.results = data.results ?? []; } catch { this.results = []; } finally { this.loading = false; }
    },
    navigate(href) { this.close(); window.location.href = href; },
});
const waggiesCart = () => ({
    open: false,
    items: [],
    init() { this.listen(); },
    listen() {
        this.load();
        window.addEventListener('waggies:open-cart', () => { this.load(); this.open = true; });
        window.addEventListener('waggies:add-item', event => this.addItem(event.detail, event.detail.quantity || 1));
        window.addEventListener('storage', event => { if (event.key === 'waggies-cart') this.load(); });
    },
    load() {
        try {
            const stored = JSON.parse(localStorage.getItem('waggies-cart') || '{}');
            this.items = Array.isArray(stored.state?.items) ? stored.state.items : [];
        } catch { this.items = []; }
    },
    persist() {
        localStorage.setItem('waggies-cart', JSON.stringify({ state: { items: this.items }, version: 0 }));
        window.dispatchEvent(new CustomEvent('waggies:cart-updated', { detail: { items: this.items } }));
    },
    addItem(item, quantity = 1) {
        const existing = this.items.find(entry => entry.productId === item.productId);
        this.items = existing
            ? this.items.map(entry => entry.productId === item.productId ? { ...entry, quantity: entry.quantity + quantity } : entry)
            : [...this.items, { ...item, quantity }];
        this.persist();
    },
    removeItem(productId) { this.items = this.items.filter(item => item.productId !== productId); this.persist(); },
    updateQuantity(productId, quantity) { quantity <= 0 ? this.removeItem(productId) : (this.items = this.items.map(item => item.productId === productId ? { ...item, quantity } : item), this.persist()); },
    clearCart() { this.items = []; this.persist(); },
    subtotal() { return this.items.reduce((total, item) => total + item.price * item.quantity, 0); },
    formatPrice(amount) { return '₦' + Number(amount).toLocaleString('en-NG'); },
    close() { this.open = false; },
});
const waggiesCartIndicator = () => ({
    count: 0,
    init() { this.listen(); },
    listen() {
        this.load();
        window.addEventListener('waggies:cart-updated', event => this.setItems(event.detail?.items));
        window.addEventListener('storage', event => { if (event.key === 'waggies-cart') this.load(); });
    },
    load() {
        try {
            const stored = JSON.parse(localStorage.getItem('waggies-cart') || '{}');
            this.setItems(stored.state?.items);
        } catch { this.setItems([]); }
    },
    setItems(items) {
        this.count = Array.isArray(items) ? items.reduce((total, item) => total + Number(item.quantity || 0), 0) : 0;
    },
});

function initWaggiesSelect(select) {
    if (!(select instanceof HTMLSelectElement) || !select.closest('main') || select.dataset.waggiesSelectEnhanced === 'true' || select.multiple || select.size > 1) return;

    select.dataset.waggiesSelectEnhanced = 'true';
    const wrapper = document.createElement('div');
    wrapper.className = 'relative w-full';
    select.parentNode.insertBefore(wrapper, select);
    wrapper.appendChild(select);
    select.hidden = false;
    select.classList.add('sr-only');
    select.setAttribute('aria-hidden', 'true');
    select.tabIndex = -1;

    const nativeId = select.id ? `${select.id}-native` : `waggies-select-${Math.random().toString(36).slice(2)}-native`;
    const button = document.createElement('button');
    button.type = 'button';
    button.id = select.id || `waggies-select-${Math.random().toString(36).slice(2)}`;
    select.id = nativeId;
    button.className = 'waggies-select-trigger flex w-full min-h-[44px] items-center justify-between gap-2 rounded-md border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-primary-dark shadow-none transition-[color,box-shadow] focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-50';
    button.style.height = '44px';
    button.style.minHeight = '44px';
    if (select.classList.contains('contact-input')) {
        button.style.borderRadius = select.classList.contains('contact-input--pet') || select.id.startsWith('pet-') ? 'var(--radius-xl)' : 'var(--radius-2xl)';
    }
    button.setAttribute('role', 'combobox');
    button.setAttribute('aria-haspopup', 'listbox');
    button.setAttribute('aria-autocomplete', 'none');
    button.setAttribute('aria-expanded', 'false');
    button.dataset.state = 'closed';
    if (select.required) button.setAttribute('aria-required', 'true');
    if (select.getAttribute('aria-describedby')) button.setAttribute('aria-describedby', select.getAttribute('aria-describedby'));
    if (select.getAttribute('aria-invalid')) button.setAttribute('aria-invalid', select.getAttribute('aria-invalid'));
    if (select.disabled) button.disabled = true;

    const value = document.createElement('span');
    value.className = 'min-w-0 flex-1 truncate';
    const chevron = document.createElement('img');
    chevron.src = '/icons/material-symbols/outlined/keyboard_arrow_down.svg';
    chevron.alt = '';
    chevron.className = 'h-4 w-4 shrink-0 opacity-50';
    button.append(value, chevron);
    wrapper.appendChild(button);

    const listbox = document.createElement('div');
    listbox.id = `${button.id}-listbox`;
    listbox.className = 'waggies-select-options fixed z-layer-navigation min-w-[8rem] overflow-x-hidden overflow-y-auto rounded-lg border border-primary/15 bg-white p-1 shadow-lg outline-none';
    listbox.setAttribute('role', 'listbox');
    listbox.dataset.state = 'closed';
    listbox.hidden = true;
    document.body.appendChild(listbox);
    button.setAttribute('aria-controls', listbox.id);

    let optionButtons = [];
    let open = false;
    let typeahead = '';
    let typeaheadTimer = null;

    const options = () => [...select.options].filter(option => option.value !== '');
    const selectedLabel = () => select.value === '' ? 'Select...' : (select.selectedOptions[0]?.textContent?.trim() || 'Select...');
    const sync = () => {
        value.textContent = selectedLabel();
        value.classList.toggle('text-primary-dark/45', select.value === '');
        value.classList.toggle('text-primary-dark', select.value !== '');
        if (select.value === '') button.dataset.placeholder = '';
        else delete button.dataset.placeholder;
        button.disabled = select.disabled;
        if (select.disabled) button.dataset.disabled = '';
        else delete button.dataset.disabled;
        if (select.required) button.setAttribute('aria-required', 'true');
        else button.removeAttribute('aria-required');
        if (select.getAttribute('aria-describedby')) button.setAttribute('aria-describedby', select.getAttribute('aria-describedby'));
        else button.removeAttribute('aria-describedby');
        if (select.getAttribute('aria-invalid')) button.setAttribute('aria-invalid', select.getAttribute('aria-invalid'));
        else button.removeAttribute('aria-invalid');
        optionButtons.forEach(optionButton => {
            const selected = optionButton.dataset.value === select.value;
            optionButton.setAttribute('aria-selected', selected ? 'true' : 'false');
            optionButton.dataset.state = selected ? 'checked' : 'unchecked';
            optionButton.querySelector('[data-waggies-select-check]')?.toggleAttribute('hidden', !selected);
        });
    };
    const renderOptions = () => {
        listbox.replaceChildren();
        optionButtons = options().map(option => {
            const optionButton = document.createElement('div');
            optionButton.className = 'flex min-h-[44px] w-full cursor-default items-center gap-2 rounded-md py-3 pl-3 pr-9 text-left text-sm text-primary-dark outline-none select-none hover:bg-surface-purple focus:bg-surface-purple';
            optionButton.dataset.value = option.value;
            optionButton.setAttribute('role', 'option');
            optionButton.tabIndex = -1;
            optionButton.dataset.state = 'unchecked';
            optionButton.textContent = option.textContent?.trim() || '';
            optionButton.dataset.label = optionButton.textContent;
            const check = document.createElement('img');
            check.src = '/icons/material-symbols/outlined/check.svg';
            check.alt = '';
            check.className = 'pointer-events-none absolute right-3 h-4 w-4';
            check.dataset.waggiesSelectCheck = '';
            optionButton.classList.add('relative');
            optionButton.appendChild(check);
            check.hidden = option.value !== select.value;
            optionButton.addEventListener('click', () => {
                select.value = option.value;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                close();
                button.focus();
            });
            optionButton.addEventListener('focus', () => { optionButton.dataset.highlighted = ''; });
            optionButton.addEventListener('blur', () => { delete optionButton.dataset.highlighted; });
            optionButton.addEventListener('pointermove', () => optionButton.focus({ preventScroll: true }));
            optionButton.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    optionButton.click();
                }
            });
            listbox.appendChild(optionButton);
            return optionButton;
        });
        sync();
    };
    const positionMenu = () => {
        const rect = button.getBoundingClientRect();
        const viewportPadding = 4;
        const gap = 4;
        const width = Math.round(rect.width);
        const left = Math.min(Math.max(viewportPadding, Math.round(rect.left)), Math.max(viewportPadding, window.innerWidth - width - viewportPadding));
        const spaceBelow = Math.floor(window.innerHeight - rect.bottom - gap - viewportPadding);
        const spaceAbove = Math.floor(rect.top - gap - viewportPadding);
        const opensAbove = spaceBelow < 180 && spaceAbove > spaceBelow;
        listbox.style.left = `${left}px`;
        listbox.style.width = `${width}px`;
        listbox.style.minWidth = `${width}px`;
        listbox.style.maxHeight = `${Math.max(44, opensAbove ? spaceAbove : spaceBelow)}px`;
        listbox.style.top = opensAbove ? 'auto' : `${Math.round(rect.bottom + gap)}px`;
        listbox.style.bottom = opensAbove ? `${Math.round(window.innerHeight - rect.top + gap)}px` : 'auto';
        listbox.dataset.side = opensAbove ? 'top' : 'bottom';
    };
    const close = () => {
        open = false;
        typeahead = '';
        if (typeaheadTimer) { clearTimeout(typeaheadTimer); typeaheadTimer = null; }
        listbox.hidden = true;
        button.setAttribute('aria-expanded', 'false');
        button.dataset.state = 'closed';
        listbox.dataset.state = 'closed';
    };
    const openMenu = (focusIndex = 0) => {
        renderOptions();
        positionMenu();
        open = true;
        listbox.hidden = false;
        button.setAttribute('aria-expanded', 'true');
        button.dataset.state = 'open';
        listbox.dataset.state = 'open';
        const selectedIndex = optionButtons.findIndex(optionButton => optionButton.dataset.value === select.value);
        optionButtons[Math.max(0, selectedIndex >= 0 ? selectedIndex : focusIndex)]?.focus();
    };
    button.addEventListener('click', () => open ? close() : openMenu());
    button.addEventListener('keydown', event => {
        if (event.key === 'ArrowDown' || event.key === 'ArrowUp' || event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (!open) openMenu(event.key === 'ArrowUp' ? -1 : 0);
        } else if (event.key === 'Escape') {
            close();
        }
    });
    listbox.addEventListener('keydown', event => {
        const currentIndex = optionButtons.indexOf(document.activeElement);
        if (event.key.length === 1 && !event.ctrlKey && !event.altKey && !event.metaKey) {
            event.preventDefault();
            typeahead += event.key.toLowerCase();
            const match = optionButtons.find(optionButton => optionButton.dataset.label?.toLowerCase().startsWith(typeahead));
            if (match) match.focus();
            if (typeaheadTimer) clearTimeout(typeaheadTimer);
            typeaheadTimer = setTimeout(() => { typeahead = ''; typeaheadTimer = null; }, 1000);
            return;
        }
        if (event.key === 'ArrowDown') { event.preventDefault(); optionButtons[Math.min(optionButtons.length - 1, currentIndex + 1)]?.focus(); }
        else if (event.key === 'ArrowUp') { event.preventDefault(); optionButtons[Math.max(0, currentIndex - 1)]?.focus(); }
        else if (event.key === 'Home') { event.preventDefault(); optionButtons[0]?.focus(); }
        else if (event.key === 'End') { event.preventDefault(); optionButtons.at(-1)?.focus(); }
        else if (event.key === 'Escape') { event.preventDefault(); close(); button.focus(); }
    });
    select.addEventListener('change', sync);
    document.addEventListener('click', event => { if (!wrapper.contains(event.target) && !listbox.contains(event.target)) close(); });
    window.addEventListener('resize', close);
    window.addEventListener('blur', close);
    window.addEventListener('scroll', () => { if (open) positionMenu(); }, true);

    const observer = new MutationObserver(() => { renderOptions(); });
    observer.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'required', 'aria-invalid', 'aria-describedby'] });
    renderOptions();
}

function enhanceWaggiesSelects(root = document) {
    if (root.matches?.('select:not([multiple]):not([size])')) initWaggiesSelect(root);
    root.querySelectorAll?.('main select:not([multiple]):not([size])').forEach(initWaggiesSelect);
}

const waggiesSelectObserver = new MutationObserver(mutations => {
    mutations.forEach(mutation => mutation.addedNodes.forEach(node => {
        if (node.nodeType === Node.ELEMENT_NODE) enhanceWaggiesSelects(node);
    }));
});
const waggiesShopIndex = (initialCategory, categories) => ({
    activeCategory: categories.includes(initialCategory) ? initialCategory : 'All',
    categories,
    select(category) {
        this.activeCategory = category;
        const url = new URL(window.location.href);
        category === 'All' ? url.searchParams.delete('category') : url.searchParams.set('category', category);
        history.replaceState({}, '', url);
    },
    visibleCount() { return [...document.querySelectorAll('[data-shop-product-category]')].filter(node => this.activeCategory === 'All' || node.dataset.shopProductCategory === this.activeCategory).length; },
});
const waggiesProductDetail = (productId, cartItem) => ({
    productId,
    cartItem,
    quantity: 1,
    increase() { this.quantity += 1; },
    decrease() { this.quantity = Math.max(1, this.quantity - 1); },
    addToCart() { window.dispatchEvent(new CustomEvent('waggies:add-item', { detail: { ...this.cartItem, quantity: this.quantity } })); },
});
const waggiesRecentlyViewed = (currentProductId, products) => ({
    currentProductId,
    products,
    visibleIds: [],
    init() {
        this.load();
        window.addEventListener('storage', event => { if (event.key === 'waggies-recently-viewed') this.load(); });
        window.addEventListener('waggies-recently-viewed-change', () => this.load());
        const viewed = this.read();
        const next = [currentProductId, ...viewed.filter(id => id !== currentProductId)].slice(0, 10);
        localStorage.setItem('waggies-recently-viewed', JSON.stringify(next));
        this.load();
        window.dispatchEvent(new Event('waggies-recently-viewed-change'));
    },
    read() { try { const raw = JSON.parse(localStorage.getItem('waggies-recently-viewed') || '[]'); return Array.isArray(raw) ? raw : []; } catch { return []; } },
    load() { this.visibleIds = this.read().filter(id => id !== this.currentProductId).slice(0, 6); },
    isVisible(id) { return this.visibleIds.includes(id); },
    hasVisible() { return this.visibleIds.length > 0; },
});
const waggiesToasts = () => ({
    items: [],
    init() {
        this.listen();
    },
    listen() {
        window.addEventListener('waggies:toast', event => this.add(event.detail));
    },
    add(message) {
        const id = Date.now();
        this.items.push({ id, message });
        setTimeout(() => this.items = this.items.filter(item => item.id !== id), 4500);
    },
});
const waggiesShare = (title, description) => ({ copied: false, checkIcon: '<span class="inline-block h-5 w-5 bg-current" style="mask:url(/icons/material-symbols/outlined/check.svg) center/contain no-repeat;-webkit-mask:url(/icons/material-symbols/outlined/check.svg) center/contain no-repeat"></span>', icon(name) { const material = ['link', 'email'].includes(name); const file = name === 'email' ? 'mail' : name; const root = material ? '/icons/material-symbols/outlined/' : '/icons/brands/'; return `<span class="inline-block h-5 w-5 bg-current" style="mask:url(${root}${file}.svg) center/contain no-repeat;-webkit-mask:url(${root}${file}.svg) center/contain no-repeat"></span>`; }, share(type) { const url = window.location.href; if (type === 'link') { navigator.clipboard?.writeText(url).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); return; } const targets = { whatsapp: `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, x: `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, email: `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(description + '\n\n' + url)}` }; if (targets[type]) window.open(targets[type], '_blank', 'noopener,noreferrer'); } });
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
const faqPage = (faqs, categories) => ({
    faqs, categories, active: new URLSearchParams(window.location.search).get('category') || 'all', query: '',
    popular: [{ id: 19, label: 'How do I make a booking?' }, { id: 1, label: 'What do I need to bring for check-in?' }, { id: 10, label: 'How early to plan a pet relocation?' }],
    init() { if (!this.categories.includes(this.active)) this.active = 'all'; },
    select(category) { this.active = category; const url = new URL(window.location.href); category === 'all' ? url.searchParams.delete('category') : url.searchParams.set('category', category); history.replaceState({}, '', url); },
    visibleCategories() { return this.active === 'all' ? this.categories : this.categories.filter(category => category === this.active); },
    results() { const q = this.query.trim().toLowerCase(); return q ? this.faqs.filter(faq => faq.question.toLowerCase().includes(q) || faq.answer.toLowerCase().includes(q)) : []; },
    highlight(text) { const escaped = text.replace(/[&<>\"']/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '\"': '&quot;', "'": '&#039;' }[char])); const q = this.query.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); return q ? escaped.replace(new RegExp(`(${q})`, 'gi'), '<mark class="rounded bg-primary/15 px-0.5 py-0.5 text-primary-dark">$1</mark>') : escaped; },
    popularClick(id) { const faq = this.faqs.find(item => item.id === id); if (!faq) return; this.select(faq.category); requestAnimationFrame(() => requestAnimationFrame(() => { const node = document.querySelector(`[data-faq-id="${id}"]`); if (node) { node.open = true; node.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'center' }); node.focus(); } })); },
    tabKeydown(event) { const tabs = [...event.currentTarget.querySelectorAll('[role=tab]')]; const index = tabs.indexOf(document.activeElement); if (index < 0) return; const delta = ['ArrowRight', 'ArrowDown'].includes(event.key) ? 1 : ['ArrowLeft', 'ArrowUp'].includes(event.key) ? -1 : 0; if (delta || event.key === 'Home' || event.key === 'End') { event.preventDefault(); const next = event.key === 'Home' ? 0 : event.key === 'End' ? tabs.length - 1 : (index + delta + tabs.length) % tabs.length; tabs[next].focus(); tabs[next].click(); } },
});
const homeTestimonials = (testimonials) => ({
    testimonials,
    active: 0,
    hovered: false,
    timer: null,
    init() {
        this.timer = setInterval(() => { if (!this.hovered) this.active = (this.active + 1) % this.testimonials.length; }, 6000);
    },
    select(index) { this.active = index; },
});

const testimonialsGrid = (items) => ({
    items, selected: new URLSearchParams(window.location.search).get('service') || 'all',
    init() { if (this.selected !== 'all' && !this.items.some(item => item.service === this.selected)) this.selected = 'all'; },
    select(service) { this.selected = service; const url = new URL(window.location.href); service === 'all' ? url.searchParams.delete('service') : url.searchParams.set('service', service); history.replaceState({}, '', url); },
    filteredCount() { return this.selected === 'all' ? this.items.length : this.items.filter(item => item.service === this.selected).length; },
});

const galleryLightbox = (images, categories) => ({
    images, categories, activeCategory: new URLSearchParams(window.location.search).get('category') || 'All', lightboxIndex: null, pointerStartX: null, pointerStartY: null,
    init() { if (this.activeCategory !== 'All' && !this.categories.includes(this.activeCategory)) this.activeCategory = 'All'; this.$watch('lightboxIndex', value => { document.body.style.overflow = value === null ? '' : 'hidden'; }); },
    visibleImages() { return this.activeCategory === 'All' ? this.images : this.images.filter(image => image.category === this.activeCategory); },
    isVisible(image) { return this.activeCategory === 'All' || image.category === this.activeCategory; },
    cellAspect(index) { return ['aspect-[4/5]', 'aspect-square', 'aspect-[3/4]', 'aspect-[4/3]', 'aspect-[5/6]'][index % 5]; },
    selectCategory(category) { this.activeCategory = category; this.lightboxIndex = null; const url = new URL(window.location.href); category === 'All' ? url.searchParams.delete('category') : url.searchParams.set('category', category); history.replaceState({}, '', url); },
    visibleIndex(index) { return this.visibleImages().findIndex(image => image === this.images[index]); },
    openAt(index) { this.lightboxIndex = this.visibleIndex(index); },
    currentImage() { return this.lightboxIndex === null ? null : this.visibleImages()[this.lightboxIndex]; },
    closeLightbox() { this.lightboxIndex = null; },
    goNext() { if (this.lightboxIndex === null) return; this.lightboxIndex = (this.lightboxIndex + 1) % this.visibleImages().length; },
    goPrev() { if (this.lightboxIndex === null) return; this.lightboxIndex = (this.lightboxIndex - 1 + this.visibleImages().length) % this.visibleImages().length; },
    pointerStart(event) { this.pointerStartX = event.clientX; this.pointerStartY = event.clientY; },
    pointerEnd(event) { if (this.pointerStartX === null || this.pointerStartY === null) return; const dx = event.clientX - this.pointerStartX; const dy = event.clientY - this.pointerStartY; this.pointerStartX = null; this.pointerStartY = null; if (Math.abs(dx) < 50 || Math.abs(dx) < Math.abs(dy)) return; dx < 0 ? this.goNext() : this.goPrev(); },
    lockScroll() { if (this.lightboxIndex === null) document.body.style.overflow = ''; },
});

const testimonialForm = (submitUrl) => ({
    step: 0, submitted: false, submitting: false, serverError: '', errors: {}, services: ['Boarding', 'Grooming', 'Vet Care', 'Training', 'Transport', 'Relocation'], petTypes: ['Dog', 'Cat', 'Bird', 'Rabbit', 'Reptile', 'Other'], data: { rating: 0, service: '', title: '', story: '', authorName: '', authorLocation: '', petName: '', petType: '', photoUrl: undefined, consent: false },
    get stepIndex() { return this.step; },
    clear(field) { delete this.errors[field]; },
    validateExperience() { const errors = {}; if (!Number.isInteger(this.data.rating) || this.data.rating < 1 || this.data.rating > 5) errors.rating = 'Please select a star rating.'; if (!this.services.includes(this.data.service)) errors.service = this.data.service ? 'Please choose a valid service.' : 'Please select the service you used.'; if (!this.data.title) errors.title = 'Please give your experience a short title.'; else if (this.data.title.length > 80) errors.title = 'Title should be 80 characters or less.'; if (this.data.story.length < 50) errors.story = 'Please share at least 50 characters about your experience.'; else if (this.data.story.length > 2000) errors.story = 'Story should be 2000 characters or less.'; return errors; },
    validateAbout() { const errors = {}; if (this.data.authorName.trim().length < 2) errors.authorName = 'Please enter your name (min 2 characters).'; else if (this.data.authorName.length > 60) errors.authorName = 'Name should be 60 characters or less.'; if (this.data.authorLocation.trim().length < 2) errors.authorLocation = 'Please enter your area (e.g. Maitama, Abuja).'; else if (this.data.authorLocation.length > 80) errors.authorLocation = 'Location should be 80 characters or less.'; if (this.data.petName.length > 60) errors.petName = 'Pet name should be 60 characters or less.'; if (!this.petTypes.includes(this.data.petType)) errors.petType = this.data.petType ? 'Please choose a valid pet type.' : 'Please select your pet type.'; return errors; },
    next() { const errors = this.step === 0 ? this.validateExperience() : this.validateAbout(); this.errors = errors; if (!Object.keys(errors).length) this.step += 1; },
    back() { if (this.step > 0) { this.step -= 1; this.errors = {}; } },
    photoChange(event) { const file = event.target.files?.[0]; if (!file) return; if (!file.type.startsWith('image/')) { this.errors.photo = 'Please choose an image file.'; return; } if (file.size > 4 * 1024 * 1024) { this.errors.photo = 'Please choose an image under 4 MB.'; return; } const reader = new FileReader(); reader.onload = () => { this.data.photoUrl = typeof reader.result === 'string' ? reader.result : undefined; this.clear('photo'); }; reader.readAsDataURL(file); },
    removePhoto() { this.data.photoUrl = undefined; const input = document.getElementById('testimonial-photo'); if (input) input.value = ''; },
    async submit(event) {
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
                    this.errors = Object.fromEntries(Object.entries(payload.errors ?? {}).map(([field, messages]) => [field, messages[0]]));
                    return;
                }

                throw new Error('The testimonial could not be submitted. Please try again.');
            }

            this.submitted = true;
            window.dispatchEvent(new CustomEvent('waggies:toast', { detail: 'Thank you! Your testimonial has been submitted for review.' }));
            setTimeout(() => {
                this.submitted = false;
                this.step = 0;
                this.data = { rating: 0, service: '', title: '', story: '', authorName: '', authorLocation: '', petName: '', petType: '', photoUrl: undefined, consent: false };
                this.errors = {};
            }, 3000);
        } catch (error) {
            this.serverError = error.message;
        } finally {
            this.submitting = false;
        }
    },
});

const waggiesGuidesIndex = initialCategory => ({
    activeCategory: initialCategory || 'All',
    select(category) {
        this.activeCategory = category;
        const url = new URL(window.location.href);
        category === 'All' ? url.searchParams.delete('category') : url.searchParams.set('category', category);
        url.searchParams.delete('page');
        history.replaceState({}, '', url);
    },
});

const waggiesKnowledgeBase = (items, categories, initialCategory, initialSearch, initialPage = 1) => ({
    items,
    categories,
    activeCategory: initialCategory || 'All',
    search: initialSearch || '',
    page: Number(initialPage) || 1,
    pageSize: 9,
    timer: null,
    init() { this.page = Math.min(Math.max(this.page, 1), this.totalPages()); this.$watch('search', () => { this.page = 1; this.syncUrl(true); }); },
    select(category) { this.activeCategory = category; this.page = 1; this.syncUrl(true); },
    syncUrl(resetPage = false) {
        clearTimeout(this.timer);
        this.timer = setTimeout(() => {
            const url = new URL(window.location.href);
            this.activeCategory === 'All' ? url.searchParams.delete('category') : url.searchParams.set('category', this.activeCategory);
            const query = this.search.trim();
            query ? url.searchParams.set('q', query) : url.searchParams.delete('q');
            if (resetPage) url.searchParams.delete('page');
            history.replaceState({}, '', url);
        }, 350);
    },
    filteredItems() {
        const query = this.search.trim().toLowerCase();
        return this.items.filter(item => (this.activeCategory === 'All' || item.category === this.activeCategory) && (!query || item.title.toLowerCase().includes(query) || item.excerpt.toLowerCase().includes(query)));
    },
    matches(index) {
        const item = this.items[index];
        const query = this.search.trim().toLowerCase();
        const filtered = this.filteredItems();
        const position = filtered.indexOf(item);
        return position >= (this.page - 1) * this.pageSize && position < this.page * this.pageSize && (this.activeCategory === 'All' || item.category === this.activeCategory) && (!query || item.title.toLowerCase().includes(query) || item.excerpt.toLowerCase().includes(query));
    },
    visibleCount() { return this.filteredItems().slice((this.page - 1) * this.pageSize, this.page * this.pageSize).length; },
    totalPages() { return Math.max(1, Math.ceil(this.filteredItems().length / this.pageSize)); },
    rangeStart() { return (this.page - 1) * this.pageSize + 1; },
    rangeEnd() { return Math.min(this.page * this.pageSize, this.filteredItems().length); },
    pageNumbers() { return Array.from({ length: this.totalPages() }, (_, i) => i + 1); },
    pageHref(page) { const url = new URL(window.location.href); page > 1 ? url.searchParams.set('page', page) : url.searchParams.delete('page'); return url.toString(); },
});

const waggiesArticleToc = () => ({
    activeId: '',
    links: [],
    init() {
        this.links = [...this.$el.querySelectorAll('[data-toc-link]')];
        const ids = new Set(this.links.map(link => link.dataset.tocLink));
        if (!this.links.length) return;
        this.setActive(ids.has(window.location.hash.slice(1)) ? window.location.hash.slice(1) : this.links[0].dataset.tocLink);

        const observer = new IntersectionObserver(entries => {
            const visible = entries.filter(entry => entry.isIntersecting).sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top);
            if (visible[0]) this.setActive(visible[0].target.id);
        }, { rootMargin: '-104px 0px -62% 0px', threshold: [0, 0.25, 1] });
        this.links.forEach(link => { const heading = document.getElementById(link.dataset.tocLink); if (heading) observer.observe(heading); });
        this.$cleanup = () => observer.disconnect();
        window.addEventListener('hashchange', this.syncHash = () => { const id = window.location.hash.slice(1); if (ids.has(id)) this.setActive(id); });
    },
    setActive(id) {
        this.activeId = id;
        this.links.forEach(link => {
            const active = link.dataset.tocLink === id;
            link.classList.toggle('border-primary', active);
            link.classList.toggle('font-semibold', active);
            link.classList.toggle('text-primary', active);
            link.classList.toggle('border-transparent', !active);
            link.classList.toggle('text-text-muted', !active);
            link.classList.toggle('hover:border-primary/30', !active);
            link.classList.toggle('hover:text-primary-dark', !active);
            active ? link.setAttribute('aria-current', 'location') : link.removeAttribute('aria-current');
        });
    },
});

registerToolComponents(Alpine);
registerPricingCalculator(Alpine);
Alpine.data('waggiesChat', waggiesChat);
Alpine.data('waggiesNavbar', waggiesNavbar);
Alpine.data('waggiesSearch', waggiesSearch);
Alpine.data('waggiesCart', waggiesCart);
Alpine.data('waggiesCartIndicator', waggiesCartIndicator);
Alpine.data('waggiesShopIndex', waggiesShopIndex);
Alpine.data('waggiesProductDetail', waggiesProductDetail);
Alpine.data('waggiesRecentlyViewed', waggiesRecentlyViewed);
Alpine.data('waggiesToasts', waggiesToasts);
Alpine.data('waggiesShare', waggiesShare);
Alpine.data('contactRequest', contactRequest);
Alpine.data('faqPage', faqPage);
Alpine.data('homeTestimonials', homeTestimonials);
Alpine.data('testimonialsGrid', testimonialsGrid);
Alpine.data('galleryLightbox', galleryLightbox);
Alpine.data('testimonialForm', testimonialForm);
Alpine.data('waggiesGuidesIndex', waggiesGuidesIndex);
Alpine.data('waggiesKnowledgeBase', waggiesKnowledgeBase);
Alpine.data('waggiesArticleToc', waggiesArticleToc);

document.addEventListener('click', event => {
    const link = event.target.closest?.('a[href]');
    if (!link) return;
    const href = link.getAttribute('href');
    if (href?.startsWith('/') && !href.startsWith('#') && href !== window.location.pathname) {
        const bar = document.querySelector('[data-navigation-progress]');
        if (bar) { bar.hidden = false; requestAnimationFrame(() => bar.classList.add('is-loading')); }
    }
    if (href?.startsWith('#')) {
        const target = document.getElementById(href.slice(1));
        if (target) { event.preventDefault(); target.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }); history.pushState(null, '', href); target.setAttribute('tabindex', '-1'); target.focus({ preventScroll: true }); }
    }
}, true);

enhanceWaggiesSelects();
waggiesSelectObserver.observe(document.body, { childList: true, subtree: true });
Livewire.start();
