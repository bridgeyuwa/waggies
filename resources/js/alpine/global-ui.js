const waggiesChat = () => ({
    showTop: false,
    chatOpen: false,
    input: '',
    sending: false,
    newReplyAvailable: false,
    announcement: '',
    lastFocus: null,
    requestController: null,
    suggestions: [
        'What services do you offer?',
        'What are your opening hours?',
        'How do I make a booking?',
        'What are your prices?',
    ],
    messages: [{
        role: 'assistant',
        text: 'Hi! I’m the Waggies AI assistant. I can help with services, booking, opening hours, and general pet-care information.',
    }],
    init() {
        this.showTop = window.scrollY > 400;
        window.addEventListener('scroll', () => this.showTop = window.scrollY > 400, { passive: true });
        this.$watch('chatOpen', open => {
            if (!open) return;

            this.$nextTick(() => {
                this.$refs.chatInput?.focus();
                this.scrollToLatest(true);
            });
        });
    },
    openChat() {
        this.lastFocus = document.activeElement;
        this.chatOpen = true;
    },
    closeChat() {
        if (!this.chatOpen) return;

        const focusTarget = this.lastFocus;
        this.chatOpen = false;
        this.newReplyAvailable = false;
        this.$nextTick(() => requestAnimationFrame(() => focusTarget?.focus?.()));
    },
    resetChat() {
        if (this.sending) return;

        this.messages = [{
            role: 'assistant',
            text: 'Hi! I’m the Waggies AI assistant. I can help with services, booking, opening hours, and general pet-care information.',
        }];
        this.input = '';
        this.newReplyAvailable = false;
        this.announcement = '';
        this.$nextTick(() => {
            this.$refs.chatInput?.focus();
            this.scrollToLatest(true);
        });
    },
    handleChatKeydown(event) {
        if (event.key === 'Escape') {
            event.preventDefault();
            this.closeChat();
            return;
        }

        if (event.key !== 'Tab') return;

        const focusableElements = this.getFocusableElements();
        if (!focusableElements.length) return;

        const first = focusableElements[0];
        const last = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        }

        if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    },
    getFocusableElements() {
        if (!this.$refs.chatPanel) return [];

        return [...this.$refs.chatPanel.querySelectorAll('button:not([disabled]), a[href], input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])')]
            .filter(element => element.offsetParent !== null && element.getAttribute('aria-hidden') !== 'true');
    },
    sendSuggestion(suggestion) {
        this.input = suggestion;
        this.send();
    },
    isNearBottom(messages = this.$refs.messages) {
        if (!messages) return true;

        return messages.scrollHeight - messages.scrollTop - messages.clientHeight <= 80;
    },
    handleMessagesScroll() {
        if (this.isNearBottom()) this.newReplyAvailable = false;
    },
    scrollToLatest(force = false) {
        this.$nextTick(() => requestAnimationFrame(() => {
            const messages = this.$refs.messages;
            if (!messages) return;

            if (!force && !this.isNearBottom(messages)) {
                this.newReplyAvailable = true;
                return;
            }

            this.newReplyAvailable = false;
            messages.scrollTo({
                top: messages.scrollHeight,
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
            });
        }));
    },
    async send() {
        const value = this.input.trim();
        if (!value || this.sending) return;

        this.messages.push({ role: 'user', text: value });
        this.input = '';
        this.sending = true;
        this.scrollToLatest(true);

        const controller = new AbortController();
        const timeoutId = window.setTimeout(() => controller.abort(), 30000);
        let streamingMessage = null;
        this.requestController = controller;

        try {
            const response = await fetch(document.body.dataset.assistantStreamUrl || document.body.dataset.assistantUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'text/event-stream, application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({
                    message: value,
                    history: this.messages.slice(-8).map(message => ({ role: message.role, content: message.text })),
                }),
                signal: controller.signal,
            });

            if (!response.ok) {
                let payload = {};

                try {
                    payload = await response.json();
                } catch (error) {
                    // The status code still provides the useful failure signal.
                }

                throw new Error(payload.message || 'Assistant unavailable');
            }

            if (response.headers.get('content-type')?.includes('text/event-stream') && response.body) {
                streamingMessage = { role: 'assistant', text: '', streaming: true };
                this.messages.push(streamingMessage);
                await this.consumeStream(response, streamingMessage);
                streamingMessage.streaming = false;
                this.announcement = streamingMessage.text;
            } else {
                const payload = await response.json();
                const shouldScroll = this.isNearBottom();
                this.messages.push({ role: 'assistant', text: payload.message, sources: payload.sources || [] });
                this.announcement = payload.message;
                this.scrollToLatest(shouldScroll);
            }
        } catch (error) {
            if (streamingMessage) {
                this.messages = this.messages.filter(message => message !== streamingMessage);
            }

            const shouldScroll = this.isNearBottom();
            const timedOut = error.name === 'AbortError';
            const errorMessage = timedOut
                ? 'The assistant took too long to respond. Please try again or continue on WhatsApp.'
                : 'I’m temporarily unavailable. Please try again or continue on WhatsApp.';
            this.messages.push({
                role: 'assistant',
                text: errorMessage,
                retryText: value,
                error: true,
            });
            this.announcement = errorMessage;
            this.scrollToLatest(shouldScroll);
        } finally {
            window.clearTimeout(timeoutId);

            if (this.requestController === controller) {
                this.requestController = null;
            }

            this.sending = false;
        }
    },
    async consumeStream(response, message) {
        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';
        let receivedText = false;

        const processEvent = event => {
            const data = event.split(/\r?\n/)
                .filter(line => line.startsWith('data:'))
                .map(line => line.slice(5).trimStart())
                .join('\n');

            if (!data || data === '[DONE]') return;

            let payload;

            try {
                payload = JSON.parse(data);
            } catch (error) {
                return;
            }

            if (payload.type === 'error') {
                throw new Error(payload.error || 'Assistant stream failed');
            }

            if (payload.type === 'citation' && payload.citation) {
                message.sources = [...(message.sources || []), payload.citation]
                    .filter((source, index, sources) => sources.findIndex(item => item.url === source.url) === index);
                return;
            }

            if (payload.type !== 'text_delta' || !payload.delta) return;

            message.text += payload.delta;
            receivedText = true;
            this.scrollToLatest();
        };

        while (true) {
            const { value, done } = await reader.read();
            buffer += decoder.decode(value || new Uint8Array(), { stream: !done });
            const events = buffer.split(/\r?\n\r?\n/);
            buffer = events.pop() || '';
            events.forEach(processEvent);

            if (done) break;
        }

        if (buffer.trim()) processEvent(buffer);
        if (!receivedText) throw new Error('Assistant returned an empty response');
    },
    retry(messageText) {
        if (this.sending) return;

        const errorIndex = this.messages.findIndex(message => message.error && message.retryText === messageText);
        if (errorIndex !== -1) {
            this.messages.splice(errorIndex, 1);
            const previousMessage = this.messages[errorIndex - 1];

            if (previousMessage?.role === 'user' && previousMessage.text === messageText) {
                this.messages.splice(errorIndex - 1, 1);
            }
        }

        this.input = messageText;
        this.send();
    },
    hasSources(message) {
        return (message.sources || []).some(source => this.sourceUrl(source));
    },
    sourceUrl(source) {
        const rawUrl = typeof source === 'object'
            ? source.url || source.href || source.public_url
            : '';

        if (!rawUrl || typeof rawUrl !== 'string') return '';

        try {
            const url = new URL(rawUrl, window.location.origin);
            return url.origin === window.location.origin ? url.href : '';
        } catch (error) {
            return '';
        }
    },
    sourceLabel(source) {
        const label = typeof source === 'object'
            ? source.title || source.name || source.label || source.filename
            : '';

        return label || 'Waggies source';
    },
});

const waggiesNavbar = () => ({
    mobileOpen: false, desktopMenu: null, pinnedMenu: null, timer: null, lastFocus: null, desktopTrigger: null,
    init() { this.$watch('mobileOpen', open => { document.body.classList.toggle('overflow-hidden', open); if (open) { this.lastFocus = document.activeElement; this.$nextTick(() => this.$refs.drawer?.querySelector('button, a')?.focus()); } else { this.lastFocus?.focus?.(); } }); this.$el.addEventListener('keydown', event => { if (!this.mobileOpen || event.key !== 'Tab') return; const nodes = [...this.$refs.drawer.querySelectorAll('a,button')].filter(node => !node.disabled && node.offsetParent !== null); if (!nodes.length) return; const first = nodes[0], last = nodes[nodes.length - 1]; if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); } else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); } }); window.addEventListener('resize', () => { if (this.desktopMenu) this.updateMegaOffset(this.desktopMenu); }); },
    cancelClose() { clearTimeout(this.timer); this.timer = null; },
    hoverOpen(menu) { this.cancelClose(); this.timer = setTimeout(() => { if (!this.pinnedMenu || this.pinnedMenu === menu) { this.updateMegaOffset(menu); this.desktopMenu = menu; } }, 150); },
    hoverClose(menu) { this.cancelClose(); this.timer = setTimeout(() => { if (!this.pinnedMenu && this.desktopMenu === menu) this.desktopMenu = null; }, 150); },
    toggle(menu) { this.cancelClose(); if (this.pinnedMenu === menu) { this.pinnedMenu = null; this.desktopMenu = null; this.desktopTrigger = null; } else { this.updateMegaOffset(menu); this.pinnedMenu = menu; this.desktopMenu = menu; this.desktopTrigger = document.querySelector(`[data-nav-trigger="${menu}"]`); } },
    updateMegaOffset(menu) { const panel = document.querySelector(`#desktop-menu-${menu}`), trigger = document.querySelector(`[data-nav-trigger="${menu}"]`), nav = this.$refs.nav; if (!panel || !trigger || !nav) return; const triggerRect = trigger.getBoundingClientRect(), navRect = nav.getBoundingClientRect(); panel.style.setProperty('--mega-offset', `${navRect.left + navRect.width / 2 - (triggerRect.left + triggerRect.width / 2)}px`); },
    openAndFocus(menu) { this.updateMegaOffset(menu); this.desktopMenu = menu; this.desktopTrigger = document.querySelector(`[data-nav-trigger="${menu}"]`); this.$nextTick(() => { document.querySelector(`#desktop-menu-${menu} a`)?.focus(); }); },
    closeDesktop() { this.cancelClose(); this.pinnedMenu = null; this.desktopMenu = null; this.desktopTrigger = null; },
    openMobile() { this.lastFocus = document.activeElement; this.mobileOpen = true; }, closeMobile() { this.mobileOpen = false; this.$nextTick(() => { if (this.lastFocus?.isConnected) this.lastFocus.focus(); this.lastFocus = null; }); },
    escape() { if (this.mobileOpen) { this.closeMobile(); return; } const trigger = this.desktopTrigger; this.closeDesktop(); trigger?.focus(); },
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
