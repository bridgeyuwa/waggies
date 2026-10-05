@props(['whatsappUrl'])

@php
    $actions = [
        [
            'href' => $whatsappUrl,
            'label' => 'Continue on WhatsApp',
            'target' => '_blank',
            'rel' => 'noopener noreferrer',
            'iconAfter' => 'arrow-forward',
            'class' => 'w-full sm:w-auto',
        ],
        [
            'href' => route('book'),
            'label' => 'Send another request',
            'variant' => 'secondary',
            'class' => 'w-full sm:w-auto',
        ],
    ];
@endphp

<x-waggies.cta-actions :actions="$actions" align="start" {{ $attributes }} />
