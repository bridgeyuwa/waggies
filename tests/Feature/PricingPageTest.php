<?php

namespace Tests\Feature;

use Tests\TestCase;

class PricingPageTest extends TestCase
{
    public function test_pricing_page_exposes_local_transport_calculator_options(): void
    {
        $this->get('/services/pricing?service=transport&tier=airport')
            ->assertOk()
            ->assertSee('Local Transport')
            ->assertSee('City Pet Transfer')
            ->assertSee('Vet Transfer')
            ->assertSee('Airport Transfer')
            ->assertSee('Pickup location')
            ->assertSee('Drop-off location')
            ->assertSee('Airport details');
    }

    public function test_transport_alias_locks_the_pricing_calculator_to_local_transport(): void
    {
        $this->get('/services/pricing?service=transport&tier=airport')
            ->assertOk()
            ->assertSee('Local Transport')
            ->assertSee('Airport details');
    }
}
