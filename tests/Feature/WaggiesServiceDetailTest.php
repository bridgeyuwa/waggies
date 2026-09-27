<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('primary public booking CTAs use the booking request flow and preserve service context', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('book'))
        ->assertSeeText('Request a booking');

    $this->get(route('services.grooming'))
        ->assertOk()
        ->assertSee(route('book', ['service' => 'grooming']))
        ->assertSeeText('Request Grooming');

    $this->get(route('services.boarding.species', ['species' => 'dogs']))
        ->assertOk()
        ->assertSee(route('book', ['service' => 'boarding']))
        ->assertSeeText('Request boarding');
});

test('standard service detail pages retain package pricing and faq composition', function () {
    $response = $this->get(route('services.grooming'));

    $response
        ->assertOk()
        ->assertSeeText("What's Included")
        ->assertSeeText('₦10,000')
        ->assertSeeText('Frequently Asked Questions')
        ->assertSeeText('Ready to Get Started?');
});

test('transport uses the shared service detail composition with quote pricing', function () {
    $response = $this->get(route('relocation.transport'));

    $response
        ->assertOk()
        ->assertSee('Provisional route estimate')
        ->assertSee('Final charges confirmed on WhatsApp')
        ->assertSee('A simple handoff from door to door')
        ->assertSee('Safe &amp; Climate-Controlled Pet Taxi', false);
});
