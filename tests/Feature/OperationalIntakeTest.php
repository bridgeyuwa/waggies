<?php

use App\Models\ContactEnquiry;
use App\Models\NewsletterSubscriber;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('persists a public contact enquiry and returns its operational identifier', function (): void {
    $payload = [
        'name' => 'Ada Obi',
        'phone' => '0808 081 1902',
        'subject' => 'Boarding request',
        'service' => 'boarding',
        'intent' => 'BOOKING_REQUEST',
        'message' => 'Hello Waggies, I would like to request boarding for my dog next month.',
        'reference' => 'WGX-TEST-01',
        'website' => '',
    ];

    $response = $this->postJson(route('contact-enquiries.store'), $payload);

    $response->assertCreated()->assertJsonPath('message', 'Your request has been saved. Waggies will continue the conversation on WhatsApp.');

    $enquiry = ContactEnquiry::query()->where('reference', 'WGX-TEST-01')->firstOrFail();

    expect($enquiry->getKey())
        ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
        ->and($enquiry->getIncrementing())->toBeFalse()
        ->and($enquiry->status)->toBe(ContactEnquiry::STATUS_NEW);

    $this->assertDatabaseHas('contact_enquiries', [
        'id' => $enquiry->getKey(),
        'phone' => '0808 081 1902',
        'subject' => 'Boarding request',
    ]);
});

it('rejects contact enquiries without a message and does not persist them', function (): void {
    $response = $this->postJson(route('contact-enquiries.store'), [
        'subject' => 'Missing message',
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors(['message']);

    expect(ContactEnquiry::query()->count())->toBe(0);
});

it('rejects contact enquiries that trip the honeypot', function (): void {
    $response = $this->postJson(route('contact-enquiries.store'), [
        'subject' => 'Bot submission',
        'message' => 'This should not be stored.',
        'website' => 'https://spam.example.test',
    ]);

    $response->assertUnprocessable()->assertJsonValidationErrors(['website']);
    expect(ContactEnquiry::query()->count())->toBe(0);
});

it('normalizes newsletter email and prevents duplicate subscriptions', function (): void {
    $this->post(route('newsletter.store'), ['email' => '  OWNER@Example.COM '])->assertRedirect();
    $this->post(route('newsletter.store'), ['email' => 'owner@example.com'])->assertRedirect();

    $subscriber = NewsletterSubscriber::query()->where('email', 'owner@example.com')->firstOrFail();

    expect(NewsletterSubscriber::query()->where('email', 'owner@example.com')->count())->toBe(1)
        ->and($subscriber->getKey())->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
        ->and($subscriber->status)->toBe(NewsletterSubscriber::STATUS_SUBSCRIBED);
});

it('rejects newsletter submissions that trip the honeypot', function (): void {
    $this->from(route('home'))
        ->post(route('newsletter.store'), [
            'email' => 'bot@example.com',
            'website' => 'https://spam.example.test',
        ])
        ->assertSessionHasErrors('website');

    expect(NewsletterSubscriber::query()->where('email', 'bot@example.com')->exists())->toBeFalse();
});

it('publishes only approved testimonials and preserves their display order', function (): void {
    $first = Testimonial::factory()->create([
        'service' => 'grooming',
        'story' => 'First database testimonial that should appear before the second database testimonial.',
        'author_name' => 'First Owner',
        'status' => Testimonial::STATUS_APPROVED,
        'sort_order' => 1,
    ]);
    $second = Testimonial::factory()->create([
        'service' => 'grooming',
        'story' => 'Second database testimonial that should appear after the first database testimonial.',
        'author_name' => 'Second Owner',
        'status' => Testimonial::STATUS_APPROVED,
        'sort_order' => 2,
    ]);
    $draft = Testimonial::factory()->create([
        'story' => 'This draft testimonial must remain private from the public page.',
        'status' => Testimonial::STATUS_PENDING,
        'sort_order' => 0,
    ]);

    $content = $this->get(route('about.testimonials'))->getContent();

    expect(strpos($content, $first->story))->toBeLessThan(strpos($content, $second->story));
    $this->get(route('about.testimonials'))->assertSee($first->story)->assertDontSee($draft->story);
});

it('exposes all Batch 25 resources to authenticated Filament staff', function (): void {
    config()->set('app.env', 'local');
    $this->actingAs(User::factory()->create());

    $this->get('/admin/contact-enquiries')->assertOk();
    $this->get('/admin/newsletter-subscribers')->assertOk();
    $this->get('/admin/testimonials')->assertOk();
});
