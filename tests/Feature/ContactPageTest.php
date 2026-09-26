<?php

namespace Tests\Feature;

use App\Models\BusinessProfile;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    public function test_contact_gateway_and_contextual_requests_render(): void
    {
        $this->get('/contact')->assertOk()->assertSee('What can Waggies help you with?');
        $this->get('/contact?intent=booking')->assertOk()->assertSee('What would you like to book?');
        $this->get('/contact?intent=veterinary&service=vet-care')->assertOk()->assertSee('Veterinary appointment');
    }

    public function test_contextual_schema_preserves_reference_fields_and_conditions(): void
    {
        $this->get('/contact?intent=service&service=boarding&variant=cats')
            ->assertOk()
            ->assertSee('feedingRequirements')
            ->assertSee('medications')
            ->assertSee('behavioralConsiderations')
            ->assertSee('specialCareNeeds')
            ->assertSee('Cozy')
            ->assertSee('Exact packages and pricing vary by pet type');

        $this->get('/contact?intent=transport&service=local-transport')
            ->assertOk()
            ->assertSee('transportProduct')
            ->assertSee('distanceKm')
            ->assertSee('additionalPetSafe')
            ->assertSee('stopsWithinCorridor')
            ->assertSee('airportDetails')
            ->assertSee('transport-airport-transfer');

        $this->get('/contact?intent=quote&service=relocation-import')
            ->assertOk()
            ->assertSee('documentationStatus')
            ->assertSee('Default destination is our facility in Abuja');
    }

    public function test_transport_route_context_is_carried_into_the_contact_engine(): void
    {
        $route = json_encode([
            'origin' => 'Maitama, Abuja',
            'destination' => 'Wuse 2, Abuja',
            'journeyType' => 'return',
            'distanceKm' => 8,
            'petCount' => 2,
            'petSpecies' => 'dog',
            'additionalPetSafe' => 'true',
        ], JSON_THROW_ON_ERROR);

        $this->get('/contact?intent=transport&service=local-transport&transportRoute='.urlencode($route))
            ->assertOk()
            ->assertSee('transportRoute')
            ->assertSee('Maitama, Abuja');
    }

    public function test_general_inquiry_keeps_optional_contact_fields_optional(): void
    {
        $this->get('/contact?intent=general')
            ->assertOk()
            ->assertSee('Your name (optional)')
            ->assertSee('WhatsApp number (optional)');
    }

    public function test_pricing_payload_contains_reference_boarding_grooming_training_and_transport_rules(): void
    {
        $response = $this->get('/contact?intent=service&service=training');

        $response->assertOk()
            ->assertSee('Basic Obedience')
            ->assertSee('Puppy Foundation')
            ->assertSee('Behaviour Modification');

        $this->get('/contact?intent=transport&service=local-transport')
            ->assertOk()
            ->assertSee('pricingData')
            ->assertSee('transportProduct');
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

    public function test_contact_and_global_business_consumers_render_database_profile_values(): void
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
            ->assertSee('href="tel:2348001112233"', false)
            ->assertSee('https://wa.example.test/database-profile', false)
            ->assertSee('https://maps.example.test/database-profile', false)
            ->assertSee('Database-owned Street, Database City, 123456, Database State, Database Country', false)
            ->assertSee('https://social.example.test/database-profile', false);

        $address = 'Database-owned Street, Database City, 123456, Database State, Database Country';
        $directionsUrl = 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($address);
        $content = $response->getContent();

        $this->assertIsString($content);
        $this->assertSame(1, substr_count($content, $address));
        $this->assertStringContainsString('href="'.e($directionsUrl).'"', $content);
        $this->assertStringNotContainsString('https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d6484.734419313616', $content);
        $this->assertStringNotContainsString('height="340"', $content);
    }
}
