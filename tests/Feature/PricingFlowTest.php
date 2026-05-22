<?php

use App\Models\Enquiry;

it('renders the pricing calculator page', function () {
    $this->get(route('services.pricing'))
        ->assertOk()
        ->assertSee('Get Your Estimate')
        ->assertSee('pricing-calculator', false);
});

it('preselects service context from query string', function () {
    $this->get(route('services.pricing', ['service' => 'boarding-dogs', 'tier' => 'premium']))
        ->assertOk()
        ->assertSee('Dog Boarding', false);
});

it('prefills contact form from pricing context', function () {
    $this->get(route('contact', [
        'service' => 'grooming',
        'tier' => 'full',
        'intent' => 'book',
        'summary' => 'Full Groom: ₦18,000/session',
    ]))
        ->assertOk()
        ->assertSee('Your estimate is attached')
        ->assertSee('Full Groom: ₦18,000/session', false);
});

it('stores pricing context on contact enquiries', function () {
    config(['mail.admin_address' => null]);

    $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Please book grooming.',
        'service' => 'grooming',
        'tier' => 'full',
        'intent' => 'book',
        'estimate_summary' => 'Full Groom: ₦18,000/session',
    ])->assertRedirect();

    $enquiry = Enquiry::query()->first();

    expect($enquiry)->not->toBeNull()
        ->and($enquiry->service)->toBe('grooming')
        ->and($enquiry->tier)->toBe('full')
        ->and($enquiry->intent)->toBe('book')
        ->and($enquiry->estimate_summary)->toBe('Full Groom: ₦18,000/session');
});

it('normalizes service aliases when storing enquiries', function () {
    config(['mail.admin_address' => null]);

    $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Boarding enquiry.',
        'service' => 'boarding-dogs',
        'tier' => 'premium',
        'intent' => 'save',
    ])->assertRedirect();

    expect(Enquiry::query()->first())
        ->service->toBe('boarding')
        ->variant->toBe('dogs');
});
