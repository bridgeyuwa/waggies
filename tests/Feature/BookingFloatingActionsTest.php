<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps global floating actions off the booking request page while retaining inline WhatsApp help', function (): void {
    $this->get(route('book'))
        ->assertOk()
        ->assertDontSee('aria-label="Chat on WhatsApp"', false)
        ->assertDontSee('aria-label="Back to top"', false)
        ->assertSee('Open WhatsApp');
});

it('keeps global floating actions available on the homepage', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('aria-label="Chat on WhatsApp"', false)
        ->assertSee('aria-label="Back to top"', false);
});
