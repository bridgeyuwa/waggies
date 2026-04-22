{{--
    Availability status badge.

    Props:
      $status — available | limited | full
      $label  — override display text (defaults to capitalised status)
--}}
@props([
    'status' => 'available',
    'label'  => null,
])

@php
    [$bg, $text, $icon] = match($status) {
        'limited' => ['bg-warning-light', 'text-warning', 'schedule'],
        'full'    => ['bg-error-light',   'text-error',   'block'],
        default   => ['bg-success-light', 'text-success', 'check_circle'],
    };
    $display = $label ?? ucfirst($status);
@endphp

<span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $bg }} {{ $text }}">
    <span class="material-symbols-outlined icon-filled text-sm" aria-hidden="true">{{ $icon }}</span>
    {{ $display }}
</span>
