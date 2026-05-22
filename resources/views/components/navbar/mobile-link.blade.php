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
