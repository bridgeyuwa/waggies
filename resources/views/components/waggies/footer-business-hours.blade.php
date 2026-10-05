@props([
    'schedule',
    'compact' => false,
])

@if ($compact)
    <section class="border-y border-white/10 py-4" aria-labelledby="compact-footer-business-hours">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between sm:gap-6">
            <div class="min-w-0">
                <h2 id="compact-footer-business-hours" class="text-xs font-semibold uppercase tracking-[0.14em] text-secondary">
                    Business Hours
                </h2>
                <p class="mt-2 text-sm font-semibold text-white">
                    {{ $schedule['status']['label'] }}
                    <span class="text-white/45" aria-hidden="true">·</span>
                    <span class="font-normal text-white/70">{{ $schedule['status']['detail12'] }}</span>
                </p>
                <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-white/60">
                    @foreach ($schedule['weekly']['grouped'] as $hours)
                        <span>
                            <span class="font-medium text-white/75">{{ str_replace('–', ' - ', $hours['label']) }}:</span>
                            {{ str_replace('–', ' - ', $hours['hours12']) }}
                        </span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex min-h-11 shrink-0 items-center gap-1.5 text-sm font-semibold text-secondary underline-offset-4 hover:text-white hover:underline">
                View full hours
                <x-waggies.icon name="arrow-forward" size="16" />
            </a>
        </div>
    </section>
@else
    <section class="mb-10 border-y border-white/10 py-5 lg:mb-12" aria-labelledby="footer-business-hours">
        <div class="flex flex-col gap-4 lg:grid lg:grid-cols-[auto_minmax(0,1fr)_auto] lg:items-center lg:gap-8">
            <h2 id="footer-business-hours" class="text-xs font-semibold uppercase tracking-[0.14em] text-secondary">
                Business Hours
            </h2>
            <div class="flex min-w-0 flex-col gap-2.5 sm:flex-row sm:flex-wrap sm:items-center sm:gap-x-8 sm:gap-y-2">
                <p class="text-sm font-semibold text-white">
                    {{ $schedule['status']['label'] }}
                    <span class="text-white/45" aria-hidden="true">·</span>
                    <span class="font-normal text-white/70">{{ $schedule['status']['detail12'] }}</span>
                </p>
                <div class="flex flex-wrap gap-x-5 gap-y-1 text-xs text-white/60">
                    @foreach ($schedule['weekly']['grouped'] as $hours)
                        <span>
                            <span class="font-medium text-white/75">{{ str_replace('–', ' - ', $hours['label']) }}:</span>
                            {{ str_replace('–', ' - ', $hours['hours12']) }}
                        </span>
                    @endforeach
                </div>
            </div>
            <a href="{{ route('contact') }}" class="inline-flex min-h-11 shrink-0 items-center gap-1.5 text-sm font-semibold text-secondary underline-offset-4 hover:text-white hover:underline">
                View full hours
                <x-waggies.icon name="arrow-forward" size="16" />
            </a>
        </div>
    </section>
@endif
