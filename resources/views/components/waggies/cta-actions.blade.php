@props([
    'actions' => [],
    'align' => 'center',
])

@php
    $alignmentClass = match ($align) {
        'start' => 'justify-start',
        'end' => 'justify-end',
        default => 'justify-center',
    };
@endphp

<div {{ $attributes->class(['flex flex-wrap gap-3', $alignmentClass]) }}>
    @foreach($actions as $action)
        <x-waggies.button
            :href="$action['href'] ?? null"
            :variant="$action['variant'] ?? 'primary'"
            :tone="$action['tone'] ?? 'light'"
            :size="$action['size'] ?? 'md'"
            :type="$action['type'] ?? 'button'"
            :disabled="$action['disabled'] ?? false"
            :loading="$action['loading'] ?? false"
            :target="$action['target'] ?? null"
            :rel="$action['rel'] ?? null"
            :class="$action['class'] ?? ''"
        >
            @if(!empty($action['iconBefore']))
                <x-waggies.icon name="{{ $action['iconBefore'] }}" size="{{ $action['iconSize'] ?? 18 }}" />
            @endif
            {{ $action['label'] }}
            @if(!empty($action['iconAfter']) || !empty($action['icon']))
                <x-waggies.icon name="{{ $action['iconAfter'] ?? $action['icon'] }}" size="{{ $action['iconSize'] ?? 18 }}" />
            @endif
        </x-waggies.button>
    @endforeach
</div>
