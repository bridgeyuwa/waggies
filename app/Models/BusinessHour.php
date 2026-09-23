<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

final class BusinessHour extends Model
{
    public const string KIND_WEEKLY = 'weekly';

    public const string KIND_EXCEPTION = 'exception';

    protected $fillable = [
        'kind',
        'day_of_week',
        'date',
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
            'is_closed' => 'boolean',
            'day_of_week' => 'integer',
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

    public static function forDate(CarbonInterface $date): ?self
    {
        return self::query()
            ->where(function (Builder $query) use ($date): void {
                $query
                    ->where(function (Builder $query) use ($date): void {
                        $query->where('kind', self::KIND_EXCEPTION)->whereDate('date', $date->toDateString());
                    })
                    ->orWhere(function (Builder $query) use ($date): void {
                        $query->where('kind', self::KIND_WEEKLY)->where('day_of_week', $date->dayOfWeek);
                    });
            })
            ->orderByRaw("CASE WHEN kind = 'exception' THEN 0 ELSE 1 END")
            ->first();
    }

    /**
     * @return array<int, array{day: string, hours: string}>
     */
    public static function publicSchedule(): array
    {
        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        if (! Schema::hasTable('business_hours')) {
            return [
                ['day' => 'Monday - Friday', 'hours' => '9:00 AM - 5:00 PM'],
                ['day' => 'Saturday', 'hours' => '10:00 AM - 2:00 PM'],
                ['day' => 'Sunday', 'hours' => '10:00 AM - 2:00 PM'],
            ];
        }

        $weekStart = now()->startOfWeek();
        $weekEnd = $weekStart->copy()->addDays(6);
        $weekly = self::query()->weekly()->get()->keyBy('day_of_week');
        $exceptions = self::query()
            ->exceptions()
            ->whereBetween('date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->filter(fn (self $record): bool => $record->date !== null)
            ->keyBy(fn (self $record): string => CarbonImmutable::parse($record->date)->toDateString());

        return collect(range(0, 6))->map(function (int $offset) use ($dayNames, $weekStart, $weekly, $exceptions): array {
            $date = $weekStart->copy()->addDays($offset);
            $dayOfWeek = $date->dayOfWeek;
            $record = $exceptions->get($date->toDateString()) ?? $weekly->get($dayOfWeek);

            return ['day' => $dayNames[$dayOfWeek], 'hours' => $record?->displayLabel() ?? 'Closed'];
        })->all();
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

    private function formatTime(string $time): string
    {
        return date('g:i A', strtotime($time));
    }
}
