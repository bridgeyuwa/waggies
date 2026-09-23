<?php

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('renders accessible testimonial controls with required field contracts', function (): void {
    $this->get(route('about.testimonials'))
        ->assertOk()
        ->assertSee('for="testimonial-service"', false)
        ->assertSee('id="testimonial-service"', false)
        ->assertSee('name="service"', false)
        ->assertSee('id="testimonial-rating-label"', false)
        ->assertSee('aria-labelledby="testimonial-rating-label"', false)
        ->assertSee('name="author_name"', false)
        ->assertSee('name="author_location"', false)
        ->assertSee('name="pet_type"', false)
        ->assertSee('name="website"', false)
        ->assertSee('name="consent"', false);
});

it('rejects testimonial submissions that trip the honeypot', function (): void {
    $payload = [
        'website' => 'https://spam.example.test',
        'rating' => 5,
        'service' => 'Boarding',
        'title' => 'A lovely stay',
        'story' => 'This submission should be rejected before it can become a testimonial record.',
        'author_name' => 'Bot Owner',
        'author_location' => 'Abuja',
        'contact_method' => 'phone',
        'contact_value' => '0808 081 1902',
        'pet_type' => 'Dog',
        'consent' => true,
    ];

    $this->postJson(route('testimonials.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['website']);

    $this->assertDatabaseMissing('testimonials', ['title' => 'A lovely stay']);
});

it('rejects testimonial photos outside the supported image types', function (): void {
    $payload = [
        'rating' => 5,
        'service' => 'Boarding',
        'title' => 'A lovely stay',
        'story' => 'The Waggies team took wonderful care of our dog and kept us updated every day.',
        'author_name' => 'Adaeze O.',
        'author_location' => 'Maitama, Abuja',
        'contact_method' => 'phone',
        'contact_value' => '0808 081 1902',
        'pet_type' => 'Dog',
        'consent' => true,
        'photo' => UploadedFile::fake()->create('proof.gif', 100, 'image/gif'),
    ];

    $this->postJson(route('testimonials.store'), $payload)
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['photo']);

    $this->assertDatabaseMissing('testimonials', ['title' => 'A lovely stay']);
});

it('stores an accepted testimonial photo with the pending submission', function (): void {
    Storage::fake('public');

    $payload = [
        'rating' => 5,
        'service' => 'Boarding',
        'title' => 'A lovely stay',
        'story' => 'The Waggies team took wonderful care of our dog and kept us updated every day.',
        'author_name' => 'Adaeze O.',
        'author_location' => 'Maitama, Abuja',
        'contact_method' => 'phone',
        'contact_value' => '0808 081 1902',
        'pet_type' => 'Dog',
        'consent' => true,
        'photo' => UploadedFile::fake()->image('bruno.webp'),
    ];

    $this->postJson(route('testimonials.store'), $payload)->assertCreated();

    $testimonial = Testimonial::query()->where('title', 'A lovely stay')->firstOrFail();

    expect($testimonial->status)->toBe(Testimonial::STATUS_PENDING)
        ->and($testimonial->getFirstMedia('photo'))->not->toBeNull();

    $media = $testimonial->getFirstMedia('photo');

    Storage::disk('public')->assertExists($media->getPathRelativeToRoot());
});
