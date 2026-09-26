// Livewire bundles Alpine; the public layout guard prevents the unused Livewire runtime from auto-starting.
import { Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm.js';
import { registerCareersComponents } from './alpine/careers';
import { registerContentComponents } from './alpine/content';
import { registerGlobalComponents } from './alpine/global-ui';
import { registerOpeningHoursComponents } from './alpine/opening-hours';
import { registerRequestComponents } from './alpine/requests';
import { registerShopComponents } from './alpine/shop';
import { registerWaggiesSelectEnhancement } from './alpine/selects';
import { registerPricingCalculator } from './pricing-calculator';
import { registerToolComponents } from './tools-calculators';

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

registerWaggiesSelectEnhancement();
Alpine.start();
