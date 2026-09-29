<?php

use App\Support\CountryCatalog;
use Tests\TestCase;

uses(TestCase::class);

it('provides the configured ISO country list with searchable display names', function (): void {
    $catalog = app(CountryCatalog::class);

    expect($catalog->options())
        ->toHaveCount(249)
        ->toHaveKey('GH', 'Ghana')
        ->toHaveKey('NG', 'Nigeria')
        ->and($catalog->options('NG'))
        ->not->toHaveKey('NG')
        ->and($catalog->countries()[0])
        ->toHaveKeys(['code', 'name', 'aliases']);
});
