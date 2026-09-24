<?php

namespace Tests\Feature;

use App\Models\BusinessHour;
use DateTimeImmutable;
use DateTimeZone;
use Tests\TestCase;

class OpeningHoursPresentationTest extends TestCase
{
    public function test_spatie_calculates_current_split_schedule_and_consecutive_groups(): void
    {
        $this->travelTo('2026-09-24 10:30:00');
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '12:00', '13:00', '18:00'],
            2 => ['09:00', '12:00', '13:00', '18:00'],
            3 => ['09:00', '12:00'],
            4 => ['09:00', '12:00', '13:00', '18:00'],
            5 => ['09:00', '12:00', '13:00', '20:00'],
            6 => ['09:00', '12:00', '13:00', '16:00'],
        ]);

        $schedule = BusinessHour::contactSchedule('Africa/Lagos');

        $this->assertTrue($schedule['status']['isOpen']);
        $this->assertSame('Open since 09:00 · closes 12:00', $schedule['status']['detail24']);
        $this->assertSame(['Mon – Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'], array_column($schedule['weekly']['grouped'], 'label'));
        $this->assertSame('09:00–12:00 · 13:00–18:00', $schedule['weekly']['daily'][0]['hours24']);
        $this->assertTrue($schedule['weekly']['daily'][0]['isToday']);
    }

    public function test_spatie_preserves_weekly_grouping_and_applies_exception_to_the_date_schedule(): void
    {
        $this->travelTo('2026-09-24 10:30:00');
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '12:00', '13:00', '18:00'],
            2 => ['09:00', '12:00', '13:00', '18:00'],
            3 => ['09:00', '12:00'],
            4 => ['09:00', '12:00', '13:00', '18:00'],
            5 => ['09:00', '12:00', '13:00', '20:00'],
            6 => ['09:00', '12:00', '13:00', '16:00'],
        ]);
        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-24',
            'label' => 'Staff training day',
            'is_closed' => true,
        ]);

        $schedule = BusinessHour::contactSchedule('Africa/Lagos');
        $response = $this->get(route('contact'));

        $this->assertFalse($schedule['status']['isOpen']);
        $this->assertSame('Mon – Tue', $schedule['weekly']['grouped'][0]['label']);
        $this->assertTrue($schedule['weekly']['daily'][0]['isClosed']);
        $this->assertSame('Closed', $schedule['weekly']['daily'][0]['hours12']);
        $this->assertSame('Staff training day', $schedule['exceptions'][0]['label']);
        $this->assertTrue($schedule['exceptions'][0]['isClosed']);
        $this->assertSame('Closed', $schedule['exceptions'][0]['hours24']);
        $response->assertSee('Staff training day')
            ->assertSee('24 Sep 2026')
            ->assertSee('Grouped days')
            ->assertSee('12h');
    }

    public function test_footer_reuses_calculated_status_grouped_schedule_and_contact_link(): void
    {
        $this->travelTo('2026-09-24 10:30:00');
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ]);
        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-24',
            'label' => 'Staff training day',
            'is_closed' => true,
        ]);

        $schedule = BusinessHour::contactSchedule('Africa/Lagos');
        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Business Hours')
            ->assertSee($schedule['status']['label'])
            ->assertSee($schedule['status']['detail12'])
            ->assertSee(str_replace('–', ' - ', $schedule['weekly']['grouped'][0]['label']))
            ->assertSee(str_replace('–', ' - ', $schedule['weekly']['grouped'][0]['hours12']))
            ->assertSee('View full hours')
            ->assertSee('href="'.route('contact').'"', false);

        $this->assertFalse($schedule['status']['isOpen']);
    }

    public function test_spatie_exception_hours_override_weekly_status_and_render_as_an_exception(): void
    {
        $this->travelTo('2026-09-24 15:00:00');
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ]);
        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-24',
            'label' => 'Early close',
            'is_closed' => false,
            'open_time' => '09:00',
            'close_time' => '12:00',
        ]);

        $schedule = BusinessHour::contactSchedule('Africa/Lagos');

        $this->assertFalse($schedule['status']['isOpen']);
        $this->assertSame('Opens tomorrow at 09:00', $schedule['status']['detail24']);
        $this->assertSame('09:00–12:00', $schedule['weekly']['daily'][0]['hours24']);
        $this->assertSame('09:00–12:00', $schedule['exceptions'][0]['hours24']);
    }

    public function test_every_day_view_is_a_rolling_seven_day_window(): void
    {
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ]);

        foreach ([
            '2026-09-24' => ['2026-09-24', '2026-09-25', '2026-09-26', '2026-09-27', '2026-09-28', '2026-09-29', '2026-09-30'],
            '2026-09-25' => ['2026-09-25', '2026-09-26', '2026-09-27', '2026-09-28', '2026-09-29', '2026-09-30', '2026-10-01'],
            '2026-09-26' => ['2026-09-26', '2026-09-27', '2026-09-28', '2026-09-29', '2026-09-30', '2026-10-01', '2026-10-02'],
            '2026-09-27' => ['2026-09-27', '2026-09-28', '2026-09-29', '2026-09-30', '2026-10-01', '2026-10-02', '2026-10-03'],
            '2026-09-28' => ['2026-09-28', '2026-09-29', '2026-09-30', '2026-10-01', '2026-10-02', '2026-10-03', '2026-10-04'],
        ] as $today => $expectedDates) {
            $this->travelTo("{$today} 10:30:00");
            $schedule = BusinessHour::contactSchedule('Africa/Lagos');

            $this->assertCount(7, $schedule['weekly']['daily']);
            $this->assertSame($expectedDates, array_column($schedule['weekly']['daily'], 'date'));
            $this->assertTrue($schedule['weekly']['daily'][0]['isToday']);
            $this->assertFalse($schedule['weekly']['daily'][1]['isToday']);
        }
    }

    public function test_spatie_applies_annual_and_date_range_exceptions(): void
    {
        $this->travelTo('2026-09-24 10:30:00');
        $this->seedWeeklySchedule([
            0 => ['09:00', '17:00'],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['09:00', '17:00'],
        ]);
        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-12-25',
            'recurrence' => BusinessHour::RECURRENCE_YEARLY,
            'label' => 'Christmas Day',
            'is_closed' => true,
        ]);
        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-25',
            'end_date' => '2026-09-26',
            'label' => 'Staff training',
            'is_closed' => false,
            'open_time' => '11:00',
            'close_time' => '15:00',
        ]);

        $openingHours = BusinessHour::openingHours('Africa/Lagos');
        $schedule = BusinessHour::contactSchedule('Africa/Lagos');

        $this->assertTrue($openingHours->forDate(new DateTimeImmutable('2027-12-25', new DateTimeZone('Africa/Lagos')))->isEmpty());
        $this->assertSame('11:00-15:00', $openingHours->forDate(new DateTimeImmutable('2026-09-26', new DateTimeZone('Africa/Lagos')))->__toString());
        $this->assertSame('11:00–15:00', $schedule['weekly']['daily'][1]['hours24']);
        $this->assertSame('25 Dec (every year)', $schedule['exceptions'][1]['dateLabel']);
        $this->assertSame('25 Sep 2026 – 26 Sep 2026', $schedule['exceptions'][0]['dateLabel']);
    }

    public function test_no_weekly_schedule_is_distinguished_from_an_intentional_closure(): void
    {
        BusinessHour::query()->delete();

        $schedule = BusinessHour::contactSchedule('Africa/Lagos', new DateTimeImmutable('2026-09-24 10:30:00', new DateTimeZone('Africa/Lagos')));

        $this->assertFalse($schedule['status']['isOpen']);
        $this->assertFalse($schedule['status']['isConfigured']);
        $this->assertSame('Hours unavailable', $schedule['status']['label']);
        $this->assertSame('Opening hours have not been configured', $schedule['status']['detail12']);
    }

    public function test_package_generates_opening_hours_structured_data(): void
    {
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ]);

        $structuredData = BusinessHour::openingHours('Africa/Lagos')->asStructuredData();

        $this->assertContains([
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => 'Monday',
            'opens' => '09:00',
            'closes' => '17:00',
        ], $structuredData);
    }

    public function test_spatie_object_handles_current_range_next_close_next_open_and_closed_exception(): void
    {
        $this->seedWeeklySchedule([
            0 => [],
            1 => ['09:00', '17:00'],
            2 => ['09:00', '17:00'],
            3 => ['09:00', '17:00'],
            4 => ['09:00', '17:00'],
            5 => ['09:00', '17:00'],
            6 => ['10:00', '14:00'],
        ]);

        $openingHours = BusinessHour::openingHours('Africa/Lagos');
        $openAt = new DateTimeImmutable('2026-09-24 10:30:00', new DateTimeZone('Africa/Lagos'));
        $closedAt = new DateTimeImmutable('2026-09-24 18:00:00', new DateTimeZone('Africa/Lagos'));

        $this->assertTrue($openingHours->isOpenAt($openAt));
        $this->assertSame('09:00', $openingHours->currentOpenRange($openAt)?->start()->format('H:i'));
        $this->assertSame('17:00', $openingHours->nextClose($openAt)->format('H:i'));
        $this->assertFalse($openingHours->isOpenAt($closedAt));
        $this->assertSame('2026-09-25 09:00', $openingHours->nextOpen($closedAt)->format('Y-m-d H:i'));

        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-24',
            'label' => 'Staff training day',
            'is_closed' => true,
        ]);

        $withException = BusinessHour::openingHours('Africa/Lagos');

        $this->assertFalse($withException->isOpenOn('2026-09-24'));
        $this->assertTrue($withException->forDate(new DateTimeImmutable('2026-09-24', new DateTimeZone('Africa/Lagos')))->isEmpty());
    }

    /**
     * @param  array<int, array<int, string>>  $schedule
     */
    private function seedWeeklySchedule(array $schedule): void
    {
        BusinessHour::query()->delete();

        foreach ($schedule as $dayOfWeek => $times) {
            $isClosed = $times === [];

            BusinessHour::query()->create([
                'kind' => BusinessHour::KIND_WEEKLY,
                'day_of_week' => $dayOfWeek,
                'is_closed' => $isClosed,
                'open_time' => $times[0] ?? null,
                'close_time' => $times[1] ?? null,
                'second_open_time' => $times[2] ?? null,
                'second_close_time' => $times[3] ?? null,
            ]);
        }

    }
}
