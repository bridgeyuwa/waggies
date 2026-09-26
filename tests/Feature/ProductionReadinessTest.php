<?php

use App\Models\BusinessProfile;
use App\Models\User;

it('returns a healthy native endpoint with non-sensitive security headers', function (): void {
    $this->get('/up')
        ->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
        ->assertHeader('Permissions-Policy', 'camera=(), geolocation=(), microphone=(), payment=()');
});

it('renders the Waggies not found page with a real 404 response', function (): void {
    $this->get('/this-page-does-not-exist')
        ->assertNotFound()
        ->assertSee('Page Not Found')
        ->assertSee('Search for services, articles, products');
});

it('renders a truthful homepage testimonial state when no stories are publishable', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Verified client stories will appear here as Waggies pet parents share their experience.');
});

it('redirects guests away from the protected admin panel', function (): void {
    $this->get('/admin')->assertRedirect('/admin/login');
});

it('does not seed the development user in production', function (): void {
    config()->set('app.env', 'production');

    $this->seed();

    $this->assertDatabaseCount('users', 0);
});

it('keeps the backup configuration destination-driven and notification-safe', function (): void {
    $notifications = config('backup.notifications.notifications');

    expect(config('backup.backup.destination.disks'))->toBe(['backups'])
        ->and(config('backup.backup.verify_backup'))->toBeTrue()
        ->and($notifications)->not->toBeEmpty()
        ->and(array_filter($notifications))->toBe([])
        ->and(config('backup.notifications.mail.to'))->toBe(config('mail.from.address'))
        ->and(config('backup.backup.source.databases'))->toBe([config('database.default')]);
});

it('allows authenticated staff to reach the admin panel without exposing credentials in source', function (): void {
    config()->set('app.env', 'local');

    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertOk();
});

it('uses the database WhatsApp destination in public contact journeys', function (): void {
    BusinessProfile::current()->update([
        'whatsapp_url' => 'https://wa.example.test/waggies',
    ]);

    $this->get('/book')
        ->assertOk()
        ->assertSee('https://wa.example.test/waggies');

    $this->get('/contact')
        ->assertOk()
        ->assertSee('https://wa.example.test/waggies');
});
