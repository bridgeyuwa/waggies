<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders accessible testimonial controls with required field contracts', function (): void {
    $this->get(route('about.testimonials'))
        ->assertOk()
        ->assertSee('for="testimonial-service"', false)
        ->assertSee('id="testimonial-service"', false)
        ->assertSee('name="service"', false)
        ->assertSee('id="testimonial-rating-label"', false)
        ->assertSee('aria-labelledby="testimonial-rating-label"', false)
        ->assertSee('name="author_name"', false)
        ->assertSee('name="author_location"', false)
        ->assertSee('name="website"', false)
        ->assertSee('name="consent"', false);
});

it('keeps the home testimonial panel labelled by the testimonial that is currently displayed', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee(":aria-labelledby=\"'testimonial-tab-' + displayed\"", false);
});

it('rejects testimonial submissions that trip the honeypot', function (): void {
    $payload = [
        'website' => 'https://spam.example.test',
        'rating' => 5,
        'service' => 'Boarding',
        'story' => 'This submission should be rejected before it can become a testimonial record.',
        'author_name' => 'Bot Owner',
        'author_location' => 'Abuja',
        'consent' => true,
    ];

    $this->postJson(route('testimonials.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['website']);

    $this->assertDatabaseMissing('testimonials', ['story' => 'This submission should be rejected before it can become a testimonial record.']);
});
