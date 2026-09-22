<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('exposes the FAQ and Gallery resources in the authenticated admin panel', function (): void {
    config()->set('app.env', 'local');
    $this->actingAs(User::factory()->create());

    $this->get('/admin/faqs')->assertOk();
    $this->get('/admin/gallery-items')->assertOk();
});

it('does not expose pricing administration in the authenticated admin panel', function (): void {
    config()->set('app.env', 'local');
    $this->actingAs(User::factory()->create());

    $this->get('/admin/service-prices')->assertNotFound();
});
