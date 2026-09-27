<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelocationRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_relocation_service_routes_use_the_canonical_services_hierarchy(): void
    {
        $routes = [
            'services.relocation' => '/services/relocation',
            'relocation.import' => '/services/relocation/import',
            'relocation.export' => '/services/relocation/export',
            'relocation.checklist' => '/services/relocation/checklist',
        ];

        foreach ($routes as $name => $path) {
            $this->assertSame($path, route($name, absolute: false));
            $this->get($path)->assertOk();
        }
    }

    public function test_retired_relocation_routes_return_not_found(): void
    {
        foreach ([
            '/relocation',
            '/relocation/import',
            '/relocation/export',
            '/relocation/transport',
            '/relocation/checklist',
            '/services/relocation/transport',
        ] as $path) {
            $this->get($path)->assertNotFound();
        }
    }

    public function test_sitemap_contains_canonical_relocation_routes_only(): void
    {
        $xml = simplexml_load_string($this->get('/sitemap.xml')->assertOk()->getContent());
        $locations = [];
        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        foreach ([
            '/services/relocation',
            '/services/relocation/import',
            '/services/relocation/export',
            '/services/relocation/checklist',
        ] as $path) {
            $this->assertNotSame([], array_filter($locations, static fn (string $location): bool => parse_url($location, PHP_URL_PATH) === $path));
        }

        foreach ([
            '/relocation',
            '/relocation/import',
            '/relocation/export',
            '/relocation/transport',
            '/relocation/checklist',
            '/services/relocation/transport',
        ] as $path) {
            $this->assertSame([], array_filter($locations, static fn (string $location): bool => parse_url($location, PHP_URL_PATH) === $path));
        }

        $this->assertSame($locations, array_values(array_unique($locations)));
    }

    public function test_relocation_hub_secondary_actions_open_the_checklist(): void
    {
        $this->get(route('services.relocation'))
            ->assertOk()
            ->assertSeeText('Open Relocation Checklist')
            ->assertSee('href="'.route('relocation.checklist').'"', false);
    }

    public function test_operational_policy_pages_are_public(): void
    {
        $routes = [
            'boarding-policy',
            'cancellation-policy',
            'relocation-policy',
        ];

        foreach ($routes as $routeName) {
            $this->get(route($routeName))->assertOk();
        }

        $this->get(route('boarding-policy'))
            ->assertSeeText('Boarding Policy')
            ->assertSeeText('Last updated: 1 January 2026')
            ->assertSeeText('Accepted species and admission')
            ->assertSeeText('Dog size guidance')
            ->assertSeeText('Check-in, check-out and late pickup');

        $this->get(route('cancellation-policy'))
            ->assertSeeText('Cancellation Policy')
            ->assertSeeText('More than 48 hours before check-in')
            ->assertSeeText('No-show');

        $this->get(route('relocation-policy'))
            ->assertSeeText('Relocation Policy')
            ->assertSeeText('Import')
            ->assertSeeText('Export')
            ->assertSeeText('Quotes and deposits');

        foreach ([
            '/policies' => 'boarding-policy',
            '/policy' => 'boarding-policy',
            '/policies/boarding-requirements' => 'boarding-policy',
            '/policies/cancellation-rescheduling-refunds' => 'cancellation-policy',
            '/policies/check-in-check-out-late-pickup' => 'boarding-policy',
            '/policies/medication-special-care' => 'boarding-policy',
            '/policies/emergency-veterinary-care' => 'boarding-policy',
            '/policies/pet-behaviour-safety' => 'boarding-policy',
            '/policies/relocation' => 'relocation-policy',
            '/policies/general-service-terms' => 'terms-of-service',
        ] as $path => $routeName) {
            $this->get($path)->assertRedirect(route($routeName));
        }
    }
}
