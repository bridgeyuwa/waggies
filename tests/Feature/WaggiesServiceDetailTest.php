<?php

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
