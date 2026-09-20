<?php

test('service feature collections render as unordered lists', function () {
    $view = $this->blade(
        '<x-waggies.service-feature-grid :features="$features" />',
        [
            'features' => [
                ['icon' => 'verified', 'title' => 'Supervised care', 'desc' => 'Care from trained handlers.'],
            ],
        ],
    );

    $html = (string) $view;

    expect($html)
        ->toContain('<ul ')
        ->not->toContain('<ol ');
});
