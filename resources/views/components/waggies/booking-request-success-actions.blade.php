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

<div
    x-data="waggiesBookingWhatsAppActions({ url: @js($whatsappUrl) })"
    @click="handleClick($event)"
    {{ $attributes->class(['flex flex-col gap-3']) }}
>
    <p
        x-cloak
        x-show="statusMessage"
        x-text="statusMessage"
        class="text-sm text-primary-dark/60"
        role="status"
        aria-live="polite"
        aria-atomic="true"
    ></p>

    <x-waggies.cta-actions :actions="$actions" align="start" />
</div>
