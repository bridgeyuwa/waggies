<?php

namespace Tests\Feature;

use Tests\TestCase;

final class WaggiesPrimitiveComponentsTest extends TestCase
{
    public function test_button_preserves_navigation_and_action_semantics(): void
    {
        $link = $this->blade('<x-waggies.button href="/contact">Contact</x-waggies.button>');
        $button = $this->blade('<x-waggies.button type="submit" disabled>Save</x-waggies.button>');

        $link->assertSee('<a href="/contact"', false);
        $link->assertDontSee('<button', false);
        $button->assertSee('<button type="submit" disabled', false);
        $button->assertDontSee('<a ', false);
    }

    public function test_form_primitives_associate_labels_help_text_and_errors(): void
    {
        $view = $this->blade('<x-waggies.input id="email" name="email" label="Email address" help="We will only use this to reply." error="Enter a valid email address." required />');

        $view->assertSee('for="email"', false);
        $view->assertSee('id="email-help"', false);
        $view->assertSee('id="email-error"', false);
        $view->assertSee('aria-describedby="email-help email-error"', false);
        $view->assertSee('aria-invalid="true"', false);
    }

    public function test_icons_are_decorative_by_default_and_can_be_semantic(): void
    {
        $view = $this->blade('<x-waggies.icon name="info" /><x-waggies.icon name="info" label="More information" />');

        $view->assertSee('aria-hidden="true"', false);
        $view->assertSee('role="img" aria-label="More information"', false);
    }

    public function test_zoom_in_icon_uses_a_registered_asset_instead_of_the_fallback_icon(): void
    {
        $view = $this->blade('<x-waggies.icon name="zoom-in" />');

        $view->assertSee('search.svg', false);
        $view->assertDontSee('pets.svg', false);
    }
}
