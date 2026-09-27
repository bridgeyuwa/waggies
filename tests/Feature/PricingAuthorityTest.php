<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('only the three core services appear on the public services page', function (): void {
    $this->get(route('services.index'))
        ->assertOk()
        ->assertSeeText('Boarding')
        ->assertSeeText('Veterinary Care')
        ->assertSeeText('Relocation')
        ->assertDontSeeText('Grooming')
        ->assertDontSeeText('Dog Training')
        ->assertDontSeeText('Local Transport')
        ->assertDontSeeText('Exotic Pet Boarding');
});

test('boarding pages use direct size guidance and have no package choices', function (): void {
    $this->get(route('services.boarding.species', ['species' => 'dogs']))
        ->assertOk()
        ->assertSeeText('Small — up to 10kg')
        ->assertSeeText('Medium — over 10kg through 25kg')
        ->assertSeeText('Large — over 25kg through 40kg')
        ->assertSeeText('Above 40kg or unusual size')
        ->assertDontSeeText('Basic package')
        ->assertDontSeeText('Premium package');

    $this->get('/services/boarding/exotic')->assertNotFound();
});

test('veterinary page includes request-only vaccination and standalone microchipping', function (): void {
    $this->get(route('services.vet-care'))
        ->assertOk()
        ->assertSeeText('Vaccination request')
        ->assertSeeText('Microchip implantation')
        ->assertSeeText('Request review')
        ->assertDontSeeText('Generic vaccination price');
});

test('booking wizard exposes only active services and no tier field', function (): void {
    Livewire::test('booking-request-wizard')
        ->assertSee('Boarding')
        ->assertSee('Veterinary Care')
        ->assertSee('Relocation')
        ->assertDontSee('Grooming')
        ->assertDontSee('Dog Training')
        ->assertDontSee('Local Transport')
        ->assertDontSee('Choose a package');
});

test('pricing has no database runtime authority and no retired service configuration', function (): void {
    expect(Schema::hasTable('service_prices'))->toBeFalse()
        ->and(config('waggies_pricing.services'))->toHaveKeys(['boarding', 'vet-care', 'relocation'])
        ->and(config('waggies_pricing.services'))->not->toHaveKey('grooming')
        ->and(config('waggies_pricing.services'))->not->toHaveKey('training')
        ->and(config('waggies_pricing.services'))->not->toHaveKey('local-transport');
});
