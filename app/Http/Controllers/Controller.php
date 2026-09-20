<?php

namespace App\Http\Controllers;

use Laravel\Head\Facades\Head;

abstract class Controller
{
    /**
     * Apply resource-owned metadata through Laravel Head.
     *
     * Controllers remain responsible for choosing the values and indexability
     * policy; this method only adapts those values to the installed package.
     *
     * @param  array<string, mixed>  $metadata
     * @param  array<int, array<string, mixed>>  $schemas
     */
    protected function setPageHead(array $metadata, array $schemas = []): void
    {
        $title = (string) ($metadata['title'] ?? 'Waggies - Pet Care, Abuja');
        $description = (string) ($metadata['description'] ?? 'Waggies provides boarding, grooming, vet care, training, relocation and local transport services in Abuja, Nigeria.');
        $canonical = $metadata['canonical'] ?? null;
        $robots = $metadata['robots'] ?? ['index', 'follow'];
        $ogTitle = (string) ($metadata['ogTitle'] ?? $title);
        $ogDescription = (string) ($metadata['ogDescription'] ?? $description);
        $ogImage = (string) ($metadata['ogImage'] ?? asset('logo.svg'));

        Head::title($title, exact: true)
            ->description($description)
            ->robots($robots)
            ->meta('googlebot', implode(', ', (array) $robots).', max-video-preview:-1, max-image-preview:large, max-snippet:-1')
            ->og(
                type: $metadata['ogType'] ?? 'website',
                title: $ogTitle,
                description: $ogDescription,
                url: $canonical,
                image: $ogImage,
                siteName: 'Waggies',
                locale: 'en_NG',
            )
            ->twitter(
                card: 'summary_large_image',
                title: $ogTitle,
                description: $ogDescription,
                image: (string) ($metadata['twitterImage'] ?? $ogImage),
            );

        if (is_string($canonical) && $canonical !== '') {
            Head::canonical($canonical, forceHttps: false);
        }

        foreach ($schemas as $schema) {
            Head::schema($schema);
        }
    }
}
