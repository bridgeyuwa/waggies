<?php

namespace Tests\Feature;

use Tests\TestCase;

final class WaggiesHeroComponentsTest extends TestCase
{
    public function test_cover_hero_preserves_semantic_media_and_action_links(): void
    {
        $view = $this->blade(
            '<x-waggies.cover-hero :hero="$hero"><x-slot:supporting>Trusted care</x-slot:supporting></x-waggies.cover-hero>',
            [
                'hero' => [
                    'eyebrow' => 'Pet Care',
                    'title' => 'A better stay for your pet',
                    'description' => 'Personalised care from a trusted team.',
                    'imageSrc' => 'https://example.test/hero.jpg',
                    'imageAlt' => 'Dog resting in a Waggies suite',
                    'primaryAction' => ['label' => 'Book now', 'route' => 'contact'],
                ],
            ],
        );

        $view->assertSee('<img', false);
        $view->assertSee('alt="Dog resting in a Waggies suite"', false);
        $view->assertSee('fetchpriority="high"', false);
        $view->assertSee('Trusted care');
        $view->assertSee('href="'.route('contact').'"', false);
    }

    public function test_page_header_renders_tool_identity_as_part_of_a_standard_page_heading(): void
    {
        $view = $this->blade('<x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="calculator" title="Cost Calculator" description="Estimate your pet care costs." />');

        $view->assertSee('<h1', false);
        $view->assertSee('Cost Calculator');
        $view->assertSee('Estimate your pet care costs.');
        $view->assertSee('Pet Care Tool');
    }
}
