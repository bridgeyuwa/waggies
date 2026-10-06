<?php

namespace Tests\Feature;

use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Resources\BookingRequests\Pages\ListBookingRequests;
use App\Filament\Resources\BookingRequests\Pages\ViewBookingRequest;
use App\Filament\Resources\BookingRequests\RelationManagers\PetsRelationManager;
use App\Filament\Resources\BookingRequests\RelationManagers\ServicesRelationManager;
use App\Models\BookingRequest;
use App\Models\BookingRequestPet;
use App\Models\BookingRequestService;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

uses(RefreshDatabase::class);

it('shows the canonical booking request work queue to admins', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();

    $this->actingAs($admin);

    Livewire::test(ListBookingRequests::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$request])
        ->assertTableColumnVisible('name')
        ->assertTableColumnVisible('status')
        ->assertTableColumnVisible('services_count')
        ->assertTableColumnVisible('quote_amount');
});

it('offers a read-only booking request view alongside the review action', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create(['name' => 'Ada Obi']);

    $this->actingAs($admin);

    Livewire::test(ListBookingRequests::class)
        ->assertActionExists(TestAction::make('view')->table($request));

    Livewire::test(ViewBookingRequest::class, ['record' => $request->getKey()])
        ->assertOk()
        ->assertSee('Ada Obi')
        ->assertSee('Request summary')
        ->assertDontSee('Request context')
        ->assertSee('Edit')
        ->assertActionExists('delete');
});

it('allows admins to delete a booking request and its related records', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $pet = BookingRequestPet::factory()->for($request)->create();
    $service = BookingRequestService::factory()->for($request)->create();
    $service->pets()->attach($pet);

    $this->actingAs($admin);

    Livewire::test(ListBookingRequests::class)
        ->assertActionExists(TestAction::make('delete')->table($request))
        ->callAction(TestAction::make('delete')->table($request));

    expect(BookingRequest::find($request->getKey()))->toBeNull()
        ->and(BookingRequestPet::find($pet->getKey()))->toBeNull()
        ->and(BookingRequestService::find($service->getKey()))->toBeNull();

    $this->assertDatabaseMissing('booking_request_service_pet', [
        'booking_request_service_id' => $service->getKey(),
        'booking_request_pet_id' => $pet->getKey(),
    ]);
});

it('shows all canonical pets and services in the request review workspace', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $pet = BookingRequestPet::factory()->for($request)->create([
        'name' => 'Milo',
        'species' => 'dog',
    ]);
    $secondPet = BookingRequestPet::factory()->for($request)->create([
        'name' => 'Luna',
        'species' => 'cat',
    ]);
    $service = BookingRequestService::factory()->for($request)->create([
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => ['care_notes' => 'Keep the routine calm.'],
        'price_snapshot' => ['source' => 'catalogue', 'amount' => 20000],
    ]);
    $secondService = BookingRequestService::factory()->for($request)->create([
        'service_key' => 'vet-care',
        'service_variant' => 'wellness-consultation',
        'details' => ['care_needs' => ['wellness-consultation']],
        'price_snapshot' => ['source' => 'catalogue', 'amount' => 5000],
    ]);
    $service->pets()->attach($pet);
    $secondService->pets()->attach($secondPet);

    $this->actingAs($admin);

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->assertOk()
        ->assertSee($request->name)
        ->assertSeeLivewire(ServicesRelationManager::class)
        ->assertActionExists('delete');

    Livewire::test(PetsRelationManager::class, [
        'ownerRecord' => $request,
        'pageClass' => EditBookingRequest::class,
    ])->assertCanSeeTableRecords([$pet, $secondPet]);

    Livewire::test(ServicesRelationManager::class, [
        'ownerRecord' => $request,
        'pageClass' => EditBookingRequest::class,
    ])
        ->assertCanSeeTableRecords([$service, $secondService])
        ->assertSee('Milo')
        ->assertSee('Luna')
        ->assertSee('Dogs')
        ->assertSee('Wellness consultation')
        ->assertSee('Care Notes: Keep the routine calm.')
        ->assertSee('Veterinary care needs: Wellness consultation')
        ->assertSee('NGN 20,000')
        ->assertSee('NGN 5,000')
        ->assertTableColumnVisible('assigned_pets')
        ->assertTableColumnVisible('details')
        ->assertTableColumnVisible('price_snapshot');
});

it('allows staff to correct customer details while recording the correction', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create([
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'internal_notes' => null,
    ]);

    $this->actingAs($admin);

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->fillForm([
            'name' => 'Changed by staff',
            'email' => 'changed@example.com',
            'internal_notes' => 'Review the requested dates before quoting.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $request->refresh();

    $activity = Activity::query()
        ->where('subject_type', BookingRequest::class)
        ->where('subject_id', $request->getKey())
        ->latest('id')
        ->firstOrFail();

    expect($request->name)->toBe('Changed by staff')
        ->and($request->email)->toBe('changed@example.com')
        ->and($request->internal_notes)->toBe('Review the requested dates before quoting.')
        ->and($activity->causer_id)->toBe($admin->getKey())
        ->and($activity->properties->toArray()['attributes']['name'])->toBe('Changed by staff')
        ->and($activity->properties->toArray()['old']['name'])->toBe('Ada Obi');
});

it('allows staff to update a service quote and synchronizes the parent summary', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($request)->create();

    $this->actingAs($admin);

    Livewire::test(ServicesRelationManager::class, [
        'ownerRecord' => $request,
        'pageClass' => EditBookingRequest::class,
    ])
        ->callAction(TestAction::make('editQuote')->table($service), [
            'quote_amount' => 25000,
            'quote_currency' => 'NGN',
            'quote_notes' => 'Confirmed by the care team.',
        ])
        ->assertHasNoFormErrors();

    $service->refresh();
    $request->refresh();

    expect($service->quote_amount)->toBe(25000)
        ->and($service->quote_currency)->toBe('NGN')
        ->and($request->quote_amount)->toBe(25000)
        ->and($request->quote_currency)->toBe('NGN');

    $this->assertDatabaseHas('activity_log', [
        'subject_type' => BookingRequestService::class,
        'subject_id' => $service->getKey(),
        'causer_id' => $admin->getKey(),
    ]);

    $activity = Activity::query()
        ->where('subject_type', BookingRequestService::class)
        ->where('subject_id', $service->getKey())
        ->latest('id')
        ->firstOrFail();

    expect($activity->created_at)->not->toBeNull()
        ->and($activity->attribute_changes->toArray()['attributes']['quote_amount'])
        ->toBe(25000)
        ->and($activity->attribute_changes->toArray()['old']['quote_amount'])
        ->toBeNull();
});

it('allows staff to correct one service inside a multi-service request', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $pet = BookingRequestPet::factory()->for($request)->create(['name' => 'Milo']);
    $updatedDate = now()->addDays(12)->toDateString();
    BookingRequestService::factory()->for($request)->create([
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => [
            'check_in' => now()->addDays(7)->toDateString(),
            'check_out' => now()->addDays(9)->toDateString(),
            'emergency_vet_authorization' => 'authorized',
        ],
    ]);
    $service = BookingRequestService::factory()->for($request)->create([
        'service_key' => 'vet-care',
        'service_variant' => null,
        'details' => [
            'care_needs' => ['microchip'],
            'urgency' => 'routine',
        ],
    ]);
    $service->pets()->attach($pet);

    $this->actingAs($admin);

    Livewire::test(ServicesRelationManager::class, [
        'ownerRecord' => $request,
        'pageClass' => EditBookingRequest::class,
    ])
        ->callAction(TestAction::make('editRequestDetails')->table($service), [
            'service_key' => 'vet-care',
            'service_variant' => null,
            'pricing_tier' => null,
            'requested_date' => $updatedDate,
            'requested_time' => '11:00',
            'location' => 'Waggies clinic',
            'details' => [
                'care_needs' => ['microchip'],
                'reason' => 'Please update the appointment note.',
                'urgency' => 'soon',
            ],
            'additional_details' => [],
            'pet_ids' => [$pet->getKey()],
        ])
        ->assertHasNoFormErrors()
        ->assertNotified();

    $service->refresh();

    expect($service->details['reason'])->toBe('Please update the appointment note.')
        ->and($service->details['urgency'])->toBe('soon')
        ->and($service->requested_date?->format('Y-m-d'))->toBe($updatedDate)
        ->and($service->location)->toBe('Waggies clinic')
        ->and($service->pets->modelKeys())->toContain($pet->getKey());
});

it('accepts note-only service quotes but rejects empty quotes', function (): void {
    $request = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($request)->create();

    $service->updateOperationalQuote(null, null, 'Manual quote required after the care review.');

    expect($service->fresh()->hasQuoteDecision())->toBeTrue()
        ->and($service->fresh()->quote_amount)->toBeNull()
        ->and(function () use ($service): void {
            $service->updateOperationalQuote(null, null, null);
        })
        ->toThrow(\DomainException::class);
});

it('moves a mixed service request to awaiting customer', function (): void {
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::Quoted]);
    $confirmedService = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Quoted,
        'quote_amount' => 25000,
        'quote_currency' => 'NGN',
    ]);
    BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Reviewing,
    ]);

    $confirmedService->transitionTo(BookingRequestStatus::Confirmed);

    expect($request->fresh()->status)->toBe(BookingRequestStatus::AwaitingCustomer);
});

it('uses service quotes as the parent quote readiness source', function (): void {
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::Reviewing]);
    $service = BookingRequestService::factory()->for($request)->create();

    $service->updateOperationalQuote(null, null, 'Manual quote required for this service.');

    expect($request->fresh()->canTransitionTo(BookingRequestStatus::Quoted))->toBeTrue();

    $request->transitionTo(BookingRequestStatus::Quoted);

    expect($request->fresh()->status)->toBe(BookingRequestStatus::Quoted)
        ->and($request->fresh()->quote_amount)->toBeNull()
        ->and($request->fresh()->quote_notes)->toContain('Manual quote required');
});

it('logs lifecycle status changes with actor and old and new values', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Reviewing,
        'quote_amount' => 25000,
        'quote_currency' => 'NGN',
    ]);

    $this->actingAs($admin);

    $service->transitionTo(BookingRequestStatus::Quoted);

    $activity = Activity::query()
        ->where('subject_type', BookingRequestService::class)
        ->where('subject_id', $service->getKey())
        ->latest('id')
        ->firstOrFail();
    $changes = $activity->attribute_changes->toArray();

    expect($activity->causer_id)->toBe($admin->getKey())
        ->and($activity->created_at)->not->toBeNull()
        ->and($changes['attributes']['status'])->toBe(BookingRequestStatus::Quoted->value)
        ->and($changes['old']['status'])->toBe(BookingRequestStatus::Reviewing->value);
});

it('does not allow parent confirmation until every service is confirmed', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::Quoted]);
    $service = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Quoted,
        'quote_amount' => 25000,
        'quote_currency' => 'NGN',
    ]);

    $this->actingAs($admin);

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->assertActionDisabled('confirmRequest');

    expect($service->fresh()->status)->toBe(BookingRequestStatus::Quoted);
});

it('cascades a parent cancellation to unresolved services', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::Reviewing]);
    $service = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Reviewing,
    ]);
    $confirmedService = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Confirmed,
        'quote_amount' => 25000,
        'quote_currency' => 'NGN',
    ]);

    $this->actingAs($admin);

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->callAction('cancelRequest')
        ->assertNotified();

    expect($request->fresh()->status)->toBe(BookingRequestStatus::Cancelled)
        ->and($service->fresh()->status)->toBe(BookingRequestStatus::Cancelled)
        ->and($confirmedService->fresh()->status)->toBe(BookingRequestStatus::Confirmed);

    $activity = Activity::query()
        ->where('subject_type', BookingRequest::class)
        ->where('subject_id', $request->getKey())
        ->latest('id')
        ->firstOrFail();

    expect($activity->attribute_changes->toArray()['attributes']['status'])
        ->toBe(BookingRequestStatus::Cancelled->value);
});

it('lets staff confirm a quoted service before confirming the parent request', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::Quoted]);
    $service = BookingRequestService::factory()->for($request)->create([
        'status' => BookingRequestStatus::Quoted,
        'quote_amount' => 25000,
        'quote_currency' => 'NGN',
    ]);

    $this->actingAs($admin);

    Livewire::test(ServicesRelationManager::class, [
        'ownerRecord' => $request,
        'pageClass' => EditBookingRequest::class,
    ])->callAction(TestAction::make('confirmService')->table($service));

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->callAction('confirmRequest')
        ->assertNotified();

    expect($service->fresh()->status)->toBe(BookingRequestStatus::Confirmed)
        ->and($request->fresh()->status)->toBe(BookingRequestStatus::Confirmed);
});

it('rejects invalid lifecycle transitions at the domain boundary', function (): void {
    $request = BookingRequest::factory()->create(['status' => BookingRequestStatus::New]);
    $service = BookingRequestService::factory()->for($request)->create(['status' => BookingRequestStatus::Reviewing]);

    expect(fn (): mixed => $request->transitionTo(BookingRequestStatus::Confirmed))
        ->toThrow(\DomainException::class);

    expect(fn (): mixed => $service->transitionTo(BookingRequestStatus::Quoted))
        ->toThrow(\DomainException::class);
});
