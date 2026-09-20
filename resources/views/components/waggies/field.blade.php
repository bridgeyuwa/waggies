@props([
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
])

@php
    $helpId = $help && $id ? $id.'-help' : null;
    $errorId = $error && $id ? $id.'-error' : null;
@endphp

@if(!$label && !$help && !$error)
    {{ $slot }}
@else
    <div {{ $attributes->class(['flex flex-col gap-1.5']) }}>
        @if($label)
            <label for="{{ $id }}" class="text-sm font-medium text-primary-dark">
                {{ $label }}@if($required) <span class="text-primary" aria-hidden="true">*</span>@endif
            </label>
        @endif

        {{ $slot }}

        @if($help && $helpId)
            <p id="{{ $helpId }}" class="text-xs text-primary-dark/50">{{ $help }}</p>
        @endif

        @if($error && $errorId)
            <x-waggies.field-error id="{{ $errorId }}" :message="$error" />
        @endif
    </div>
@endif
