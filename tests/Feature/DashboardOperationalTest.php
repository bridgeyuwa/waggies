<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the authenticated dashboard as an operational work queue', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/admin')
        ->assertSee('New booking requests')
        ->assertSee('Work queue')
        ->assertSee('Review booking requests')
        ->assertSee('Quick actions');
});
