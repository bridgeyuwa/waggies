<?php

use App\Models\Enquiry;
use App\Notifications\EnquiryReceivedNotification;
use Illuminate\Support\Facades\Notification;

it('stores a contact enquiry and notifies admin', function () {
    config(['mail.admin_address' => 'admin@waggies.test']);

    Notification::fake();

    $response = $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Hello, I would like to book boarding.',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $enquiry = Enquiry::query()->first();

    expect($enquiry)->not->toBeNull()
        ->and($enquiry->name)->toBe('Jane Doe')
        ->and($enquiry->email)->toBe('jane@example.com');

    Notification::assertSentOnDemand(EnquiryReceivedNotification::class);
});

it('validates contact form fields', function () {
    $response = $this->post(route('contact.store'), []);

    $response->assertSessionHasErrors(['name', 'email', 'message']);
});

it('rejects invalid pricing service keys', function () {
    $response = $this->post(route('contact.store'), [
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'message' => 'Hello.',
        'service' => 'invalid-service',
    ]);

    $response->assertSessionHasErrors(['service']);
});
