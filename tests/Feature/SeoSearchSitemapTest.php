<?php

namespace Tests\Feature;

use App\Models\BookingRequest;
use App\Models\ContactEnquiry;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoSearchSitemapTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_package_owned_head_metadata_and_schema(): void
    {
        $response = $this->get('/');
        $html = $response->getContent();
        $siteUrl = rtrim((string) config('app.url'), '/');

        $response->assertOk()
            ->assertSee('<title>Pet Boarding, Grooming &amp; Vet Care in Abuja</title>', false)
            ->assertSee('<link rel="canonical" href="'.$siteUrl.'/">', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('property="og:image" content="'.$siteUrl.'/social-card.svg"', false)
            ->assertSee('name="twitter:image" content="'.$siteUrl.'/social-card.svg"', false)
            ->assertSee('"@type":"WebSite"', false);

        $this->assertSame(1, substr_count($html, '<title>'));
        $this->assertSame(1, substr_count($html, 'rel="canonical"'));
    }

    public function test_breadcrumb_schema_is_rendered_once_through_laravel_head(): void
    {
        $html = $this->get(route('services.pricing'))->assertOk()->getContent();

        preg_match_all('/<script type="application\/ld\+json"[^>]*>(.*?)<\/script>/s', $html, $matches);

        $schemas = array_map(
            static fn (string $schema): array => json_decode($schema, true, flags: JSON_THROW_ON_ERROR),
            $matches[1] ?? [],
        );
        $breadcrumbSchemas = array_values(array_filter(
            $schemas,
            static fn (array $schema): bool => ($schema['@type'] ?? null) === 'BreadcrumbList',
        ));

        $this->assertCount(1, $breadcrumbSchemas);
        $this->assertSame('Home', $breadcrumbSchemas[0]['itemListElement'][0]['name']);
        $lastBreadcrumb = $breadcrumbSchemas[0]['itemListElement'][array_key_last($breadcrumbSchemas[0]['itemListElement'])];
        $this->assertSame(route('services.pricing'), $lastBreadcrumb['item']);
    }

    public function test_query_state_is_not_indexable_and_search_response_is_not_indexable(): void
    {
        $siteUrl = rtrim((string) config('app.url'), '/');

        $this->get('/book?service=grooming')
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.$siteUrl.'/book">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false);

        $this->get('/contact?intent=booking')->assertNotFound();

        $this->get('/api/search?q=grooming')
            ->assertOk()
            ->assertHeader('X-Robots-Tag', 'noindex, nofollow')
            ->assertJsonFragment(['href' => route('services.grooming')]);

        $this->get('/api/search?q=relocation')
            ->assertOk()
            ->assertJsonFragment(['href' => route('services.relocation')])
            ->assertJsonMissing(['href' => $siteUrl.'/relocation']);
    }

    public function test_page_specific_schema_types_are_rendered_without_global_business_schema(): void
    {
        $product = Product::query()->firstOrFail();

        $this->get(route('about'))
            ->assertOk()
            ->assertSee('"@type":"AboutPage"', false)
            ->assertDontSee('"@type":"LocalBusiness"', false);

        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('"@type":"ContactPage"', false);

        $this->get(route('services.boarding'))
            ->assertOk()
            ->assertSee('"@type":"Service"', false);

        $this->get(route('shop.show', ['product' => $product]))
            ->assertOk()
            ->assertSee('"@type":"Product"', false);

        $this->get(route('guides.show', ['slug' => 'preparing-pet-boarding']))
            ->assertOk()
            ->assertSee('"@type":"Article"', false);

        $this->get(route('faq'))
            ->assertOk()
            ->assertSee('"@type":"FAQPage"', false);
    }

    public function test_canonical_uses_configured_production_origin_and_drops_query_state(): void
    {
        config()->set([
            'app.url' => 'https://www.waggies.example',
            'app.env' => 'production',
        ]);

        $this->get('/?utm_source=external', ['Host' => 'attacker.example'])
            ->assertOk()
            ->assertSee('<link rel="canonical" href="https://www.waggies.example/">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false);
    }

    public function test_search_indexes_published_products_and_faqs(): void
    {
        $this->get('/api/search?q=Royal+Canin')
            ->assertOk()
            ->assertJsonFragment(['href' => route('shop.show', ['product' => 'royal-canin-puppy'])]);

        $this->get('/api/search?q=check-in')
            ->assertOk()
            ->assertJsonFragment(['category' => 'FAQ']);
    }

    public function test_search_does_not_index_private_operational_records(): void
    {
        ContactEnquiry::factory()->create([
            'name' => 'privatebatch30needle',
            'message' => 'Private contact content.',
        ]);
        BookingRequest::factory()->create([
            'name' => 'privatebatch30needle',
            'message' => 'Private booking content.',
        ]);
        NewsletterSubscriber::factory()->create(['email' => 'privatebatch30needle@example.com']);

        $this->get('/api/search?q=privatebatch30needle')
            ->assertOk()
            ->assertJsonPath('results', []);
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
        $this->assertContains($siteUrl.'/services/pricing', $locations);
        $this->assertNotContains($siteUrl.'/tools/cost-calculator', $locations);

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
                ->assertSee('<title>Page not found - Waggies</title>', false)
                ->assertSee('<meta name="robots" content="noindex, follow">', false);

            $this->assertSame(0, substr_count($html, 'rel="canonical"'));
            $this->assertStringNotContainsString('property="og:', $html);
            $this->assertStringNotContainsString('name="twitter:', $html);
            $this->assertStringNotContainsString('application/ld+json', $html);
        }
    }
}
