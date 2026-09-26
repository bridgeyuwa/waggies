<?php

namespace Tests\Feature;

use Tests\TestCase;

final class PublicAssetRoutesTest extends TestCase
{
    public function test_legacy_boarding_asset_urls_redirect_to_the_canonical_paths(): void
    {
        $redirects = [
            '/service-hero-boarding-cats.jpg' => '/media/services/boarding/hero-cats.jpg',
            '/service-hero-boarding-dogs.jpg' => '/media/services/boarding/hero-dogs.jpg',
            '/service-hero-boarding-exotic.jpg' => '/media/services/boarding/hero-exotic.jpg',
        ];

        foreach ($redirects as $legacyPath => $canonicalPath) {
            $this->get($legacyPath)
                ->assertMovedPermanently()
                ->assertRedirect($canonicalPath);
        }
    }
}
