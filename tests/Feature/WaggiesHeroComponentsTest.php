<?php

test('cover hero preserves semantic media and action links', function () {
    $view = $this->blade(
        '<x-waggies.cover-hero :hero="$hero"><x-slot:supporting>Trusted care</x-slot:supporting></x-waggies.cover-hero>',
        [
            'hero' => [
                'eyebrow' => 'Pet Care',
                'title' => 'A better stay for your pet',
                'description' => 'Personalised care from a trusted team.',
                'imageSrc' => 'https://example.test/hero.jpg',
                'imageAlt' => 'Dog resting in a Waggies suite',
                'actions' => [
                    ['label' => 'Book now', 'route' => 'contact'],
                ],
            ],
        ],
    );

    $view->assertSee('<img', false);
    $view->assertSee('alt="Dog resting in a Waggies suite"', false);
    $view->assertSee('fetchpriority="high"', false);
    $view->assertSee('Trusted care');
    $view->assertSee('href="'.route('contact').'"', false);
});

test('page header supports contextual identity and actions without changing its heading semantics', function () {
    $view = $this->blade(
        '<x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="calculator" title="Cost Calculator" description="Estimate your pet care costs."><x-slot:supporting>Reviewed by our care team</x-slot:supporting><x-slot:actions><x-waggies.hero-actions :actions="[[\'label\' => \'Start\', \'href\' => \'/start\']]" tone="light" /></x-slot:actions></x-waggies.page-header>',
    );

    $view->assertSee('<h1 id="page-header-title"', false);
    $view->assertSee('aria-labelledby="page-header-title"', false);
    $view->assertSee('Cost Calculator');
    $view->assertSee('Estimate your pet care costs.');
    $view->assertSee('Pet Care Tool');
    $view->assertSee('Reviewed by our care team');
    $view->assertSee('href="/start"', false);
});

test('button renders a dynamic Alpine href binding as an anchor', function () {
    $view = $this->blade(
        '<x-waggies.button x-bind:href="whatsappUrl()" target="_blank">Continue to WhatsApp</x-waggies.button>',
    );

    $view->assertSee('x-bind:href="whatsappUrl()"', false);
    $view->assertSee('target="_blank"', false);
    $view->assertSee('Continue to WhatsApp');
    $view->assertDontSee('<button', false);
});
