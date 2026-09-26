const waggiesChat = () => ({
    showTop: false,
    chatOpen: false,
    input: '',
    sending: false,
    messages: [{
        role: 'assistant',
        text: 'Hi! I’m the Waggies AI assistant. I can help with services, booking, and general pet-care information.',
    }],
    async send() {
        const value = this.input.trim();
        if (!value || this.sending) return;

        this.messages.push({ role: 'user', text: value });
        this.input = '';
        this.sending = true;

        try {
            const response = await fetch(document.body.dataset.assistantUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    message: value,
                    history: this.messages.slice(-8).map(message => ({ role: message.role, content: message.text })),
                }),
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Assistant unavailable');
            this.messages.push({ role: 'assistant', text: payload.message, sources: payload.sources || [] });
        } catch (error) {
            this.messages.push({ role: 'assistant', text: 'I’m temporarily unavailable. Please contact Waggies directly or continue on WhatsApp.' });
        } finally {
            this.sending = false;
        }
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
    openMobile() { this.lastFocus = document.activeElement; this.mobileOpen = true; }, closeMobile() { this.mobileOpen = false; this.$nextTick(() => { if (this.lastFocus?.isConnected) this.lastFocus.focus(); this.lastFocus = null; }); },
    escape() { if (this.mobileOpen) this.closeMobile(); else this.closeDesktop(); },
});
export const waggiesDialog = () => ({
    dialogState: 'open',
    dialogTrigger: null,
    dialogFallback: null,
    dialogInitialFocus: null,
    initDialog(getInitialFocus, state = 'open') {
        this.dialogState = state;
        this.dialogInitialFocus = getInitialFocus;
        this.$watch(state, isOpen => {
            if (isOpen) {
                this.$nextTick(() => requestAnimationFrame(() => {
                    const target = this.dialogInitialFocus?.() || this.dialogFocusableElements()[0];
                    target?.focus();
                }));

                return;
            }

            this.$nextTick(() => requestAnimationFrame(() => this.restoreDialogFocus()));
        });
    },
    openDialog(trigger, fallback = null) {
        this.dialogTrigger = trigger instanceof HTMLElement ? trigger : document.activeElement;
        this.dialogFallback = fallback instanceof HTMLElement ? fallback : null;
        this.dialogTrigger?.setAttribute('aria-expanded', 'true');
        this[this.dialogState] = true;
    },
    closeDialog() {
        this[this.dialogState] = false;
    },
    dialogFocusableElements() {
        const dialog = this.$refs.dialog || this.$el;

        return [...dialog.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])')]
            .filter(node => node.getClientRects().length > 0 && node.getAttribute('aria-hidden') !== 'true');
    },
    handleDialogKeydown(event) {
        if (event.key === 'Escape') {
            event.preventDefault();
            this.close();

            return;
        }

        if (event.key !== 'Tab') return;

        const focusable = this.dialogFocusableElements();
        if (!focusable.length) return;

        const first = focusable[0];
        const last = focusable[focusable.length - 1];
        const current = document.activeElement;

        if (!this.$el.contains(current)) {
            event.preventDefault();
            (event.shiftKey ? last : first).focus();
        } else if (event.shiftKey && current === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && current === last) {
            event.preventDefault();
            first.focus();
        }
    },
    restoreDialogFocus() {
        const trigger = [this.dialogTrigger, this.dialogFallback].find(node => node?.isConnected && node.getClientRects().length > 0);
        this.dialogTrigger?.setAttribute('aria-expanded', 'false');
        this.dialogFallback?.setAttribute('aria-expanded', 'false');
        trigger?.setAttribute('aria-expanded', 'false');
        trigger?.focus();
        this.dialogTrigger = null;
        this.dialogFallback = null;
    },
});

const waggiesSearch = () => ({
    ...waggiesDialog(),
    open: false, query: '', results: [], loading: false,
    init() { this.initDialog(() => this.$refs.input); this.listen(); },
    listen() { window.addEventListener('waggies:open-search', event => { this.openDialog(event.detail?.trigger, event.detail?.fallback); this.query = ''; this.results = []; }); },
    close() { this.closeDialog(); this.query = ''; this.results = []; },
    async fetchResults() {
        const value = this.query.trim();
        if (!value) { this.results = []; return; }
        this.loading = true;
        try { const response = await fetch(`${document.body.dataset.searchUrl}?q=${encodeURIComponent(value)}`); const data = await response.json(); this.results = data.results ?? []; } catch { this.results = []; } finally { this.loading = false; }
    },
    navigate(href) { this.close(); window.location.href = href; },
});
const waggiesCart = () => ({
    ...waggiesDialog(),
    open: false,
    items: [],
    init() { this.initDialog(() => this.$refs.closeButton); this.listen(); },
    listen() {
        this.load();
        window.addEventListener('waggies:open-cart', event => { this.load(); this.openDialog(event.detail?.trigger, event.detail?.fallback); });
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
    close() { this.closeDialog(); },
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

export function registerGlobalComponents(Alpine) {
    Alpine.data('waggiesChat', waggiesChat);
    Alpine.data('waggiesNavbar', waggiesNavbar);
    Alpine.data('waggiesSearch', waggiesSearch);
    Alpine.data('waggiesCart', waggiesCart);
    Alpine.data('waggiesCartIndicator', waggiesCartIndicator);
    Alpine.data('waggiesToasts', waggiesToasts);
}
