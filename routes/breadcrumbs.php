<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('waggies-path', function (BreadcrumbTrail $trail, array $items): void {
    $trail->push('Home', route('home'));

    foreach ($items as $item) {
        $url = null;

        if (! empty($item['route'])) {
            $url = route($item['route'], $item['params'] ?? []);
        } elseif (! empty($item['href'])) {
            $url = $item['href'];
        }

        $trail->push((string) $item['label'], $url);
    }
});
