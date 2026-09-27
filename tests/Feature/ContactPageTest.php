<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use Livewire\Livewire;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_gateway_and_active_booking_contexts_render(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('Choose a request type and')
            ->assertSee('Request a service');

        foreach ([
            ['service' => 'boarding', 'variant' => 'cats'],
            ['service' => 'vet-care', 'variant' => 'microchip'],
            ['service' => 'relocation', 'variant' => 'import'],
        ] as $context) {
            Livewire::test('booking-request-wizard', ['initialContext' => $context])
                ->assertSet('services.0.service_key', $context['service'])
                ->assertSet('services.0.service_variant', $context['variant']);
        }
    }

    public function test_removed_contact_contexts_are_not_active_booking_services(): void
    {
        foreach (['grooming', 'training', 'local-transport', 'boarding-exotic'] as $service) {
            Livewire::test('booking-request-wizard', ['initialContext' => ['service' => $service]])
                ->assertSet('services.0.service_key', null)
                ->assertSet('services.0.service_variant', null);
        }

        $this->get('/contact?intent=transport&service=local-transport')
            ->assertOk()
            ->assertDontSee('Local Transport');
    }

    public function test_general_inquiry_keeps_optional_contact_fields_optional(): void
    {
        $this->get('/contact?intent=general')
            ->assertOk()
            ->assertSee('Your name (optional)')
            ->assertSee('WhatsApp number (optional)')
            ->assertDontSee('transportProduct')
            ->assertDontSee('pricingData');
    }

    public function test_unknown_product_context_preserves_reference_empty_state(): void
    {
        $this->get('/contact?intent=product-inquiry&product=missing-product')
            ->assertOk()
            ->assertSee('Product not found')
            ->assertSee('Product not found — please specify the product you’re asking about.');
    }

    public function test_contact_json_ld_uses_valid_schema_context(): void
    {
        $this->get('/contact')
            ->assertOk()
            ->assertSee('"@context":"https://schema.org"', false)
            ->assertSee('"openingHoursSpecification"', false);
    }

    public function test_contact_consumers_render_database_profile_values(): void
    {
        $profile = BusinessProfile::current();
        $profile->update([
            'phone' => '0800 111 2233',
            'phone_international' => '+234 800 111 2233',
            'whatsapp_url' => 'https://wa.example.test/database-profile',
            'map_url' => 'https://maps.example.test/database-profile',
            'address_street' => 'Database-owned Street',
            'address_city' => 'Database City',
            'address_postal_code' => '123456',
            'address_state' => 'Database State',
            'address_country' => 'Database Country',
            'instagram_url' => 'https://social.example.test/database-profile',
        ]);

        $response = $this->get('/contact');

        $response->assertOk()
            ->assertSee('0800 111 2233')
            ->assertSee('https://wa.example.test/database-profile', false)
            ->assertSee('Database-owned Street, Database City, 123456, Database State, Database Country', false);
    }
}
