<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class LoyaltyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_loyalty_page_renders_reference_sections_and_metadata(): void
    {
        $response = $this->get(route('loyalty'));

        $response->assertOk()
            ->assertViewIs('pages.loyalty')
            ->assertSee('<title>Loyalty Programme - Waggies</title>', false)
            ->assertSee('Rewards for Every', false)
            ->assertSee('Three Simple Steps', false)
            ->assertSee('Membership Tiers', false)
            ->assertSee('Silver')
            ->assertSee('Gold')
            ->assertSee('Platinum')
            ->assertSee('Start earning today')
            ->assertSee('"@type":"Organization"', false)
            ->assertSee('"@type":"WebPage"', false)
            ->assertSee('application/ld+json', false);
    }

    public function test_loyalty_page_is_available_from_global_search(): void
    {
        $this->get(route('search').'?q=loyalty')
            ->assertOk()
            ->assertJsonFragment([
                'title' => 'Loyalty Programme',
                'href' => route('loyalty'),
            ]);
    }
}
