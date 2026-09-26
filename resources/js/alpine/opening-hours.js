export function registerOpeningHoursComponents(Alpine) {
    Alpine.data('waggiesOpeningHours', schedule => ({
        schedule,
        view: 'daily',
        format: '12h',

        get rows() {
            return this.view === 'grouped'
                ? this.schedule.weekly.grouped
                : this.schedule.weekly.daily;
        },

        rowHours(row) {
            return this.format === '12h' ? row.hours12 : row.hours24;
        },

        exceptionHours(exception) {
            return this.format === '12h' ? exception.hours12 : exception.hours24;
        },

        statusDetail() {
            return this.format === '12h'
                ? this.schedule.status.detail12
                : this.schedule.status.detail24;
        },
    }));
}
