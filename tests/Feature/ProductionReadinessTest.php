<?php

use App\Models\BusinessProfile;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Support\Facades\Route;

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
        ->assertSee('404')
        ->assertSee('Page not found')
        ->assertSee('Return to homepage')
        ->assertSee('Search Waggies')
        ->assertSee('Browse services')
        ->assertDontSee('Chat on WhatsApp')
        ->assertDontSee('Open Waggies AI Assistant');
});

it('renders branded fallback pages with their real HTML error status', function (): void {
    $pages = [
        401 => ['title' => 'Unauthorized', 'description' => 'You need to sign in before viewing this page.', 'secondary' => 'Contact Waggies'],
        403 => ['title' => 'Access denied', 'description' => 'You do not have permission to view this page.', 'secondary' => 'Contact Waggies'],
        419 => ['title' => 'Page expired', 'description' => 'This form session has expired.', 'secondary' => 'Contact Waggies'],
        429 => ['title' => 'Too many requests', 'description' => 'Please wait a moment before trying again.', 'secondary' => 'Contact Waggies'],
        500 => ['title' => 'Something went wrong', 'description' => 'We hit a problem while loading this page.', 'secondary' => 'Return to homepage'],
        503 => ['title' => 'Temporarily unavailable', 'description' => 'Waggies is taking a short break.', 'secondary' => 'Return to homepage'],
    ];

    foreach ($pages as $status => $page) {
        Route::get('/__test/errors/'.$status, static function () use ($status): never {
            abort($status);
        });

        $this->get('/__test/errors/'.$status)
            ->assertStatus($status)
            ->assertSee((string) $status)
            ->assertSee($page['title'])
            ->assertSee($page['description'])
            ->assertSee('<title>'.$page['title'].' - Waggies</title>', false)
            ->assertSee('Return to homepage')
            ->assertSee($page['secondary'])
            ->assertDontSee('Chat on WhatsApp')
            ->assertDontSee('Open Waggies AI Assistant');
    }
});

it('renders a truthful homepage testimonial state when no stories are publishable', function (): void {
    $this->get('/')
        ->assertOk()
        ->assertSee('Verified client stories will appear here as Waggies pet parents share their experience.');
});

it('renders up to six ordered published testimonials on the homepage', function (): void {
    $testimonials = collect(range(1, 7))->map(function (int $sortOrder): Testimonial {
        return Testimonial::factory()->create([
            'author_name' => "Homepage Owner {$sortOrder}",
            'story' => "Homepage testimonial {$sortOrder}.",
            'status' => Testimonial::STATUS_APPROVED,
            'sort_order' => $sortOrder,
        ]);
    });

    $response = $this->get('/')->assertOk();

    $testimonials->take(6)->each(function (Testimonial $testimonial) use ($response): void {
        $response->assertSee($testimonial->author_name);
    });

    $response
        ->assertDontSee($testimonials->last()->author_name)
        ->assertSee('aria-label="Next testimonial stories"', false)
        ->assertSee('Read all testimonials');
});

it('renders stored testimonial ratings on both public testimonial surfaces', function (): void {
    $testimonial = Testimonial::factory()->create([
        'author_name' => 'Three Star Owner',
        'rating' => 3,
        'story' => 'This published testimonial should render its stored three-star rating.',
        'status' => Testimonial::STATUS_APPROVED,
    ]);

    $this->get(route('about.testimonials'))
        ->assertSee($testimonial->story)
        ->assertSee('aria-label="3 out of 5 stars"', false);

    $this->get(route('home'))
        ->assertSee('\\u0022stars\\u0022:3', false);
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
