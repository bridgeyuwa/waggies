@props([
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
])

@php
    $controlId = $id ?: ($attributes->get('name') ? 'field-'.$attributes->get('name') : null);
    $helpId = $help && $controlId ? $controlId.'-help' : null;
    $errorId = $error && $controlId ? $controlId.'-error' : null;
    $describedBy = collect([$helpId, $errorId])->filter()->join(' ');
@endphp

<x-waggies.field :id="$controlId" :label="$label" :help="$help" :error="$error" :required="$required">
    <select
        @if($controlId) id="{{ $controlId }}" @endif
        @if($required) required @endif
        @if($error) aria-invalid="true" @endif
        @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->class(['contact-input']) }}
    >
        {{ $slot }}
    </select>
</x-waggies.field>
