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

<x-waggies.field :id="$controlId" :label="$label" :help="$help" :error="$error" :required="$required">
    <input
        @if($controlId) id="{{ $controlId }}" @endif
        type="{{ $type }}"
        @if($value !== null) value="{{ $value }}" @endif
        @if($required) required @endif
        @if($error) aria-invalid="true" @endif
        @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->class([$fieldClasses]) }}
    >
</x-waggies.field>
