<?php

it('keeps pricing configuration access inside the pricing catalog', function (): void {
    $projectRoot = dirname(__DIR__, 2);
    $appRoot = $projectRoot.DIRECTORY_SEPARATOR.'app';
    $pricingCatalogPath = 'app/Support/BookingPricingCatalog.php';
    $pricingConfigLeaks = [];

    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($appRoot));

    foreach ($files as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($projectRoot) + 1));

        if ($relativePath === $pricingCatalogPath) {
            continue;
        }

        if (str_contains((string) file_get_contents($file->getPathname()), 'waggies_pricing')) {
            $pricingConfigLeaks[] = $relativePath;
        }
    }

    sort($pricingConfigLeaks);

    expect($pricingConfigLeaks)->toBe([]);
});
