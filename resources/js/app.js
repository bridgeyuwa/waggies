import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import { registerCareersComponents } from './alpine/careers';
import { registerContentComponents } from './alpine/content';
import { registerGlobalComponents } from './alpine/global-ui';
import { registerOpeningHoursComponents } from './alpine/opening-hours';
import { registerRequestComponents } from './alpine/requests';
import { registerShopComponents } from './alpine/shop';
import { registerWaggiesSelectEnhancement, syncWaggiesLivewireSelects, syncWaggiesSelects } from './alpine/selects';
import { registerPricingCalculator } from './pricing-calculator';
import { registerToolComponents } from './tools-calculators';
import { registerBookingDraftPersistence } from './booking-wizard';

window.Alpine = Alpine;
window.Livewire = Livewire;

registerToolComponents(Alpine);
registerPricingCalculator(Alpine);
registerGlobalComponents(Alpine);
registerOpeningHoursComponents(Alpine);
registerCareersComponents(Alpine);
registerShopComponents(Alpine);
registerRequestComponents(Alpine);
registerContentComponents(Alpine);

document.addEventListener('click', event => {
    const link = event.target.closest?.('a[href]');
    if (!link) return;
    if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
    if (link.hasAttribute('download') || (link.target && link.target !== '_self')) return;

    const href = link.getAttribute('href');
    let destination;

    try {
        destination = new URL(link.href, window.location.href);
    } catch {
        destination = null;
    }

    const isSameOrigin = destination?.origin === window.location.origin;
    const isHashNavigation = isSameOrigin
        && destination.pathname === window.location.pathname
        && destination.search === window.location.search
        && destination.hash;

    if (isSameOrigin && !isHashNavigation && (destination.pathname !== window.location.pathname || destination.search !== window.location.search)) {
        const bar = document.querySelector('[data-navigation-progress]');
        if (bar) { bar.hidden = false; requestAnimationFrame(() => bar.classList.add('is-loading')); }
    }

    if (href?.startsWith('#')) {
        const target = document.getElementById(href.slice(1));
        if (target) { event.preventDefault(); target.scrollIntoView({ behavior: matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' }); history.pushState(null, '', href); target.setAttribute('tabindex', '-1'); target.focus({ preventScroll: true }); }
    }
}, true);

registerWaggiesSelectEnhancement(Alpine);
registerBookingDraftPersistence();

if (document.querySelector('[wire\\:id]')) {
    Livewire.hook('morphed', () => {
        syncWaggiesSelects();
        syncWaggiesLivewireSelects();
    });
    document.addEventListener('livewire:initialized', () => syncWaggiesSelects(), { once: true });
    Livewire.start();
} else {
    Alpine.start();
}
