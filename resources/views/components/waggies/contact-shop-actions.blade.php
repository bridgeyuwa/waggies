@php
    $actions = [
        [
            'href' => route('shop.index'),
            'label' => 'Browse Shop',
            'iconAfter' => 'arrow-forward',
            'iconSize' => 18,
        ],
        [
            'href' => route('contact'),
            'label' => 'Back',
            'variant' => 'secondary',
            'iconBefore' => 'arrow-back',
            'iconSize' => 18,
        ],
    ];
@endphp

<x-waggies.cta-actions :actions="$actions" align="start" {{ $attributes }} />
