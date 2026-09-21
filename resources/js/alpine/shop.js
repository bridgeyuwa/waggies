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
    Alpine.data('waggiesRecentlyViewed', waggiesRecentlyViewed);
}
