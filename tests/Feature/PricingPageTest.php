<?php

namespace Tests\Feature;

use Tests\TestCase;

class PricingPageTest extends TestCase
{
    public function test_pricing_page_exposes_only_the_active_service_guidance(): void
    {
        $this->get('/services/pricing?service=transport&tier=airport')
            ->assertOk()
            ->assertSee('Boarding')
            ->assertSee('Veterinary Care')
            ->assertSee('Relocation')
            ->assertDontSee('Local Transport')
            ->assertDontSee('Grooming')
            ->assertDontSee('Dog Training')
            ->assertDontSee('Airport details');
    }
}
