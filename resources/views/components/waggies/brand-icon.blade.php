@props([
    'name' => 'instagram',
    'size' => 18,
    'class' => '',
    'label' => null,
])

@php
    $brands = ['instagram', 'facebook', 'x', 'linkedin', 'tiktok', 'youtube', 'whatsapp'];
    $file = in_array($name, $brands, true) ? $name.'.svg' : 'instagram.svg';
@endphp

<span
    @if($label) role="img" aria-label="{{ $label }}" @else aria-hidden="true" @endif
    {{ $attributes->class(['inline-block shrink-0 bg-current', $class]) }}
    style="width: {{ $size }}px; height: {{ $size }}px; mask: url('{{ asset('icons/brands/'.$file) }}') center / contain no-repeat; -webkit-mask: url('{{ asset('icons/brands/'.$file) }}') center / contain no-repeat;"
></span>
