<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('primary public booking CTAs use the booking request flow and preserve service context', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('book'))
        ->assertSeeText('Submit Booking Request');

    $this->get(route('services.vet-care'))
        ->assertOk()
        ->assertSee(route('book', ['service' => 'vet-care']))
        ->assertSeeText('Submit Veterinary Request');

    $this->get(route('services.boarding.species', ['species' => 'dogs']))
        ->assertOk()
        ->assertSee(route('book', ['service' => 'boarding']))
        ->assertSeeText('Submit Booking Request');
});

test('veterinary service detail pages retain request-only pricing and faq composition', function () {
    $response = $this->get(route('services.vet-care'));

    $response
        ->assertOk()
        ->assertSeeText('What you can request')
        ->assertSeeText('Vaccination request')
        ->assertSeeText('Microchip implantation')
        ->assertSeeText('Veterinary care questions')
        ->assertSeeText('Submit Veterinary Request')
        ->assertDontSeeText('Grooming');
});

test('relocation transport is only described as part of a quote-only relocation request', function () {
    $this->get(route('relocation.import'))
        ->assertOk()
        ->assertSeeText('Airport transfers')
        ->assertSeeText('Submit Booking Request')
        ->assertDontSeeText('Local Transport');

    $this->get('/services/relocation/transport')->assertNotFound();
});
