@props([
    'actions' => [],
    'tone' => 'image',
])

@php
    $isImageTone = $tone === 'image';
    $normalizedActions = [];

    foreach ($actions as $index => $action) {
        $isPrimary = $index === 0;

        $normalizedActions[] = [
            'href' => $action['href'] ?? $action['url'] ?? null,
            'label' => $action['label'],
            'variant' => $action['variant'] ?? ($isPrimary ? 'primary' : 'secondary'),
            'tone' => $isImageTone ? 'dark' : 'light',
            'iconBefore' => $action['iconBefore'] ?? null,
            'iconAfter' => $action['iconAfter'] ?? $action['icon'] ?? ($isPrimary ? 'arrow-forward' : null),
            'iconSize' => $action['iconSize'] ?? 18,
            'target' => $action['target'] ?? null,
            'rel' => $action['rel'] ?? null,
        ];
    }
@endphp

<x-waggies.cta-actions :actions="$normalizedActions" align="start" {{ $attributes }} />
