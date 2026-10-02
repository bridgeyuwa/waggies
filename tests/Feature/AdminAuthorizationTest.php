<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('keeps the admin panel closed to authenticated non-admin users', function (): void {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('allows explicitly designated admins to reach the admin panel', function (): void {
    config()->set('app.env', 'local');

    $this->actingAs(User::factory()->admin()->create())
        ->get('/admin')
        ->assertOk();
});
