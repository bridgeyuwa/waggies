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
    listbox.className = 'waggies-select-options fixed z-layer-navigation min-w-32 overflow-x-hidden overflow-y-auto rounded-lg border border-primary/15 bg-white p-1 shadow-lg outline-none';
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

export function registerWaggiesSelectEnhancement() {
    enhanceWaggiesSelects();

    waggiesSelectObserver.observe(document.body, { childList: true, subtree: true });
}
