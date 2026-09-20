<?php

namespace Tests\Feature;

use Tests\TestCase;

final class AboutPagesTest extends TestCase
{
    public function test_about_child_pages_render_with_page_metadata(): void
    {
        $pages = [
            ['uri' => '/about/testimonials', 'title' => 'Client Testimonials'],
            ['uri' => '/about/gallery', 'title' => 'Photo Gallery'],
            ['uri' => '/about/careers', 'title' => 'Careers at Waggies'],
            ['uri' => '/about/partnerships', 'title' => 'Partnerships'],
        ];

        foreach ($pages as $page) {
            $this->get($page['uri'])
                ->assertOk()
                ->assertSee('<title>'.$page['title'].'</title>', false)
                ->assertSee('application/ld+json', false);
        }
    }
}
