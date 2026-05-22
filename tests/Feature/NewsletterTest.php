<?php

use App\Models\Subscriber;

it('subscribes an email to the newsletter', function () {
    $response = $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('newsletter_success', true);

    expect(Subscriber::query()->where('email', 'subscriber@example.com')->exists())->toBeTrue();
});

it('does not duplicate newsletter subscribers', function () {
    Subscriber::factory()->create(['email' => 'subscriber@example.com']);

    $this->post(route('newsletter.subscribe'), [
        'email' => 'subscriber@example.com',
    ]);

    expect(Subscriber::query()->where('email', 'subscriber@example.com')->count())->toBe(1);
});

it('validates newsletter email', function () {
    $response = $this->post(route('newsletter.subscribe'), []);

    $response->assertSessionHasErrors(['email']);
});
