export const registerBookingDraftPersistence = () => {
    const root = document.querySelector('[data-booking-draft]');

    if (!root) return;

    const modelControls = () => root.querySelectorAll(
        '[data-booking-draft-model], [wire\\:model], [wire\\:model\\.live], [wire\\:model\\.blur], [wire\\:model\\.live\\.blur]',
    );

    const storageKey = root.dataset.bookingDraft;
    const contextKey = root.dataset.bookingContext || '';
    const status = root.querySelector('[data-booking-draft-status]');
    let restoring = false;
    let saveTimer;

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
            if (status) status.textContent = 'Draft saved on this device for this visit.';
        } catch {
            if (status) status.textContent = 'Draft saving is unavailable in this browser.';
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

        if (status) status.textContent = 'Unfinished request restored from this device.';
    };

    root.addEventListener('input', scheduleSave, true);
    root.addEventListener('change', scheduleSave, true);

    applyDraft();

    const bindLivewireEvents = () => {
        if (!window.Livewire?.on) return;

        window.Livewire.on('booking-request-submitted', () => {
            sessionStorage.removeItem(storageKey);
            if (status) status.textContent = 'Draft cleared after your request was sent.';
        });
    };

    if (window.Livewire?.on) {
        bindLivewireEvents();
    } else {
        document.addEventListener('livewire:init', bindLivewireEvents, { once: true });
    }

    const focusStepHeading = () => {
        const heading = root.querySelector('[data-booking-step-heading]');

        if (!heading) return;

        heading.focus({ preventScroll: true });
        heading.scrollIntoView({
            behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth',
            block: 'start',
        });
    };

    const bindStepEvents = () => {
        if (!window.Livewire?.on) return;

        window.Livewire.on('booking-wizard-step-changed', focusStepHeading);
    };

    if (window.Livewire?.on) {
        bindStepEvents();
    } else {
        document.addEventListener('livewire:init', bindStepEvents, { once: true });
    }
};
