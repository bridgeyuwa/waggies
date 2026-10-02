<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('moves through the booking steps in order after each step is valid', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->call('nextStep')
        ->assertSet('step', 4)
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'ada@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->call('nextStep');

    $component->assertSet('step', 5);
});

it('stops on the pet step when an age-dependent service has no life stage', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('toggleService', 'vet-care')
        ->set('services.0.details.care_needs', ['wellness-consultation'])
        ->call('nextStep')
        ->assertSet('step', 2)
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertHasErrors(['pets.0.age' => 'required']);
});

it('continues from the pet step after the user chooses an explicit life stage', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'vet-care')
        ->set('services.0.details.care_needs', ['wellness-consultation'])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('pets.0.age', 'not-sure')
        ->call('nextStep');

    $component->assertSet('step', 3)->assertHasNoErrors();
});
