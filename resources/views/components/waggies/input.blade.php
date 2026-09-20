@props([
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'type' => 'text',
    'required' => false,
    'value' => null,
])

@php
    $controlId = $id ?: ($attributes->get('name') ? 'field-'.$attributes->get('name') : null);
    $helpId = $help && $controlId ? $controlId.'-help' : null;
    $errorId = $error && $controlId ? $controlId.'-error' : null;
    $describedBy = collect([$helpId, $errorId])->filter()->join(' ');
    $fieldClasses = 'contact-input'.($error ? ' border-error' : '');
@endphp

@if($label)
    <div class="flex flex-col gap-1.5">
        <label for="{{ $controlId }}" class="text-sm font-medium text-primary-dark">
            {{ $label }}@if($required) <span class="text-primary" aria-hidden="true">*</span>@endif
        </label>
@endif

<input
    @if($controlId) id="{{ $controlId }}" @endif
    type="{{ $type }}"
    @if($value !== null) value="{{ $value }}" @endif
    @if($required) required @endif
    @if($error) aria-invalid="true" @endif
    @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
    {{ $attributes->class([$fieldClasses]) }}
>

@if($help && $helpId)<p id="{{ $helpId }}" class="text-xs text-primary-dark/50">{{ $help }}</p>@endif
@if($error && $errorId)<x-waggies.field-error id="{{ $errorId }}" :message="$error" />@endif

@if($label)</div>@endif
