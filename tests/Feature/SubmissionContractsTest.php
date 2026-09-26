<?php

namespace Tests\Feature;

use App\Models\NewsletterSubscriber;
use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubmissionContractsTest extends TestCase
{
    use RefreshDatabase;

    public function test_newsletter_subscription_is_persisted_and_normalized(): void
    {
        $response = $this->post(route('newsletter.store'), ['email' => '  OWNER@Example.COM ']);

        $response->assertRedirect()
            ->assertSessionHas('newsletter_status', 'Thanks — you are subscribed to Waggies updates.');

        $this->assertDatabaseHas('newsletter_subscriptions', ['email' => 'owner@example.com']);
    }

    public function test_duplicate_newsletter_submissions_do_not_create_duplicate_records(): void
    {
        $payload = ['email' => 'owner@example.com'];

        $this->post(route('newsletter.store'), $payload);
        $this->post(route('newsletter.store'), $payload);

        $this->assertSame(1, NewsletterSubscriber::query()->where('email', $payload['email'])->count());
    }

    public function test_invalid_newsletter_subscription_is_rejected(): void
    {
        $this->from('/')->post(route('newsletter.store'), ['email' => 'not-an-email'])
            ->assertRedirect('/')
            ->assertSessionHasErrors('email');

        $this->assertDatabaseCount('newsletter_subscriptions', 0);
    }

    public function test_valid_testimonial_is_persisted_pending_review(): void
    {
        $response = $this->postJson(route('testimonials.store'), $this->testimonial_payload());

        $response->assertCreated()
            ->assertJsonPath('message', "Thank you! Your testimonial has been submitted. We'll review it and share it with the Waggies community soon.");

        $testimonial = Testimonial::query()->where('story', $this->testimonial_payload()['story'])->firstOrFail();

        $this->assertSame(Testimonial::STATUS_PENDING, $testimonial->status);
        $this->assertNotNull($testimonial->consented_at);
        $this->assertDatabaseHas('testimonials', [
            'story' => $this->testimonial_payload()['story'],
            'status' => Testimonial::STATUS_PENDING,
        ]);
    }

    public function test_invalid_testimonial_is_rejected_without_persistence(): void
    {
        $response = $this->postJson(route('testimonials.store'), [
            ...$this->testimonial_payload(),
            'story' => 'Too short',
            'consent' => false,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['story', 'consent']);

        $this->assertDatabaseMissing('testimonials', ['story' => 'Too short']);
    }

    public function test_pending_testimonials_are_excluded_from_public_publication_scope(): void
    {
        $this->postJson(route('testimonials.store'), $this->testimonial_payload());

        $testimonial = Testimonial::query()->where('story', $this->testimonial_payload()['story'])->firstOrFail();

        $this->assertFalse(Testimonial::published()->whereKey($testimonial)->exists());
        $this->get(route('about.testimonials'))->assertDontSee($testimonial->story);
    }

    public function test_cost_calculator_receives_rates_from_pricing_configuration(): void
    {
        $rates = config('waggies_pricing.cost_calculator.rates');

        $this->get(route('tools.cost'))
            ->assertMovedPermanently()
            ->assertRedirect(route('services.pricing'));
    }

    /**
     * @return array<string, mixed>
     */
    private function testimonial_payload(): array
    {
        return [
            'rating' => 5,
            'service' => 'Boarding',
            'story' => 'The Waggies team took wonderful care of our dog and kept us updated every day.',
            'author_name' => 'Adaeze O.',
            'author_location' => 'Maitama, Abuja',
            'consent' => true,
        ];
    }
}
