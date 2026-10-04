<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RelocationRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_relocation_hub_and_import_export_routes_are_public(): void
    {
        foreach ([
            'services.relocation' => '/services/relocation',
            'relocation.import' => '/services/relocation/import',
            'relocation.export' => '/services/relocation/export',
            'relocation.checklist' => '/services/relocation/checklist',
        ] as $name => $path) {
            $this->assertSame($path, route($name, absolute: false));
            $this->get($path)->assertOk();
        }
    }

    public function test_local_transport_is_not_a_relocation_route_or_public_link(): void
    {
        $this->get('/services/relocation/transport')->assertNotFound();
        $this->get('/relocation/transport')->assertNotFound();

        $this->get(route('services.relocation'))
            ->assertOk()
            ->assertDontSee('Local Transport')
            ->assertDontSee('href="'.url('/services/relocation/transport').'"', false);
    }

    public function test_sitemap_contains_active_relocation_urls_and_policy(): void
    {
        $xml = simplexml_load_string($this->get('/sitemap.xml')->assertOk()->getContent());
        $locations = [];

        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }
        $siteUrl = rtrim((string) config('app.url'), '/');

        foreach ([
            '/services/relocation',
            '/services/relocation/import',
            '/services/relocation/export',
            '/relocation-policy',
        ] as $path) {
            $this->assertContains($siteUrl.$path, $locations);
        }

        foreach (['/services/relocation/transport', '/relocation/transport'] as $path) {
            $this->assertNotContains($siteUrl.$path, $locations);
        }

        $this->assertSame($locations, array_values(array_unique($locations)));
    }

    public function test_relocation_pages_explain_request_based_import_and_export(): void
    {
        $this->get(route('services.relocation'))
            ->assertOk()
            ->assertSee('Pet Import to Nigeria')
            ->assertSee('Pet Export from Nigeria')
            ->assertSee('Request-based import and export coordination');

        $this->get(route('relocation.import'))
            ->assertOk()
            ->assertSee('href="'.e(route('book', ['service' => 'relocation', 'direction' => 'import'])).'"', false);

        $this->get(route('relocation.export'))
            ->assertOk()
            ->assertSee('href="'.e(route('book', ['service' => 'relocation', 'direction' => 'export'])).'"', false);
    }
}
