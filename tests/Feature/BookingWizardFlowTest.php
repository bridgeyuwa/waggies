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

it('renders friendly labels for every missing relocation detail', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'relocation')
        ->set('services.0.service_variant', 'export')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', [0])
        ->call('nextStep');

    $component
        ->assertSet('step', 3)
        ->assertSee('The travel timing field is required.')
        ->assertSee('The destination country field is required.')
        ->assertSee('The flight booking status field is required.')
        ->assertSee('The existing microchip status field is required.')
        ->assertSee('The documentation status field is required.')
        ->assertDontSee('The services.0.details.microchip status field is required.');
});

it('shows missing names for every added pet when submission is attempted', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->call('addPet')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('pets.1.species', 'dog')
        ->set('pets.1.size', 'medium')
        ->set('pets.1.sex', 'female')
        ->set('services.0.assigned_pet_ids', [0, 1])
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'ada@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors([
            'pets.0.name' => 'required',
            'pets.1.name' => 'required',
        ])
        ->assertSee('The pet name field is required for 2 pets.');
});

it('groups repeated pet field errors in the summary', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->call('nextStep')
        ->call('addPet')
        ->call('nextStep');

    preg_match('/<div id="booking-error-summary".*?<\/div>/s', $component->html(), $matches);

    expect($matches[0] ?? '')
        ->toContain('for 2 pets')
        ->and(substr_count($matches[0] ?? '', 'The pet name field is required for 2 pets.'))->toBe(1)
        ->and(substr_count($matches[0] ?? '', 'The pet type field is required for 2 pets.'))->toBe(1)
        ->and(substr_count($matches[0] ?? '', 'The sex field is required for 2 pets.'))->toBe(1);
});

it('groups repeated service field errors in the summary', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('toggleService', 'relocation')
        ->set('services.1.service_variant', 'export')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->call('nextStep');

    preg_match('/<div id="booking-error-summary".*?<\/div>/s', $component->html(), $matches);

    expect($matches[0] ?? '')
        ->toContain('The assigned pet field is required for 2 services.')
        ->and(substr_count($matches[0] ?? '', 'The assigned pet field is required for 2 services.'))->toBe(1);
});

it('includes pet-name errors when submission also has an earlier service error', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('addPet')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors([
            'services.0.service_key' => 'required',
            'pets.0.name' => 'required',
            'pets.1.name' => 'required',
        ])
        ->assertSee('The service field is required.')
        ->assertSee('The pet name field is required for 2 pets.')
        ->assertSee('(Step 2)')
        ->assertDontSee('href="#booking-pet-0-name"', false);
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
