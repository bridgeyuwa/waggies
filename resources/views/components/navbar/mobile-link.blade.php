{{-- Simple link row inside mobile drawer accordion panels --}}
@props([
    'href' => '#',
    'label' => '',
])

<a href="{{ $href }}"
   @click="mobileOpen = false"
   class="flex items-center gap-2 px-3 py-2.5 text-sm text-primary-dark/70 hover:text-primary
          hover:bg-surface-purple rounded-lg transition-colors">
    <span class="w-1 h-1 rounded-full bg-primary/40 shrink-0" aria-hidden="true"></span>
    {{ $label }}
</a>

{{-- 
    Mobile grouped nav link for use inside an accordion group.

    Usage example:
    <x-navbar.mobile-link-group label="Services">
        <x-navbar.mobile-link href="..." label="Dog Walking" />
        <x-navbar.mobile-link href="..." label="Grooming" />
    </x-navbar.mobile-link-group>
--}}

@once
    @push('components')
        @php
            /**
             * Group wrapper for mobile nav sections.
             *
             * Props:
             * - label: heading for the group
             * - icon: optional Material Symbol icon (text)
             */
        @endphp
        @component('components.navbar.mobile-link-group', ['label' => $groupLabel ?? null, 'icon' => $groupIcon ?? null])
            {{ $slot ?? '' }}
        @endcomponent
    @endpush
@endonce

{{-- Grouped mobile nav link group --}}
@props([
    'label',
    'icon' => null,
])
<div class="mb-2">
    <div class="flex items-center gap-2 px-3 pt-4 pb-2 uppercase text-xs text-primary/70 tracking-widest font-semibold">
        @if ($icon)
            <span class="material-symbols-outlined text-base text-primary/60">{{ $icon }}</span>
        @endif
        <span>{{ $label }}</span>
    </div>
    <div class="flex flex-col gap-1">
        {{ $slot }}
    </div>
</div>


