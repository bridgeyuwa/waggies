<?php

test('global dialogs render an accessible trigger and modal contract', function (): void {
    $this->get(route('home'))
        ->assertSee('id="global-search-dialog"', false)
        ->assertSee('id="global-cart-dialog"', false)
        ->assertSee('role="dialog" aria-modal="true" aria-labelledby="search-title"', false)
        ->assertSee('role="dialog" aria-modal="true" aria-labelledby="cart-title"', false)
        ->assertSee('aria-controls="global-search-dialog" aria-expanded="false"', false)
        ->assertSee('aria-controls="global-cart-dialog" aria-expanded="false"', false)
        ->assertSee('x-ref="input"', false)
        ->assertSee('x-ref="closeButton"', false)
        ->assertSee('@keydown="handleDialogKeydown($event)"', false);
});
