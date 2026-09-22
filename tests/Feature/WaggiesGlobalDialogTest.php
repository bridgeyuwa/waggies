<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('global dialogs render an accessible trigger and modal contract', function (): void {
    $this->get(route('home'))
        ->assertSee('id="global-search-dialog"', false)
        ->assertSee('id="global-cart-dialog"', false)
        ->assertSee('role="dialog" aria-modal="true" aria-labelledby="search-title"', false)
        ->assertSee('role="dialog" aria-modal="true" aria-labelledby="cart-title"', false)
        ->assertSee('aria-controls="global-search-dialog" aria-expanded="false"', false)
        ->assertSee('aria-controls="global-cart-dialog" aria-expanded="false"', false)
        ->assertSee('x-ref="input"', false)
        ->assertSee('x-ref="closeButton"', false)
        ->assertSee('@keydown="handleDialogKeydown($event)"', false);
});

test('public layout keeps Alpine markup without Livewire assets', function (): void {
    $response = $this->get(route('home'));
    $html = strtolower($response->getContent() ?: '');

    expect($html)->not->toContain('data-livewire-style')
        ->and($html)->not->toContain('livewirescripts');

    $response->assertSee('x-data="waggiesSearch"', false);
});

test('public stylesheet cloaks Alpine content before initialization', function (): void {
    $stylesheet = file_get_contents(base_path('resources/css/app.css'));

    expect($stylesheet)->toContain('[x-cloak]')
        ->and($stylesheet)->toContain('display: none !important;');
});

test('catalogue cart copy does not imply checkout or shipping', function (): void {
    $this->get(route('home'))
        ->assertSeeText('Ask about these products')
        ->assertSeeText('Availability and final pricing confirmed by Waggies.')
        ->assertDontSeeText('Checkout via WhatsApp')
        ->assertDontSeeText('Shipping and taxes confirmed by Waggies.');

    $this->get(route('contact', ['intent' => 'cart-order']))
        ->assertOk()
        ->assertSeeText('No products selected')
        ->assertSeeText('Add products to your saved list before asking about availability.')
        ->assertDontSeeText('Checkout');
});

test('public booking copy describes a request rather than a confirmed appointment', function (): void {
    $this->get(route('home'))
        ->assertSeeText('request a visit')
        ->assertSeeText('booking request');

    $this->get(route('terms-of-service'))
        ->assertSeeText('This website does not currently provide online checkout or payment processing.')
        ->assertDontSeeText('confirmed upon receipt of the required deposit');
});
