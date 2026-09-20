@props(['hover' => false])

<div {{ $attributes->class(['w-card', 'w-card-hover' => $hover]) }}>
    {{ $slot }}
</div>
