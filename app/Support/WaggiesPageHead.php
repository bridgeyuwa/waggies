<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Laravel\Head\Facades\Head;
use Spatie\SchemaOrg\Schema;

final class WaggiesPageHead
{
    /**
     * Apply resource-owned metadata through Laravel Head.
     *
     * @param  array<string, mixed>  $metadata
     * @param  array<int, array<string, mixed>>  $schemas
     */
    public function apply(array $metadata, array $schemas = []): void
    {
        $title = (string) ($metadata['title'] ?? 'Waggies - Pet Care, Abuja');
        $description = (string) ($metadata['description'] ?? 'Waggies provides boarding, grooming, vet care, training, relocation and local transport services in Abuja, Nigeria.');
        $canonical = $this->normalizeCanonical($metadata['canonical'] ?? null);
        $hasQueryState = request()->query() !== [];
        $robots = $hasQueryState ? ['noindex', 'follow'] : ($metadata['robots'] ?? ['index', 'follow']);
        $canonicalDisabled = array_key_exists('canonical', $metadata) && $metadata['canonical'] === null;

        if ($hasQueryState && $canonical === null && ! $canonicalDisabled) {
            $canonical = $this->normalizeCanonical(request()->url());
        }

        $ogTitle = (string) ($metadata['ogTitle'] ?? $title);
        $ogDescription = (string) ($metadata['ogDescription'] ?? $description);
        $ogImage = (string) ($metadata['ogImage'] ?? asset('social-card.svg'));

        Head::title($title, exact: true)
            ->description($description)
            ->robots($robots)
            ->meta('googlebot', implode(', ', (array) $robots).', max-video-preview:-1, max-image-preview:large, max-snippet:-1')
            ->applicationName('Waggies')
            ->meta('author', 'Waggies')
            ->meta('generator', 'Laravel')
            ->meta('keywords', 'pet boarding Abuja,pet care Abuja,pet relocation Nigeria,dog grooming Abuja,veterinary care,Waggies')
            ->referrer('origin-when-cross-origin')
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

        Head::favicon(asset('favicon.ico'));

        if (is_string($canonical) && $canonical !== '') {
            Head::canonical($canonical, forceHttps: app()->isProduction());
        }

        foreach ($schemas as $schema) {
            Head::schema($schema);
        }
    }

    private function normalizeCanonical(mixed $canonical): ?string
    {
        if (! is_string($canonical) || trim($canonical) === '') {
            return null;
        }

        $configuredParts = parse_url((string) config('app.url'));
        $host = $configuredParts['host'] ?? null;

        if (! is_string($host) || $host === '') {
            return null;
        }

        $scheme = app()->isProduction()
            ? 'https'
            : ($configuredParts['scheme'] ?? 'https');
        $port = isset($configuredParts['port']) ? ':'.$configuredParts['port'] : '';
        $path = parse_url($canonical, PHP_URL_PATH);
        $path = is_string($path) && $path !== '' ? $path : '/';

        return $scheme.'://'.$host.$port.'/'.ltrim($path, '/');
    }

    /**
     * Register the rendered breadcrumb trail as structured data through Laravel Head.
     *
     * @param  Collection<int, object>  $breadcrumbs
     */
    public function registerBreadcrumbs(Collection $breadcrumbs): void
    {
        if ($breadcrumbs->isEmpty()) {
            return;
        }

        $breadcrumbSchema = Schema::breadcrumbList()
            ->itemListElement($breadcrumbs->values()->map(
                static fn (object $breadcrumb, int $index): object => Schema::listItem()
                    ->position($index + 1)
                    ->name((string) $breadcrumb->title)
                    ->item((string) ($breadcrumb->url ?: url()->current()))
            )->all())
            ->toArray();

        Head::schema($breadcrumbSchema);
    }
}
