<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PricingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_dog_boarding_pricing_is_per_pet_per_night_and_uses_direct_size_selection(): void
    {
        Livewire::test('pricing-calculator', [
            'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
        ])
            ->assertSee('Small')
            ->assertDontSee('Weight');
    }

    public function test_cat_boarding_uses_the_fixed_nightly_rate(): void
    {
        Livewire::test('pricing-calculator', [
            'initialContext' => ['service' => 'boarding', 'variant' => 'cats'],
        ])
            ->call('calculate')
            ->assertSee('₦12,000')
            ->assertDontSee('staff confirmation')
            ->assertDontSee('Quote required');
    }

    public function test_vaccination_and_microchipping_are_request_based_veterinary_options(): void
    {
        $this->get(route('services.vet-care'))
            ->assertOk()
            ->assertSee('Vaccination request')
            ->assertSee('Microchip implantation')
            ->assertSee('Identification')
            ->assertSee('request-only');
    }

    public function test_relocation_pricing_is_quote_only_for_import_and_export(): void
    {
        foreach (['import', 'export'] as $variant) {
            Livewire::test('pricing-calculator', [
                'initialContext' => ['service' => 'relocation', 'variant' => $variant],
            ])
                ->call('calculate')
                ->assertSee('Custom quote')
                ->assertDontSee('Local Transport');
        }
    }
}
