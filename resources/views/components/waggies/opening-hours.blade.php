@props(['schedule'])

<section
    x-data="waggiesOpeningHours({{ Illuminate\Support\Js::from($schedule) }})"
    class="overflow-hidden rounded-2xl border border-primary/10 bg-white shadow-sm"
    aria-labelledby="opening-hours-title"
>
    <div class="flex flex-col gap-4 border-b border-primary/10 px-4 py-5 sm:px-6">
        <div class="flex flex-wrap items-center gap-x-3 gap-y-2">
            <span
                class="inline-flex items-center gap-2 rounded-full px-3.5 py-1.5 text-sm font-semibold"
                :class="schedule.status.isOpen ? 'bg-success-light text-green-900' : 'bg-surface-muted text-primary-dark'"
                aria-live="polite"
            >
                <span class="h-2 w-2 rounded-full" :class="schedule.status.isOpen ? 'bg-success' : 'bg-primary-dark/35'" aria-hidden="true"></span>
                <span x-text="schedule.status.label"></span>
            </span>
            <p class="text-sm text-primary-dark/70" x-text="statusDetail()"></p>
            <p class="text-xs text-primary-dark/50">Times shown in <span x-text="schedule.timezone"></span> (WAT, UTC+1)</p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="inline-flex w-fit rounded-xl border border-primary/15 bg-surface p-1" role="group" aria-label="Schedule view">
                <button
                    type="button"
                    class="min-h-10 rounded-lg px-3.5 text-sm font-semibold transition-colors sm:px-4"
                    :class="view === 'daily' ? 'bg-white text-primary-dark shadow-sm' : 'text-primary-dark/60 hover:text-primary-dark'"
                    :aria-pressed="view === 'daily'"
                    @click="view = 'daily'"
                >
                    Every day
                </button>
                <button
                    type="button"
                    class="min-h-10 rounded-lg px-3.5 text-sm font-semibold transition-colors sm:px-4"
                    :class="view === 'grouped' ? 'bg-white text-primary-dark shadow-sm' : 'text-primary-dark/60 hover:text-primary-dark'"
                    :aria-pressed="view === 'grouped'"
                    @click="view = 'grouped'"
                >
                    Grouped days
                </button>
            </div>

            <div class="inline-flex w-fit rounded-xl border border-primary/15 bg-white p-1" role="group" aria-label="Time format">
                <button
                    type="button"
                    class="min-h-10 rounded-lg px-3.5 text-sm font-semibold transition-colors"
                    :class="format === '24h' ? 'bg-primary text-white shadow-sm' : 'text-primary-dark/60 hover:text-primary-dark'"
                    :aria-pressed="format === '24h'"
                    @click="format = '24h'"
                >
                    24h
                </button>
                <button
                    type="button"
                    class="min-h-10 rounded-lg px-3.5 text-sm font-semibold transition-colors"
                    :class="format === '12h' ? 'bg-primary text-white shadow-sm' : 'text-primary-dark/60 hover:text-primary-dark'"
                    :aria-pressed="format === '12h'"
                    @click="format = '12h'"
                >
                    12h
                </button>
            </div>
        </div>
    </div>

    <div class="px-4 sm:px-6">
        <h2 id="opening-hours-title" class="sr-only">Opening hours</h2>
        <div class="divide-y divide-primary/10">
            <template x-for="row in rows" :key="view === 'daily' ? row.date : row.label">
                <div
                    class="grid gap-3 py-4 sm:grid-cols-[9.5rem_minmax(0,1fr)_minmax(12rem,1.25fr)] sm:items-center sm:gap-5"
                    :class="row.isToday ? 'bg-surface-purple/35' : ''"
                    :aria-current="row.isToday ? 'date' : null"
                >
                    <div class="flex items-center gap-2">
                        <span class="text-base font-semibold text-primary-dark" x-text="view === 'daily' ? row.dateLabel : row.label"></span>
                        <span x-show="row.isToday" x-cloak class="rounded-full bg-primary/10 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-primary">Today</span>
                    </div>

                    <div class="relative h-3 overflow-hidden rounded-full border border-primary/10 bg-surface" aria-hidden="true">
                        <template x-for="interval in row.intervals" :key="`${row.date || row.label}-${interval.start}`">
                            <span
                                class="absolute inset-y-0 rounded-full bg-primary"
                                :style="`left: ${interval.left}%; width: ${interval.width}%;`"
                            ></span>
                        </template>
                    </div>

                    <p class="text-sm font-medium tracking-tight text-primary-dark sm:text-[0.95rem]" :class="row.isClosed ? 'text-primary-dark/50' : ''" x-text="row.isClosed ? 'Closed' : rowHours(row).replaceAll('–', ' – ')"></p>
                </div>
            </template>
        </div>
    </div>

    <template x-if="schedule.exceptions.length">
        <div class="border-t border-primary/15 px-4 py-5 sm:px-6">
            <h3 class="text-sm font-semibold text-primary-dark">Exceptions</h3>
            <ul class="mt-3 divide-y divide-primary/10">
                <template x-for="exception in schedule.exceptions" :key="exception.key">
                    <li class="flex flex-col gap-1 py-2 first:pt-0 sm:flex-row sm:items-baseline sm:justify-between sm:gap-6">
                        <p class="min-w-0 text-sm text-primary-dark/75">
                            <span x-text="exception.dateLabel"></span>
                            <template x-if="exception.label">
                                <span> · <span x-text="exception.label"></span></span>
                            </template>
                        </p>
                        <p class="shrink-0 text-sm font-semibold text-primary-dark" :class="exception.isClosed ? 'text-primary-dark/55' : ''" x-text="exceptionHours(exception).replaceAll('–', ' – ')"></p>
                    </li>
                </template>
            </ul>
        </div>
    </template>
</section>
