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

@if($label)<div class="flex flex-col gap-1.5"><label for="{{ $controlId }}" class="text-sm font-medium text-primary-dark">{{ $label }}@if($required) <span class="text-primary" aria-hidden="true">*</span>@endif</label>@endif
<select
    @if($controlId) id="{{ $controlId }}" @endif
    @if($required) required @endif
    @if($error) aria-invalid="true" @endif
    @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
    {{ $attributes->class(['contact-input']) }}
>
    {{ $slot }}
</select>
@if($help && $helpId)<p id="{{ $helpId }}" class="text-xs text-primary-dark/50">{{ $help }}</p>@endif
@if($error && $errorId)<x-waggies.field-error id="{{ $errorId }}" :message="$error" />@endif
@if($label)</div>@endif
