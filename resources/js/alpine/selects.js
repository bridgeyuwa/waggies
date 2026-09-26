function initWaggiesSelect(select) {
    if (!(select instanceof HTMLSelectElement)
        || !select.closest('main')
        || select.closest('[wire\\:id]')
        || select.dataset.waggiesSelectEnhanced === 'true'
        || select.multiple
        || select.size > 1) return;

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
    const enabledOptionButtons = () => optionButtons.filter(optionButton => optionButton.getAttribute('aria-disabled') !== 'true');
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
    select._waggiesSelectSync = sync;
    const renderOptions = () => {
        listbox.replaceChildren();
        optionButtons = options().map(option => {
            const optionButton = document.createElement('div');
            optionButton.className = 'flex min-h-[44px] w-full cursor-default items-center gap-2 rounded-md py-3 pl-3 pr-9 text-left text-sm text-primary-dark outline-none select-none hover:bg-surface-purple focus:bg-surface-purple';
            optionButton.dataset.value = option.value;
            optionButton.setAttribute('role', 'option');
            optionButton.setAttribute('aria-disabled', option.disabled ? 'true' : 'false');
            optionButton.tabIndex = -1;
            optionButton.dataset.state = 'unchecked';
            optionButton.textContent = option.textContent?.trim() || '';
            optionButton.dataset.label = optionButton.textContent;
            optionButton.classList.toggle('pointer-events-none', option.disabled);
            optionButton.classList.toggle('cursor-not-allowed', option.disabled);
            optionButton.classList.toggle('opacity-45', option.disabled);
            const check = document.createElement('img');
            check.src = '/icons/material-symbols/outlined/check.svg';
            check.alt = '';
            check.className = 'pointer-events-none absolute right-3 h-4 w-4';
            check.dataset.waggiesSelectCheck = '';
            optionButton.classList.add('relative');
            optionButton.appendChild(check);
            check.hidden = option.value !== select.value;
            optionButton.addEventListener('click', () => {
                if (option.disabled) return;

                select.value = option.value;
                select.dispatchEvent(new Event('change', { bubbles: true }));
                close();
                button.focus();
            });
            optionButton.addEventListener('focus', () => { optionButton.dataset.highlighted = ''; });
            optionButton.addEventListener('blur', () => { delete optionButton.dataset.highlighted; });
            optionButton.addEventListener('pointermove', () => {
                if (!option.disabled) optionButton.focus({ preventScroll: true });
            });
            optionButton.addEventListener('keydown', event => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    event.stopPropagation();
                    if (!option.disabled) optionButton.click();
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
        const enabled = enabledOptionButtons();
        const selectedButton = selectedIndex >= 0 && optionButtons[selectedIndex]?.getAttribute('aria-disabled') !== 'true'
            ? optionButtons[selectedIndex]
            : focusIndex < 0 ? enabled.at(-1) : enabled[0];
        selectedButton?.focus();
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
        const enabled = enabledOptionButtons();

        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            if (currentIndex >= 0 && document.activeElement?.getAttribute('aria-disabled') !== 'true') document.activeElement.click();

            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            close();
            button.focus();

            return;
        }

        if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
            event.preventDefault();

            if (event.key === 'Home') {
                enabled[0]?.focus();
            } else if (event.key === 'End') {
                enabled.at(-1)?.focus();
            } else {
                const currentEnabledIndex = enabled.indexOf(document.activeElement);
                const fallbackIndex = event.key === 'ArrowDown' ? -1 : enabled.length;
                const nextIndex = currentEnabledIndex < 0
                    ? fallbackIndex
                    : (currentEnabledIndex + (event.key === 'ArrowDown' ? 1 : -1) + enabled.length) % enabled.length;
                enabled[Math.min(enabled.length - 1, Math.max(0, nextIndex))]?.focus();
            }

            return;
        }

        if (event.key.length === 1 && !event.ctrlKey && !event.altKey && !event.metaKey) {
            event.preventDefault();
            typeahead += event.key.toLowerCase();
            const match = enabled.find(optionButton => optionButton.dataset.label?.toLowerCase().startsWith(typeahead));
            if (match) match.focus();
            if (typeaheadTimer) clearTimeout(typeaheadTimer);
            typeaheadTimer = setTimeout(() => { typeahead = ''; typeaheadTimer = null; }, 1000);
        }
    });
    select.addEventListener('change', sync);
    document.addEventListener('click', event => { if (!wrapper.contains(event.target) && !listbox.contains(event.target)) close(); });
    document.addEventListener('focusin', event => { if (!wrapper.contains(event.target) && !listbox.contains(event.target)) close(); });
    window.addEventListener('resize', close);
    window.addEventListener('blur', close);
    window.addEventListener('scroll', () => { if (open) positionMenu(); }, true);

    const observer = new MutationObserver(() => { renderOptions(); });
    observer.observe(select, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'required', 'aria-invalid', 'aria-describedby'] });
    renderOptions();
}

function waggiesLivewireSelect() {
    return {
        open: false,
        options: [],
        selectedValue: '',
        selectedLabel: 'Select...',
        isPlaceholder: true,
        isDisabled: false,
        highlightedIndex: -1,
        typeahead: '',
        typeaheadTimer: null,

        init() {
            this.refreshOptions();
            this.sync();

            this.nativeChangeHandler = () => {
                this.refreshOptions();
                this.sync();
            };
            this.$refs.native.addEventListener('change', this.nativeChangeHandler);

            this.optionObserver = new MutationObserver(() => {
                this.refreshOptions();
                this.sync();
            });
            this.optionObserver.observe(this.$refs.native, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['disabled', 'selected', 'required', 'aria-invalid', 'aria-describedby'],
            });

            this.syncEventHandler = () => {
                this.refreshOptions();
                this.sync();
            };
            window.addEventListener('waggies-livewire-selects-sync', this.syncEventHandler);

            this.documentClickHandler = event => {
                if (!this.$root.contains(event.target) && !this.$refs.listbox?.contains(event.target)) this.close();
            };
            this.focusInHandler = event => {
                if (!this.$root.contains(event.target) && !this.$refs.listbox?.contains(event.target)) this.close();
            };
            this.resizeHandler = () => this.close();
            this.scrollHandler = () => { if (this.open) this.positionMenu(); };
            document.addEventListener('click', this.documentClickHandler);
            document.addEventListener('focusin', this.focusInHandler);
            window.addEventListener('resize', this.resizeHandler);
            window.addEventListener('blur', this.resizeHandler);
            window.addEventListener('scroll', this.scrollHandler, true);
        },

        destroy() {
            this.$refs.native?.removeEventListener('change', this.nativeChangeHandler);
            this.optionObserver?.disconnect();
            window.removeEventListener('waggies-livewire-selects-sync', this.syncEventHandler);
            document.removeEventListener('click', this.documentClickHandler);
            document.removeEventListener('focusin', this.focusInHandler);
            window.removeEventListener('resize', this.resizeHandler);
            window.removeEventListener('blur', this.resizeHandler);
            window.removeEventListener('scroll', this.scrollHandler, true);
            if (this.typeaheadTimer) clearTimeout(this.typeaheadTimer);
        },

        refreshOptions() {
            this.options = [...this.$refs.native.options]
                .filter(option => option.value !== '')
                .map(option => ({
                    value: option.value,
                    label: option.textContent?.trim() || '',
                    disabled: option.disabled,
                }));
        },

        sync() {
            const native = this.$refs.native;
            this.selectedValue = native.value;
            this.selectedLabel = native.value === ''
                ? 'Select...'
                : (native.selectedOptions[0]?.textContent?.trim() || 'Select...');
            this.isPlaceholder = native.value === '';
            this.isDisabled = native.disabled;
            if (this.$refs.trigger) {
                if (native.required) this.$refs.trigger.setAttribute('aria-required', 'true');
                else this.$refs.trigger.removeAttribute('aria-required');
                if (native.getAttribute('aria-describedby')) this.$refs.trigger.setAttribute('aria-describedby', native.getAttribute('aria-describedby'));
                else this.$refs.trigger.removeAttribute('aria-describedby');
                if (native.getAttribute('aria-invalid')) this.$refs.trigger.setAttribute('aria-invalid', native.getAttribute('aria-invalid'));
                else this.$refs.trigger.removeAttribute('aria-invalid');
            }
            if (this.open) this.$nextTick(() => this.positionMenu());
        },

        optionButtons() {
            return this.$refs.listbox ? [...this.$refs.listbox.querySelectorAll('[role="option"]')] : [];
        },

        enabledOptions() {
            return this.options
                .map((option, index) => ({ option, index }))
                .filter(({ option }) => !option.disabled);
        },

        focusOption(index) {
            const option = this.options[index];
            if (!option || option.disabled) return;

            this.highlightedIndex = index;
            this.optionButtons()[index]?.focus({ preventScroll: true });
        },

        toggle() {
            if (this.isDisabled) return;
            this.open ? this.close() : this.openMenu();
        },

        openMenu(direction = 0) {
            if (this.isDisabled || !this.options.length) return;

            this.refreshOptions();
            this.sync();
            this.open = true;
            this.$refs.listbox?.setAttribute('data-state', 'open');
            this.$nextTick(() => {
                this.positionMenu();
                const enabled = this.enabledOptions();
                const selectedIndex = enabled.findIndex(({ index }) => index === this.options.findIndex(option => option.value === this.selectedValue));
                const target = direction < 0
                    ? enabled.at(-1)
                    : direction > 0
                        ? enabled[0]
                        : selectedIndex >= 0 ? enabled[selectedIndex] : enabled[0];

                if (target) this.focusOption(target.index);
            });
        },

        close() {
            this.open = false;
            this.highlightedIndex = -1;
            this.typeahead = '';
            this.$refs.listbox?.setAttribute('data-state', 'closed');
            if (this.typeaheadTimer) {
                clearTimeout(this.typeaheadTimer);
                this.typeaheadTimer = null;
            }
        },

        choose(value) {
            const option = [...this.$refs.native.options].find(candidate => candidate.value === value);
            if (!option || option.disabled) return;

            this.$refs.native.value = value;
            this.$refs.native.dispatchEvent(new Event('change', { bubbles: true }));
            this.close();
            this.$nextTick(() => this.$refs.trigger.focus());
        },

        onListboxKeydown(event) {
            const enabled = this.enabledOptions();
            if (!enabled.length) return;

            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                const activeIndex = this.highlightedIndex >= 0
                    ? this.highlightedIndex
                    : this.options.findIndex(option => option.value === this.selectedValue);
                if (activeIndex >= 0) this.choose(this.options[activeIndex].value);

                return;
            }

            if (event.key === 'Escape') {
                event.preventDefault();
                this.close();
                this.$refs.trigger.focus();

                return;
            }

            if (['ArrowDown', 'ArrowUp', 'Home', 'End'].includes(event.key)) {
                event.preventDefault();
                const currentPosition = enabled.findIndex(({ index }) => index === this.highlightedIndex);
                let targetPosition;

                if (event.key === 'Home') targetPosition = 0;
                else if (event.key === 'End') targetPosition = enabled.length - 1;
                else {
                    const fallback = event.key === 'ArrowDown' ? -1 : enabled.length;
                    const position = currentPosition < 0 ? fallback : currentPosition;
                    targetPosition = (position + (event.key === 'ArrowDown' ? 1 : -1) + enabled.length) % enabled.length;
                }

                this.focusOption(enabled[targetPosition].index);

                return;
            }

            if (event.key.length === 1 && !event.ctrlKey && !event.altKey && !event.metaKey) {
                event.preventDefault();
                this.typeahead += event.key.toLowerCase();
                const match = enabled.find(({ option }) => option.label.toLowerCase().startsWith(this.typeahead));
                if (match) this.focusOption(match.index);
                if (this.typeaheadTimer) clearTimeout(this.typeaheadTimer);
                this.typeaheadTimer = setTimeout(() => {
                    this.typeahead = '';
                    this.typeaheadTimer = null;
                }, 1000);
            }
        },

        positionMenu() {
            const trigger = this.$refs.trigger;
            const listbox = this.$refs.listbox;
            if (!trigger || !listbox || !this.open) return;

            const rect = trigger.getBoundingClientRect();
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
            listbox.dataset.state = 'open';
        },
    };
}

function syncWaggiesSelectPresentation(select) {
    const button = select.parentElement?.querySelector(':scope > button.waggies-select-trigger');
    if (!button) return;

    const selectedValue = select.value;
    const selectedLabel = selectedValue === '' ? 'Select...' : (select.selectedOptions[0]?.textContent?.trim() || 'Select...');
    const value = button.querySelector('span');

    if (value) {
        value.textContent = selectedLabel;
        value.classList.toggle('text-primary-dark/45', selectedValue === '');
        value.classList.toggle('text-primary-dark', selectedValue !== '');
    }

    button.toggleAttribute('data-placeholder', selectedValue === '');

    const listboxId = button.getAttribute('aria-controls');
    const listbox = listboxId ? document.getElementById(listboxId) : null;

    listbox?.querySelectorAll('[role="option"]').forEach(optionButton => {
        const selected = optionButton.dataset.value === selectedValue;
        optionButton.setAttribute('aria-selected', selected ? 'true' : 'false');
        optionButton.dataset.state = selected ? 'checked' : 'unchecked';
        optionButton.querySelector('[data-waggies-select-check]')?.toggleAttribute('hidden', !selected);
    });
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

export function registerWaggiesSelectEnhancement(Alpine) {
    Alpine.data('waggiesLivewireSelect', waggiesLivewireSelect);
    enhanceWaggiesSelects();

    waggiesSelectObserver.observe(document.body, { childList: true, subtree: true });
}

export function syncWaggiesSelects(root = document) {
    root.querySelectorAll?.('select[data-waggies-select-enhanced="true"]').forEach(select => {
        select._waggiesSelectSync?.();
        syncWaggiesSelectPresentation(select);
    });
}

export function syncWaggiesLivewireSelects() {
    window.dispatchEvent(new CustomEvent('waggies-livewire-selects-sync'));
}
