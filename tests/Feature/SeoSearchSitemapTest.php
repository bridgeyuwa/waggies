<?php

namespace Tests\Feature;

use Tests\TestCase;

class SeoSearchSitemapTest extends TestCase
{
    public function test_homepage_renders_package_owned_head_metadata_and_schema(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();
        $siteUrl = rtrim((string) config('app.url'), '/');

        $response->assertOk()
            ->assertSee('<title>Pet Boarding, Grooming &amp; Vet Care in Abuja</title>', false)
            ->assertSee('<link rel="canonical" href="'.$siteUrl.'/">', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('"@type":"WebSite"', false);

        $this->assertSame(1, substr_count($html, '<title>'));
        $this->assertSame(1, substr_count($html, 'rel="canonical"'));
    }

    public function test_query_state_is_not_indexable_and_search_response_is_not_indexable(): void
    {
        $siteUrl = rtrim((string) config('app.url'), '/');

        $this->get('/contact?intent=booking')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.$siteUrl.'/contact">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false);

        $this->get('/api/search?q=grooming')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertJsonFragment(['href' => route('services.grooming')]);

        $this->get('/api/search?q=relocation')
            ->assertOk()
            ->assertJsonFragment(['href' => route('services.relocation')])
            ->assertJsonMissing(['href' => $siteUrl.'/relocation']);
    }

    public function test_sitemap_and_robots_publish_only_canonical_public_urls(): void
    {
        $sitemap = $this->get('/sitemap.xml')->assertOk();
        $xml = simplexml_load_string($sitemap->getContent());

        $this->assertNotFalse($xml);

        $locations = [];

        foreach ($xml->url as $url) {
            $locations[] = (string) $url->loc;
        }

        $siteUrl = rtrim((string) config('app.url'), '/');

        $this->assertSame($locations, array_values(array_unique($locations)));
        $this->assertSame([], array_filter($locations, fn (string $location): bool => str_contains($location, '?')));
        $this->assertSame([], array_filter($locations, fn (string $location): bool => str_contains($location, '/api/')));
        $this->assertContains($siteUrl.'/privacy-policy', $locations);
        $this->assertContains($siteUrl.'/guides/preparing-pet-boarding', $locations);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: '.$siteUrl.'/sitemap.xml', false)
            ->assertSee('Disallow: /api/', false);
    }

    public function test_missing_pages_render_noindex_error_metadata(): void
    {
        foreach ([
            '/not-a-real-page',
            '/guides/nonexistent-guide',
            '/knowledge-base/nonexistent-article',
            '/shop/nonexistent-product',
            '/services/relocation/nonexistent-service',
        ] as $path) {
            $response = $this->get($path);
            $html = $response->getContent();

            $response->assertNotFound()
                ->assertSee('We couldn’t find that page', false)
                ->assertSee('<title>Page not found - Waggies</title>', false)
                ->assertSee('<meta name="robots" content="noindex, follow">', false);

            $this->assertSame(0, substr_count($html, 'rel="canonical"'));
        }
    }
}
