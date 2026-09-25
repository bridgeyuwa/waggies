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

const waggiesProductGallery = images => ({
    images,
    activeIndex: 0,
    lightboxOpen: false,
    lastFocus: null,
    touchStartX: null,
    touchStartY: null,
    touchMoved: false,
    init() {
        this.$watch('lightboxOpen', open => {
            document.body.style.overflow = open ? 'hidden' : '';

            if (open) {
                this.$nextTick(() => this.$refs.lightboxClose?.focus());
            }
        });

        this.$cleanup = () => {
            document.body.style.overflow = '';
        };
    },
    currentImage() {
        return this.images[this.activeIndex] ?? this.images[0] ?? { url: '', thumb: '', alt: '' };
    },
    select(index) {
        if (! this.images[index]) {
            return;
        }

        this.activeIndex = index;
        this.$nextTick(() => {
            this.$refs.thumbnailRail?.querySelector('[aria-current="true"]')?.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'nearest',
            });
        });
    },
    next() {
        if (this.images.length < 2) {
            return;
        }

        this.select((this.activeIndex + 1) % this.images.length);
    },
    previous() {
        if (this.images.length < 2) {
            return;
        }

        this.select((this.activeIndex - 1 + this.images.length) % this.images.length);
    },
    openLightbox() {
        if (this.images.length === 0) {
            return;
        }

        this.lastFocus = document.activeElement;
        this.lightboxOpen = true;
    },
    activateMainImage() {
        if (this.touchMoved) {
            this.touchMoved = false;

            return;
        }

        this.openLightbox();
    },
    closeLightbox() {
        const focusTarget = this.lastFocus;
        this.lightboxOpen = false;
        this.$nextTick(() => (focusTarget?.isConnected ? focusTarget : this.$refs.mainImage)?.focus());
    },
    handleGalleryKeydown(event) {
        if (this.lightboxOpen) {
            return;
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            this.next();
        }

        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            this.previous();
        }
    },
    handleLightboxKeydown(event) {
        if (! this.lightboxOpen) {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            this.closeLightbox();
        }

        if (event.key === 'ArrowRight') {
            event.preventDefault();
            this.next();
        }

        if (event.key === 'ArrowLeft') {
            event.preventDefault();
            this.previous();
        }

        if (event.key === 'Tab') {
            const focusable = [...this.$refs.lightboxDialog.querySelectorAll('button:not([disabled]), [tabindex]:not([tabindex="-1"])')]
                .filter(element => element.getClientRects().length > 0);

            if (!focusable.length) return;

            const first = focusable[0];
            const last = focusable[focusable.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    },
    startTouch(event) {
        const point = event.touches?.[0] ?? event;
        this.touchMoved = false;
        this.touchStartX = point.clientX;
        this.touchStartY = point.clientY;
    },
    endTouch(event) {
        if (this.touchStartX === null || this.touchStartY === null) {
            return;
        }

        const point = event.changedTouches?.[0] ?? event;
        const deltaX = point.clientX - this.touchStartX;
        const deltaY = point.clientY - this.touchStartY;
        this.touchStartX = null;
        this.touchStartY = null;

        if (Math.abs(deltaX) < 48 || Math.abs(deltaX) <= Math.abs(deltaY)) {
            return;
        }

        this.touchMoved = true;
        deltaX < 0 ? this.next() : this.previous();
    },
});

const waggiesRecentlyViewed = currentProductId => ({
    currentProductId,
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

export function registerShopComponents(Alpine) {
    Alpine.data('waggiesShopIndex', waggiesShopIndex);
    Alpine.data('waggiesProductDetail', waggiesProductDetail);
    Alpine.data('waggiesProductGallery', waggiesProductGallery);
    Alpine.data('waggiesRecentlyViewed', waggiesRecentlyViewed);
}
