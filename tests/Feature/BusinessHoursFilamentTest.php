<?php

namespace Tests\Feature;

use App\Filament\Resources\BusinessHours\Pages\CreateBusinessHour;
use App\Models\BusinessHour;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class BusinessHoursFilamentTest extends TestCase
{
    public function test_closed_exception_can_be_saved_without_opening_times(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateBusinessHour::class)
            ->fillForm([
                'kind' => BusinessHour::KIND_EXCEPTION,
                'date' => '2026-12-25',
                'label' => 'Christmas Day',
                'is_closed' => true,
                'open_time' => null,
                'close_time' => null,
                'second_open_time' => null,
                'second_close_time' => null,
            ])
            ->assertSchemaComponentHidden('open_time')
            ->assertSchemaComponentHidden('close_time')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('business_hours', [
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-12-25',
            'is_closed' => true,
            'open_time' => null,
            'close_time' => null,
        ]);
    }

    public function test_open_exception_still_requires_a_complete_primary_interval(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateBusinessHour::class)
            ->fillForm([
                'kind' => BusinessHour::KIND_EXCEPTION,
                'date' => '2026-12-26',
                'is_closed' => false,
                'open_time' => null,
                'close_time' => null,
            ])
            ->call('create')
            ->assertHasFormErrors([
                'open_time' => 'required',
                'close_time' => 'required',
            ]);
    }

    public function test_filament_saves_annual_exception_and_date_range_fields(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateBusinessHour::class)
            ->fillForm([
                'kind' => BusinessHour::KIND_EXCEPTION,
                'date' => '2026-12-24',
                'end_date' => '2026-12-26',
                'recurrence' => BusinessHour::RECURRENCE_YEARLY,
                'label' => 'Christmas closure',
                'is_closed' => true,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('business_hours', [
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-12-24',
            'end_date' => '2026-12-26',
            'recurrence' => BusinessHour::RECURRENCE_YEARLY,
            'is_closed' => true,
        ]);
    }

    public function test_filament_rejects_overlapping_intervals_through_spatie(): void
    {
        $this->actingAs(User::factory()->create());

        Livewire::test(CreateBusinessHour::class)
            ->fillForm([
                'kind' => BusinessHour::KIND_WEEKLY,
                'day_of_week' => 4,
                'is_closed' => false,
                'open_time' => '09:00',
                'close_time' => '12:00',
                'second_open_time' => '11:00',
                'second_close_time' => '14:00',
            ])
            ->call('create')
            ->assertHasFormErrors(['close_time']);

        $this->assertDatabaseMissing('business_hours', [
            'kind' => BusinessHour::KIND_WEEKLY,
            'day_of_week' => 4,
            'open_time' => '09:00',
            'close_time' => '12:00',
            'second_open_time' => '11:00',
            'second_close_time' => '14:00',
        ]);
    }
}
