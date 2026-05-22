<?php

use App\Models\User;
use Filament\Facades\Filament;

it('allows local users to access the admin panel', function () {
    $this->app->detectEnvironment(fn () => 'local');

    $user = User::factory()->create(['email' => 'anyone@example.com']);

    expect($user->canAccessPanel(Filament::getPanel('admin')))->toBeTrue();
});

it('allows production users listed in ADMIN_EMAILS', function () {
    $this->app->detectEnvironment(fn () => 'production');

    putenv('ADMIN_EMAILS=admin@waggies.test,editor@waggies.test');

    $allowed = User::factory()->create(['email' => 'admin@waggies.test']);
    $denied = User::factory()->create(['email' => 'stranger@example.com']);

    expect($allowed->canAccessPanel(Filament::getPanel('admin')))->toBeTrue()
        ->and($denied->canAccessPanel(Filament::getPanel('admin')))->toBeFalse();
});
