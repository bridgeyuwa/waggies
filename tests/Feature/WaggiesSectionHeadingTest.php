<?php

namespace Tests\Feature;

use Tests\TestCase;

final class WaggiesSectionHeadingTest extends TestCase
{
    public function test_it_renders_the_required_title_and_optional_section_content(): void
    {
        $view = $this->blade(
            '<x-waggies.section-heading eyebrow="Care standards" title="Trusted pet care" subtitle="Thoughtful support for every stay."><x-slot:action><a href="/contact">Contact us</a></x-slot:action></x-waggies.section-heading>',
        );

        $view
            ->assertSee('Care standards')
            ->assertSee('Trusted pet care')
            ->assertSee('Thoughtful support for every stay.')
            ->assertSee('href="/contact"', false)
            ->assertSee('Contact us');
    }

    public function test_it_omits_optional_section_content_when_it_is_not_supplied(): void
    {
        $view = $this->blade('<x-waggies.section-heading title="Trusted pet care" />');

        $view
            ->assertSee('Trusted pet care')
            ->assertDontSee('text-eyebrow')
            ->assertDontSee('<a', false);
    }
}
