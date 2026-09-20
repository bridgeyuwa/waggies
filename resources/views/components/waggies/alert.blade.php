@props(['type' => 'info', 'title' => null, 'role' => null])

@php
    $styles = match ($type) {
        'success' => ['wrapper' => 'border-success/25 bg-success-light text-success', 'icon' => 'check-circle'],
        'warning' => ['wrapper' => 'border-warning/30 bg-warning-light text-warning', 'icon' => 'warning'],
        'error' => ['wrapper' => 'border-error/25 bg-error-light text-error', 'icon' => 'error'],
        default => ['wrapper' => 'border-primary/15 bg-surface-purple text-primary', 'icon' => 'info'],
    };
    $semanticRole = $role ?: ($type === 'error' ? 'alert' : 'status');
@endphp

<div role="{{ $semanticRole }}" {{ $attributes->class(['flex items-start gap-3 rounded-xl border p-4', $styles['wrapper']]) }}>
    <x-waggies.icon name="{{ $styles['icon'] }}" size="20" variant="filled" class="mt-0.5 shrink-0" aria-hidden="true" />
    <div class="min-w-0 text-sm leading-relaxed">
        @if($title)<p class="font-semibold">{{ $title }}</p>@endif
        <div>{{ $slot }}</div>
    </div>
</div>
