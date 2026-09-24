const waggiesShare = (title, description) => ({ copied: false, checkIcon: '<span class="inline-block h-5 w-5 bg-current" style="mask:url(/icons/material-symbols/outlined/check.svg) center/contain no-repeat;-webkit-mask:url(/icons/material-symbols/outlined/check.svg) center/contain no-repeat"></span>', icon(name) { const material = ['link', 'email'].includes(name); const file = name === 'email' ? 'mail' : name; const root = material ? '/icons/material-symbols/outlined/' : '/icons/brands/'; return `<span class="inline-block h-5 w-5 bg-current" style="mask:url(${root}${file}.svg) center/contain no-repeat;-webkit-mask:url(${root}${file}.svg) center/contain no-repeat"></span>`; }, share(type) { const url = window.location.href; if (type === 'link') { navigator.clipboard?.writeText(url).then(() => { this.copied = true; setTimeout(() => this.copied = false, 2000); }); return; } const targets = { whatsapp: `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`, facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, x: `https://twitter.com/intent/tweet?text=${encodeURIComponent(title)}&url=${encodeURIComponent(url)}`, linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`, email: `mailto:?subject=${encodeURIComponent(title)}&body=${encodeURIComponent(description + '\n\n' + url)}` }; if (targets[type]) window.open(targets[type], '_blank', 'noopener,noreferrer'); } });

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
    testimonials: testimonials?.length ? testimonials : [{
        service: 'Client stories',
        quote: 'Verified client stories will appear here as Waggies pet parents share their experience.',
        initial: 'W',
        name: 'Waggies pet parents',
        subtitle: 'Verified stories coming soon',
    }],
    active: 0,
    hovered: false,
    timer: null,
    init() {
        if (this.testimonials.length === 0) return;
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
    cellAspect(index) { return ['aspect-4/5', 'aspect-square', 'aspect-3/4', 'aspect-4/3', 'aspect-5/6'][index % 5]; },
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

export function registerContentComponents(Alpine) {
    Alpine.data('waggiesShare', waggiesShare);
    Alpine.data('faqPage', faqPage);
    Alpine.data('homeTestimonials', homeTestimonials);
    Alpine.data('testimonialsGrid', testimonialsGrid);
    Alpine.data('galleryLightbox', galleryLightbox);
    Alpine.data('waggiesGuidesIndex', waggiesGuidesIndex);
    Alpine.data('waggiesKnowledgeBase', waggiesKnowledgeBase);
    Alpine.data('waggiesArticleToc', waggiesArticleToc);
}
