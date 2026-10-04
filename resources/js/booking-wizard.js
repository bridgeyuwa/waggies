const toIsoDate = date => {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
};

const parseIsoDate = value => {
    if (!/^\d{4}-\d{2}-\d{2}$/.test(value || '')) return null;

    const [year, month, day] = value.split('-').map(Number);
    const date = new Date(year, month - 1, day);

    return date.getFullYear() === year && date.getMonth() === month - 1 && date.getDate() === day
        ? date
        : null;
};

export const registerBookingCalendar = Alpine => {
    Alpine.data('waggiesDatePicker', (config = {}) => ({
        value: config.value || '',
        minimum: config.minimum || '',
        open: false,
        month: '',

        init() {
            this.month = this.toMonth(this.value || this.minimum || toIsoDate(new Date()));
            this.nativeInputHandler = () => {
                this.value = this.$refs.native.value;
                if (this.value) this.month = this.toMonth(this.value);
            };
            this.$refs.native.addEventListener('input', this.nativeInputHandler);
            this.$refs.native.addEventListener('change', this.nativeInputHandler);
            this.minimumObserver = new MutationObserver(() => {
                this.minimum = this.$refs.native.min || this.minimum;

                if (this.minimum && this.month < this.toMonth(this.minimum)) {
                    this.month = this.toMonth(this.minimum);
                }
            });
            this.minimumObserver.observe(this.$refs.native, { attributes: true, attributeFilter: ['min'] });
        },

        destroy() {
            this.$refs.native?.removeEventListener('input', this.nativeInputHandler);
            this.$refs.native?.removeEventListener('change', this.nativeInputHandler);
            this.minimumObserver?.disconnect();
        },

        toMonth(value) {
            const date = parseIsoDate(value) || new Date();

            return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-01`;
        },

        monthDate() {
            return parseIsoDate(this.month) || new Date();
        },

        monthLabel() {
            return new Intl.DateTimeFormat('en-NG', { month: 'long', year: 'numeric' }).format(this.monthDate());
        },

        weekdays() {
            const monday = new Date(2024, 0, 1);

            return Array.from({ length: 7 }, (_, index) => new Intl.DateTimeFormat('en-NG', { weekday: 'short' }).format(new Date(monday.getFullYear(), monday.getMonth(), monday.getDate() + index)));
        },

        days() {
            const month = this.monthDate();
            const firstDay = new Date(month.getFullYear(), month.getMonth(), 1);
            const leadingDays = (firstDay.getDay() + 6) % 7;
            const daysInMonth = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate();
            const totalDays = Math.ceil((leadingDays + daysInMonth) / 7) * 7;

            return Array.from({ length: totalDays }, (_, index) => {
                if (index < leadingDays || index >= leadingDays + daysInMonth) return null;

                return toIsoDate(new Date(month.getFullYear(), month.getMonth(), index - leadingDays + 1));
            });
        },

        isDisabled(value) {
            return !value || (this.minimum && value < this.minimum);
        },

        isSelected(value) {
            return value === this.value;
        },

        isToday(value) {
            return value === toIsoDate(new Date());
        },

        isBeforeMinimumMonth() {
            return this.minimum && this.month < this.toMonth(this.minimum);
        },

        changeMonth(offset) {
            const month = this.monthDate();
            const next = new Date(month.getFullYear(), month.getMonth() + offset, 1);
            const nextMonth = toIsoDate(next);

            if (offset < 0 && this.minimum && nextMonth < this.toMonth(this.minimum)) return;

            this.month = nextMonth;
        },

        choose(value) {
            if (this.isDisabled(value)) return;

            this.value = value;
            this.$refs.native.value = value;
            this.$refs.native.dispatchEvent(new Event('input', { bubbles: true }));
            this.$refs.native.dispatchEvent(new Event('change', { bubbles: true }));
            this.open = false;
            this.$nextTick(() => this.$refs.trigger?.focus());
        },

        formattedValue() {
            if (!this.value) return '';

            return new Intl.DateTimeFormat('en-NG', { dateStyle: 'medium', timeZone: 'Africa/Lagos' }).format(new Date(`${this.value}T00:00:00`));
        },
    }));
};

export const registerBookingClearDialog = Alpine => {
    Alpine.data('waggiesBookingClearDialog', () => ({
        open: false,
        lastFocus: null,

        openDialog() {
            if (this.open) return;

            this.lastFocus = document.activeElement;
            this.open = true;
            this.$nextTick(() => this.$refs.cancelButton?.focus());
        },

        closeDialog() {
            if (!this.open) return;

            const focusTarget = this.lastFocus;

            this.open = false;
            this.lastFocus = null;
            this.$nextTick(() => window.requestAnimationFrame(() => focusTarget?.focus?.()));
        },

        confirmClear() {
            if (!this.open) return;

            this.closeDialog();
            this.$wire.clearBooking();
        },

        handleKeydown(event) {
            if (event.key === 'Escape') {
                event.preventDefault();
                this.closeDialog();

                return;
            }

            if (event.key !== 'Tab') return;

            const focusableElements = this.getFocusableElements();

            if (focusableElements.length === 0) return;

            const first = focusableElements[0];
            const last = focusableElements[focusableElements.length - 1];

            if (!this.$refs.dialog.contains(document.activeElement) || document.activeElement === this.$refs.dialog) {
                event.preventDefault();
                (event.shiftKey ? last : first).focus();

                return;
            }

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        },

        getFocusableElements() {
            if (!this.$refs.dialog) return [];

            return [...this.$refs.dialog.querySelectorAll(
                'button:not([disabled]), a[href], input:not([disabled]), textarea:not([disabled]), select:not([disabled]), [tabindex]:not([tabindex="-1"])',
            )].filter(element => element.getClientRects().length > 0 && element.getAttribute('aria-hidden') !== 'true');
        },
    }));
};

export const registerBookingDraftPersistence = () => {
    const initialRoot = document.querySelector('[data-booking-draft]');

    if (!initialRoot) return;

    const getRoot = () => document.querySelector('[data-booking-draft]');
    const baseStorageKey = initialRoot.dataset.bookingDraft;
    const contextKey = initialRoot.dataset.bookingContext || '';
    const storageKey = `${baseStorageKey}:${encodeURIComponent(contextKey || 'generic')}`;
    const maxAge = Number(initialRoot.dataset.bookingDraftMaxAge || 86400000);
    let restoring = false;
    let ignoreSavesUntil = 0;
    let saveTimer;

    const setStatus = (message, actions = {}) => {
        const status = getRoot()?.querySelector('[data-booking-draft-status]');

        if (!status) return;

        status.replaceChildren();

        if (message) {
            const text = document.createElement('p');
            text.textContent = message;
            status.append(text);
        }

        if (actions.restore || actions.discard) {
            const controls = document.createElement('div');
            controls.className = 'mt-3 flex flex-wrap gap-3';

            if (actions.restore) {
                const restore = document.createElement('button');
                restore.type = 'button';
                restore.className = 'min-h-11 rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white underline-offset-4 hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary';
                restore.textContent = 'Restore saved request';
                restore.addEventListener('click', actions.restore);
                controls.append(restore);
            }

            if (actions.discard) {
                const discard = document.createElement('button');
                discard.type = 'button';
                discard.className = 'min-h-11 rounded-lg border border-primary/20 px-4 py-2 text-sm font-semibold text-primary-dark underline-offset-4 hover:bg-surface-purple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary';
                discard.textContent = 'Start over';
                discard.addEventListener('click', actions.discard);
                controls.append(discard);
            }

            status.append(controls);
        }
    };

    const readDraft = () => {
        try {
            const draft = JSON.parse(sessionStorage.getItem(storageKey) || 'null');

            return draft && typeof draft === 'object' ? draft : null;
        } catch {
            return null;
        }
    };

    const wireComponent = () => {
        const root = getRoot();
        const id = root?.getAttribute('wire:id');

        return id && window.Livewire?.find ? window.Livewire.find(id) : null;
    };

    const stateValue = property => {
        try {
            return wireComponent()?.$get?.(property);
        } catch {
            return undefined;
        }
    };

    const controlModel = control => control.getAttribute('data-booking-draft-model')
        || control.getAttribute('wire:model')
        || control.getAttribute('wire:model.live')
        || control.getAttribute('wire:model.blur')
        || control.getAttribute('wire:model.live.blur');

    const setNestedValue = (target, path, value) => {
        const parts = path.split('.');
        let cursor = target;

        parts.forEach((part, index) => {
            if (index === parts.length - 1) {
                cursor[part] = value;
                return;
            }

            if (cursor[part] === undefined || cursor[part] === null) {
                cursor[part] = /^\d+$/.test(parts[index + 1]) ? [] : {};
            }

            cursor = cursor[part];
        });
    };

    const mergeRenderedControls = draft => {
        const root = getRoot();
        const controls = [...(root?.querySelectorAll(
            '[data-booking-draft-model], [wire\\:model], [wire\\:model\\.live], [wire\\:model\\.blur], [wire\\:model\\.live\\.blur]',
        ) || [])];
        const grouped = new Map();

        controls.forEach(control => {
            const model = controlModel(control);
            if (!model) return;

            if (!grouped.has(model)) grouped.set(model, []);
            grouped.get(model).push(control);
        });

        grouped.forEach((modelControls, model) => {
            const first = modelControls[0];

            if (first.type === 'radio') {
                const selected = modelControls.find(control => control.checked);
                if (selected) setNestedValue(draft, model, selected.value);
                return;
            }

            if (first.type === 'checkbox') {
                const checked = modelControls.filter(control => control.checked);
                setNestedValue(draft, model, modelControls.length > 1 ? checked.map(control => control.value) : Boolean(first.checked));
                return;
            }

            setNestedValue(draft, model, first.value);
        });
    };

    const writeDraft = () => {
        if (restoring || Date.now() < ignoreSavesUntil) return;

        const draft = {
            version: 1,
            contextKey,
            savedAt: Date.now(),
            step: stateValue('step'),
            services: stateValue('services'),
            pets: stateValue('pets'),
            contact: stateValue('contact'),
            submissionToken: stateValue('submissionToken'),
        };

        if (!Array.isArray(draft.services) || !Array.isArray(draft.pets)) return;

        mergeRenderedControls(draft);

        try {
            sessionStorage.setItem(storageKey, JSON.stringify(draft));
            setStatus('Draft saved on this device for this visit.');
        } catch {
            setStatus('Draft saving is unavailable in this browser.');
        }
    };

    const scheduleSave = () => {
        window.clearTimeout(saveTimer);
        saveTimer = window.setTimeout(writeDraft, 250);
    };

    const clearDraft = () => {
        try {
            sessionStorage.removeItem(storageKey);
        } catch {
            // Storage may be unavailable in a private browsing context.
        }
    };

    const restoreDraft = draft => {
        const wire = wireComponent();

        if (!wire?.$call) return;

        restoring = true;
        ignoreSavesUntil = Date.now() + 1500;
        Promise.resolve(wire.$call('restoreDraft', draft)).finally(() => {
            restoring = false;
            setStatus('Your unfinished request was restored on this device.');
        });
    };

    const discardDraft = () => {
        clearDraft();
        ignoreSavesUntil = Date.now() + 1500;
        setStatus('Starting a new request.');

        const wire = wireComponent();
        if (wire?.$call) {
            restoring = true;
            Promise.resolve(wire.$call('resetDraft')).finally(() => {
                restoring = false;
            });
        }
    };

    const loadDraft = () => {
        const draft = readDraft();

        if (!draft || draft.version !== 1 || draft.contextKey !== contextKey || !Array.isArray(draft.services) || !Array.isArray(draft.pets)) {
            if (draft) clearDraft();
            return;
        }

        const isStale = !Number.isFinite(draft.savedAt) || Date.now() - draft.savedAt > maxAge;

        if (isStale) {
            setStatus('You have an older unfinished request. Would you like to restore it?', {
                restore: () => restoreDraft(draft),
                discard: discardDraft,
            });

            return;
        }

        restoreDraft(draft);
    };

    const scheduleSaveForBookingControl = event => {
        const root = getRoot();

        if (root?.contains(event.target)) scheduleSave();
    };

    document.addEventListener('input', scheduleSaveForBookingControl, true);
    document.addEventListener('change', scheduleSaveForBookingControl, true);

    const bindLivewireEvents = () => {
        if (!window.Livewire?.on) return;

        window.Livewire.on('booking-request-submitted', () => {
            clearDraft();
            setStatus('Draft cleared after your request was sent.');
        });
        window.Livewire.on('booking-wizard-draft-restored', () => {
            restoring = false;
            ignoreSavesUntil = Date.now() + 1500;
            setStatus('Your unfinished request was restored on this device.');
        });
        window.Livewire.on('booking-wizard-draft-reset', () => {
            restoring = false;
            clearDraft();
            ignoreSavesUntil = Date.now() + 1500;
            setStatus('Starting a new request.');
        });

        if (window.Livewire.interceptMessage) {
            window.Livewire.interceptMessage(({ message, onSuccess }) => {
                onSuccess(() => {
                    const id = getRoot()?.getAttribute('wire:id');

                    if (message?.component?.id === id) scheduleSave();
                });
            });
        }
    };

    if (window.Livewire?.on) {
        bindLivewireEvents();
    } else {
        document.addEventListener('livewire:init', bindLivewireEvents, { once: true });
    }

    document.addEventListener('livewire:initialized', () => {
        window.requestAnimationFrame(loadDraft);
    }, { once: true });

    const focusStepHeading = () => {
        const heading = getRoot()?.querySelector('[data-booking-step-heading]');

        if (!heading) return;

        heading.focus({ preventScroll: true });
        heading.scrollIntoView({
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
            block: 'start',
        });
    };

    const focusErrorSummary = () => {
        const summary = getRoot()?.querySelector('[data-booking-error-summary]');

        if (!summary) return;

        summary.scrollIntoView({
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
            block: 'start',
        });
        summary.focus({ preventScroll: true });
    };

    const focusTarget = target => {
        if (!target) return;

        window.requestAnimationFrame(() => {
            const element = document.getElementById(target);

            if (!element) return;

            element.scrollIntoView({
                behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
                block: 'center',
            });

            if (typeof element.focus === 'function') {
                element.setAttribute('tabindex', '-1');
                element.focus({ preventScroll: true });
            }
        });
    };

    const announce = message => {
        const announcement = getRoot()?.querySelector('[data-booking-announcement]');

        if (!announcement || !message) return;

        announcement.textContent = '';
        window.requestAnimationFrame(() => { announcement.textContent = message; });
    };

    const bindStepEvents = () => {
        if (!window.Livewire?.on) return;

        window.Livewire.on('booking-wizard-step-changed', focusStepHeading);
        window.Livewire.on('booking-wizard-validation-failed', focusErrorSummary);
        window.Livewire.on('booking-wizard-focus-target', ({ target }) => focusTarget(target));
        window.Livewire.on('booking-wizard-announcement', ({ message }) => announce(message));
    };

    if (window.Livewire?.on) {
        bindStepEvents();
    } else {
        document.addEventListener('livewire:init', bindStepEvents, { once: true });
    }
};
