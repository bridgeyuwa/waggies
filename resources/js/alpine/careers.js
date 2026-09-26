import { waggiesDialog } from './global-ui';

const waggiesJobDetails = () => ({
    ...waggiesDialog(),
    open: false,

    init() {
        this.initDialog(() => this.$refs.closeButton);
        this.$watch('open', isOpen => {
            document.body.classList.toggle('overflow-hidden', isOpen);
        });
    },

    close() {
        this.closeDialog();
    },
});

export function registerCareersComponents(Alpine) {
    Alpine.data('waggiesJobDetails', waggiesJobDetails);
}
