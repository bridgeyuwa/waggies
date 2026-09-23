@props([
    'actions' => [],
    'tone' => 'image',
])

@php
    /** @var array<int, array{label: string, url: string, icon?: string, iconBefore?: string}> $actions */
    $isImageTone = $tone === 'image';
@endphp

<div {{ $attributes->class(['flex flex-wrap gap-3']) }}>
    @foreach($actions as $index => $action)
        @php($isPrimary = $index === 0)
        <x-waggies.button
            href="{{ $action['url'] }}"
            variant="{{ $isPrimary ? 'primary' : 'outline' }}"
            class="{{ $isPrimary && $isImageTone ? '!bg-secondary !text-primary-dark hover:!bg-secondary-hover' : '' }} {{ ! $isPrimary && $isImageTone ? 'border-white/30 bg-transparent text-white hover:bg-white/10' : '' }}"
        >
            @if(! empty($action['iconBefore']))
                <x-waggies.icon name="{{ $action['iconBefore'] }}" size="18" class="{{ $isImageTone ? 'text-white' : '' }}" />
            @endif

            {{ $action['label'] }}

            @if($isPrimary)
                <x-waggies.icon name="{{ $action['icon'] ?? 'arrow-forward' }}" size="18" />
            @endif
        </x-waggies.button>
    @endforeach
</div>
