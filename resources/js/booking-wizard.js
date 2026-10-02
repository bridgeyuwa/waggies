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

export const registerBookingDraftPersistence = () => {
    const initialRoot = document.querySelector('[data-booking-draft]');

    if (!initialRoot) return;

    const getRoot = () => document.querySelector('[data-booking-draft]');

    const modelControls = () => getRoot()?.querySelectorAll(
        '[data-booking-draft-model], [wire\\:model], [wire\\:model\\.live], [wire\\:model\\.blur], [wire\\:model\\.live\\.blur]',
    ) || [];

    const storageKey = initialRoot.dataset.bookingDraft;
    const contextKey = initialRoot.dataset.bookingContext || '';
    let restoring = false;
    let saveTimer;

    const setStatus = message => {
        const status = getRoot()?.querySelector('[data-booking-draft-status]');

        if (status) status.textContent = message;
    };

    const readDraft = () => {
        try {
            return JSON.parse(sessionStorage.getItem(storageKey) || '{}');
        } catch {
            return {};
        }
    };

    const writeDraft = () => {
        if (restoring) return;

        const draft = readDraft();

        modelControls().forEach(control => {
            const model = control.getAttribute('data-booking-draft-model')
                || control.getAttribute('wire:model')
                || control.getAttribute('wire:model.live')
                || control.getAttribute('wire:model.blur')
                || control.getAttribute('wire:model.live.blur');
            if (!model) return;

            draft[model] = control.type === 'checkbox' ? control.checked : control.value;
        });

        try {
            draft.contextKey = contextKey;
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

    const applyDraft = () => {
        const draft = readDraft();
        if (!Object.keys(draft).length || draft.contextKey !== contextKey) return;

        restoring = true;
        modelControls().forEach(control => {
            const model = control.getAttribute('data-booking-draft-model')
                || control.getAttribute('wire:model')
                || control.getAttribute('wire:model.live')
                || control.getAttribute('wire:model.blur')
                || control.getAttribute('wire:model.live.blur');
            if (!model || !(model in draft)) return;

            const value = draft[model];
            let changed = false;

            if (control.type === 'checkbox') {
                changed = control.checked !== Boolean(value);
                control.checked = Boolean(value);
            } else if (control.value !== value) {
                control.value = value;
                changed = true;
            }

            if (!changed) return;

            control.dispatchEvent(new Event('input', { bubbles: true }));
            control.dispatchEvent(new Event('change', { bubbles: true }));
        });
        restoring = false;

        setStatus('Unfinished request restored from this device.');
    };

    const restoreDraftAfterLivewireInitialisation = () => {
        window.requestAnimationFrame(() => {
            applyDraft();
        });
    };

    const scheduleSaveForBookingControl = event => {
        const root = getRoot();

        if (root?.contains(event.target)) scheduleSave();
    };

    document.addEventListener('input', scheduleSaveForBookingControl, true);
    document.addEventListener('change', scheduleSaveForBookingControl, true);

    applyDraft();

    const bindLivewireEvents = () => {
        if (!window.Livewire?.on) return;

        window.Livewire.on('booking-request-submitted', () => {
            sessionStorage.removeItem(storageKey);
            setStatus('Draft cleared after your request was sent.');
        });
    };

    if (window.Livewire?.on) {
        bindLivewireEvents();
    } else {
        document.addEventListener('livewire:init', bindLivewireEvents, { once: true });
    }

    document.addEventListener('livewire:initialized', restoreDraftAfterLivewireInitialisation, { once: true });

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
