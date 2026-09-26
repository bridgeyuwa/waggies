<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Spatie\OpeningHours\Exceptions\MaximumLimitExceeded;
use Spatie\OpeningHours\OpeningHours;
use Spatie\OpeningHours\OpeningHoursForDay;
use Spatie\OpeningHours\TimeRange;

final class BusinessHour extends Model
{
    public const string KIND_WEEKLY = 'weekly';

    public const string KIND_EXCEPTION = 'exception';

    public const string RECURRENCE_ONCE = 'once';

    public const string RECURRENCE_YEARLY = 'yearly';

    protected $fillable = [
        'kind',
        'day_of_week',
        'date',
        'end_date',
        'recurrence',
        'label',
        'is_closed',
        'open_time',
        'close_time',
        'second_open_time',
        'second_close_time',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'end_date' => 'date',
            'is_closed' => 'boolean',
            'day_of_week' => 'integer',
            'recurrence' => 'string',
        ];
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeWeekly(Builder $query): Builder
    {
        return $query->where('kind', self::KIND_WEEKLY);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeExceptions(Builder $query): Builder
    {
        return $query->where('kind', self::KIND_EXCEPTION);
    }

    public static function openingHours(string $timezone): OpeningHours
    {
        $weekly = self::query()->weekly()->get()->keyBy('day_of_week');
        $exceptions = self::query()
            ->exceptions()
            ->whereNotNull('date')
            ->orderBy('date')
            ->orderBy('end_date')
            ->orderBy('id')
            ->get();

        return self::openingHoursFor($weekly, $exceptions, $timezone);
    }

    /**
     * @return array{
     *     timezone: string,
     *     status: array<string, mixed>,
     *     weekly: array{daily: array<int, array<string, mixed>>, grouped: array<int, array<string, mixed>>},
     *     exceptions: array<int, array<string, mixed>>
     * }
     */
    public static function contactSchedule(string $timezone, ?DateTimeInterface $now = null): array
    {
        $clock = ($now ? CarbonImmutable::instance($now) : CarbonImmutable::now($timezone))->setTimezone($timezone);
        $weekly = self::query()->weekly()->get()->keyBy('day_of_week');
        $exceptions = self::query()
            ->exceptions()
            ->whereNotNull('date')
            ->orderBy('date')
            ->orderBy('end_date')
            ->orderBy('id')
            ->get();
        $openingHours = self::openingHoursFor($weekly, $exceptions, $timezone);
        $daily = collect(range(0, 6))
            ->map(function (int $offset) use ($clock, $openingHours): array {
                $date = $clock->startOfDay()->addDays($offset);
                $dateHours = $openingHours->forDate($date);
                $intervals = self::presentationIntervals($dateHours);

                return [
                    'dayOfWeek' => $date->dayOfWeek,
                    'day' => $date->format('l'),
                    'dayShort' => $date->format('D'),
                    'date' => $date->toDateString(),
                    'dateLabel' => $date->format('D j M'),
                    'isToday' => $date->isSameDay($clock),
                    'isClosed' => $dateHours->isEmpty(),
                    'intervals' => $intervals,
                    'hours24' => self::hoursLabel($intervals, 'label24'),
                    'hours12' => self::hoursLabel($intervals, 'label12'),
                ];
            })
            ->values()
            ->all();

        return [
            'timezone' => $timezone,
            'status' => self::statusFor($clock, $openingHours, $weekly->isNotEmpty()),
            'weekly' => [
                'daily' => $daily,
                'grouped' => self::groupRows($openingHours, $clock),
            ],
            'exceptions' => $exceptions->map(fn (self $record): array => self::exceptionRow($record, $openingHours))->all(),
        ];
    }

    /**
     * @return array<int, array{day: string, hours: string}>
     */
    public static function publicSchedule(): array
    {
        if (! Schema::hasTable('business_hours') || ! Schema::hasColumn('business_hours', 'end_date')) {
            return [
                ['day' => 'Monday - Friday', 'hours' => '9:00 AM - 5:00 PM'],
                ['day' => 'Saturday', 'hours' => '10:00 AM - 2:00 PM'],
                ['day' => 'Sunday', 'hours' => '10:00 AM - 2:00 PM'],
            ];
        }

        $timezone = (string) (BusinessProfile::current()->timezone ?: config('app.timezone'));
        $openingHours = self::openingHours($timezone);

        return collect($openingHours->forWeek())
            ->map(function (OpeningHoursForDay $dayHours, string $dayName): array {
                $intervals = self::presentationIntervals($dayHours);

                return [
                    'day' => ucfirst($dayName),
                    'hours' => $dayHours->isEmpty()
                        ? 'Closed'
                    : str_replace('–', ' - ', self::hoursLabel($intervals, 'label12')),
                ];
            })
            ->values()
            ->all();
    }

    public function displayLabel(): string
    {
        if ($this->is_closed || ! $this->open_time || ! $this->close_time) {
            return 'Closed';
        }

        return collect([
            $this->formatTime($this->open_time).' - '.$this->formatTime($this->close_time),
            $this->second_open_time && $this->second_close_time
                ? $this->formatTime($this->second_open_time).' - '.$this->formatTime($this->second_close_time)
                : null,
        ])->filter()->implode(', ');
    }

    public function dateLabel(): string
    {
        return $this->exceptionDateLabel() ?? '—';
    }

    /**
     * @param  Collection<int|string, self>  $weekly
     * @param  Collection<int, self>  $exceptions
     */
    private static function openingHoursFor(Collection $weekly, Collection $exceptions, string $timezone): OpeningHours
    {
        $dayNames = [0 => 'sunday', 1 => 'monday', 2 => 'tuesday', 3 => 'wednesday', 4 => 'thursday', 5 => 'friday', 6 => 'saturday'];
        $data = [];

        foreach ($dayNames as $dayOfWeek => $dayName) {
            $data[$dayName] = self::packageIntervals($weekly->get($dayOfWeek));
        }

        $data['exceptions'] = $exceptions
            ->filter(fn (self $record): bool => $record->date !== null)
            ->mapWithKeys(fn (self $record): array => [$record->packageExceptionKey() => self::packageExceptionDefinition($record)])
            ->all();

        return OpeningHours::create($data, $timezone)->setDayLimit(366);
    }

    /**
     * Validate the complete candidate schedule through Spatie's parser.
     *
     * @param  array<string, mixed>  $attributes
     */
    public static function packageValidationMessage(array $attributes, ?self $record = null): ?string
    {
        $draft = new self;
        $draft->forceFill([
            'kind' => $attributes['kind'] ?? null,
            'day_of_week' => $attributes['day_of_week'] ?? null,
            'date' => $attributes['date'] ?? null,
            'end_date' => $attributes['end_date'] ?? null,
            'recurrence' => $attributes['recurrence'] ?? self::RECURRENCE_ONCE,
            'is_closed' => $attributes['is_closed'] ?? false,
            'open_time' => $attributes['open_time'] ?? null,
            'close_time' => $attributes['close_time'] ?? null,
            'second_open_time' => $attributes['second_open_time'] ?? null,
            'second_close_time' => $attributes['second_close_time'] ?? null,
        ]);

        if ($draft->kind === self::KIND_WEEKLY && $draft->day_of_week === null) {
            return null;
        }

        if ($draft->kind === self::KIND_EXCEPTION && $draft->date === null) {
            return null;
        }

        if (! $draft->is_closed && (! filled($draft->open_time) || ! filled($draft->close_time))) {
            return null;
        }

        $records = self::query()->get()
            ->reject(fn (self $existing): bool => $record?->exists && $existing->is($record))
            ->values();
        $records->push($draft);

        $weekly = $records
            ->where('kind', self::KIND_WEEKLY)
            ->filter(fn (self $item): bool => $item->day_of_week !== null)
            ->keyBy('day_of_week');
        $exceptions = $records
            ->where('kind', self::KIND_EXCEPTION)
            ->filter(fn (self $item): bool => $item->date !== null)
            ->values();

        try {
            $timezone = (string) (BusinessProfile::query()->value('timezone') ?: config('app.timezone'));
            self::openingHoursFor($weekly, $exceptions, $timezone);
        } catch (\Throwable $exception) {
            return "Spatie could not parse this opening-hours schedule: {$exception->getMessage()}";
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    private static function packageIntervals(?self $record): array
    {
        if ($record === null || $record->is_closed) {
            return [];
        }

        return collect([
            [$record->open_time, $record->close_time],
            [$record->second_open_time, $record->second_close_time],
        ])
            ->filter(fn (array $interval): bool => filled($interval[0]) && filled($interval[1]))
            ->map(fn (array $interval): string => substr((string) $interval[0], 0, 5).'-'.substr((string) $interval[1], 0, 5))
            ->all();
    }

    private static function statusFor(CarbonImmutable $now, OpeningHours $openingHours, bool $hasWeeklyRecords): array
    {
        if ($openingHours->isAlwaysClosed() && ! $hasWeeklyRecords) {
            return [
                'isOpen' => false,
                'isConfigured' => false,
                'label' => 'Hours unavailable',
                'detail24' => 'Opening hours have not been configured',
                'detail12' => 'Opening hours have not been configured',
            ];
        }

        if (! $openingHours->isOpenAt($now)) {
            try {
                $nextOpening = CarbonImmutable::instance($openingHours->nextOpen($now));
            } catch (MaximumLimitExceeded) {
                return [
                    'isOpen' => false,
                    'isConfigured' => $hasWeeklyRecords,
                    'label' => 'Closed',
                    'detail24' => 'No upcoming opening hours',
                    'detail12' => 'No upcoming opening hours',
                ];
            }

            $dateLabel = $nextOpening->isSameDay($now)
                ? 'Opens today'
                : ($nextOpening->isTomorrow() ? 'Opens tomorrow' : "Opens {$nextOpening->format('D j M')}");

            return [
                'isOpen' => false,
                'isConfigured' => true,
                'label' => 'Closed',
                'detail24' => "{$dateLabel} at {$nextOpening->format('H:i')}",
                'detail12' => "{$dateLabel} at {$nextOpening->format('g:i A')}",
            ];
        }

        $range = $openingHours->currentOpenRange($now);
        if ($openingHours->isAlwaysOpen()) {
            return [
                'isOpen' => true,
                'isConfigured' => true,
                'label' => 'Open now',
                'detail24' => 'Open 24 hours',
                'detail12' => 'Open 24 hours',
            ];
        }

        $nextClosing = CarbonImmutable::instance($openingHours->nextClose($now));

        return [
            'isOpen' => true,
            'isConfigured' => true,
            'label' => 'Open now',
            'detail24' => "Open since {$range?->start()->format('H:i')} · closes {$nextClosing->format('H:i')}",
            'detail12' => "Open since {$range?->start()->format('g:i A')} · closes {$nextClosing->format('g:i A')}",
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function groupRows(OpeningHours $openingHours, CarbonImmutable $clock): array
    {
        return collect($openingHours->forWeekConsecutiveDays())->map(function (array $group) use ($clock): array {
            $firstDay = (string) $group['days'][0];
            $lastDay = (string) $group['days'][count($group['days']) - 1];
            $isToday = in_array(strtolower($clock->format('l')), $group['days'], true);
            $intervals = self::presentationIntervals($group['opening_hours']);
            $firstShort = self::shortDayName($firstDay);
            $lastShort = self::shortDayName($lastDay);
            $label = $firstShort === $lastShort ? $firstShort : "{$firstShort} – {$lastShort}";

            return [
                'label' => $label,
                'fullLabel' => collect($group['days'])->map(fn (string $day): string => ucfirst($day))->implode(', '),
                'isToday' => $isToday,
                'isClosed' => $group['opening_hours']->isEmpty(),
                'intervals' => $intervals,
                'hours24' => self::hoursLabel($intervals, 'label24'),
                'hours12' => self::hoursLabel($intervals, 'label12'),
            ];
        })->values()->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $intervals
     */
    private static function hoursLabel(array $intervals, string $label): string
    {
        if ($intervals === []) {
            return 'Closed';
        }

        return collect($intervals)->pluck($label)->implode(' · ');
    }

    /**
     * @return array<string, mixed>
     */
    private static function exceptionRow(self $record, OpeningHours $openingHours): array
    {
        $exceptionHours = $openingHours->forDate($record->date);
        $intervals = self::presentationIntervals($exceptionHours);

        return [
            'key' => $record->packageExceptionKey(),
            'date' => $record->date?->toDateString(),
            'dateLabel' => $record->exceptionDateLabel(),
            'label' => $record->label,
            'recurrence' => $record->recurrence ?? self::RECURRENCE_ONCE,
            'isRange' => $record->end_date !== null && ! $record->date?->isSameDay($record->end_date),
            'isClosed' => $exceptionHours->isEmpty(),
            'hours24' => self::hoursLabel($intervals, 'label24'),
            'hours12' => self::hoursLabel($intervals, 'label12'),
        ];
    }

    private static function packageExceptionDefinition(self $record): array
    {
        $hours = self::packageIntervals($record);

        if (blank($record->label)) {
            return $hours;
        }

        return [
            'hours' => $hours,
            'data' => ['id' => $record->getKey(), 'label' => $record->label],
        ];
    }

    private function packageExceptionKey(): string
    {
        $start = $this->date;
        $end = $this->end_date ?: $start;
        $format = ($this->recurrence ?? self::RECURRENCE_ONCE) === self::RECURRENCE_YEARLY ? 'm-d' : 'Y-m-d';
        $startKey = $start->format($format);
        $endKey = $end->format($format);

        return $startKey === $endKey ? $startKey : "{$startKey} to {$endKey}";
    }

    private function exceptionDateLabel(): ?string
    {
        if ($this->date === null) {
            return null;
        }

        $isYearly = ($this->recurrence ?? self::RECURRENCE_ONCE) === self::RECURRENCE_YEARLY;
        $start = $this->date->format($isYearly ? 'j M' : 'j M Y');
        $end = $this->end_date;

        if ($end === null || $this->date->isSameDay($end)) {
            return $isYearly ? "{$start} (every year)" : $start;
        }

        $endLabel = $end->format($isYearly ? 'j M' : 'j M Y');

        return $isYearly
            ? "{$start} – {$endLabel} (every year)"
            : "{$start} – {$endLabel}";
    }

    private static function shortDayName(string $day): string
    {
        return [
            'monday' => 'Mon',
            'tuesday' => 'Tue',
            'wednesday' => 'Wed',
            'thursday' => 'Thu',
            'friday' => 'Fri',
            'saturday' => 'Sat',
            'sunday' => 'Sun',
        ][$day] ?? ucfirst($day);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function presentationIntervals(OpeningHoursForDay $dayHours): array
    {
        return collect($dayHours)->map(function (TimeRange $range): array {
            $start = $range->start();
            $end = $range->end();
            $startMinutes = ($start->hours() * 60) + $start->minutes();
            $endMinutes = ($end->hours() * 60) + $end->minutes();
            $start24 = $start->format('H:i');
            $end24 = $end->format('H:i');
            $start12 = $start->format('g:i A');
            $end12 = $end->format('g:i A');

            return [
                'start' => $start24,
                'end' => $end24,
                'startMinutes' => $startMinutes,
                'endMinutes' => $endMinutes,
                'left' => round(($startMinutes / 1440) * 100, 4),
                'width' => round((($endMinutes - $startMinutes) / 1440) * 100, 4),
                'label24' => "{$start24}–{$end24}",
                'label12' => "{$start12}–{$end12}",
                'start24' => $start24,
                'end24' => $end24,
                'start12' => $start12,
                'end12' => $end12,
            ];
        })->all();
    }

    private function formatTime(string $time): string
    {
        return date('g:i A', strtotime($time));
    }
}
