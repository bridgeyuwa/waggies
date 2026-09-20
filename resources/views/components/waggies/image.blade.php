@props([
    'src',
    'alt',
    'loading' => 'lazy',
    'fetchPriority' => null,
    'decoding' => 'async',
])

<img
    src="{{ $src }}"
    alt="{{ $alt }}"
    loading="{{ $loading }}"
    decoding="{{ $decoding }}"
    @if($fetchPriority) fetchpriority="{{ $fetchPriority }}" @endif
    {{ $attributes }}
>
