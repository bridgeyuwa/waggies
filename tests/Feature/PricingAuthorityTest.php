<?php

test('public service comparison derives published prices from canonical service rates', function () {
    $this->get(route('services.index'))
        ->assertOk()
        ->assertSeeText('From ₦6,000/night')
        ->assertSeeText('From ₦10,000/session')
        ->assertSeeText('From ₦12,000/visit')
        ->assertSeeText('From ₦80,000/programme')
        ->assertSeeText('Custom quote')
        ->assertSeeText('Route estimate/trip');
});

test('boarding pages derive tier displays from canonical boarding rates', function () {
    $this->get(route('services.boarding.species', ['species' => 'exotic']))
        ->assertOk()
        ->assertSeeText('₦6,000 - ₦8,000')
        ->assertSeeText('₦12,000 - ₦15,000')
        ->assertSeeText('Quote');
});

test('service detail packages derive numeric prices and preserve quote-only packages', function () {
    $this->get(route('services.vet-care'))
        ->assertOk()
        ->assertSeeText('₦12,000')
        ->assertSeeText('₦15,000')
        ->assertSeeText('₦18,000')
        ->assertDontSeeText('₦15,000 - ₦35,000')
        ->assertSeeText('Custom quote');
});

test('contact request schema serializes canonical pricing data', function () {
    $training = config('waggies_pricing.services.training.tiers.puppy');

    $this->get(route('contact', ['intent' => 'service', 'service' => 'training']))
        ->assertOk()
        ->assertSee('pricingData')
        ->assertSee((string) $training['amount'])
        ->assertSee((string) $training['max_amount']);
});
