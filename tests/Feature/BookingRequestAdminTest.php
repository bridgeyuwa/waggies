<?php

namespace Tests\Feature;

use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Resources\BookingRequests\Pages\ListBookingRequests;
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

it('shows all canonical pets and services in the request review workspace', function (): void {
    $admin = User::factory()->admin()->create();
    $request = BookingRequest::factory()->create();
    $pet = BookingRequestPet::factory()->for($request)->create(['name' => 'Milo']);
    $secondPet = BookingRequestPet::factory()->for($request)->create(['name' => 'Luna']);
    $service = BookingRequestService::factory()->for($request)->create([
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => ['care_notes' => 'Keep the routine calm.'],
        'price_snapshot' => ['source' => 'catalogue', 'amount' => 20000],
    ]);
    $secondService = BookingRequestService::factory()->for($request)->create([
        'service_key' => 'vet-care',
        'service_variant' => 'microchip',
        'details' => ['care_needs' => ['microchip']],
        'price_snapshot' => ['source' => 'catalogue', 'amount' => 5000],
    ]);
    $service->pets()->attach($pet);
    $secondService->pets()->attach($secondPet);

    $this->actingAs($admin);

    Livewire::test(EditBookingRequest::class, ['record' => $request->getKey()])
        ->assertOk()
        ->assertSee($request->name)
        ->assertSeeLivewire(ServicesRelationManager::class);

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
        ->assertTableColumnVisible('assigned_pets')
        ->assertTableColumnVisible('details')
        ->assertTableColumnVisible('price_snapshot');
});

it('keeps customer-submitted facts read-only while allowing internal notes', function (): void {
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

    expect($request->name)->toBe('Ada Obi')
        ->and($request->email)->toBe('ada@example.com')
        ->and($request->internal_notes)->toBe('Review the requested dates before quoting.');
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
        ->callAction(TestAction::make('edit')->table($service), [
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
