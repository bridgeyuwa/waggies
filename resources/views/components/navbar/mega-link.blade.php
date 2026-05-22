{{-- Reusable link row used inside desktop flyout / mega-menu panels --}}
@props([
    'href' => '#',
    'icon' => 'pets',
    'title' => '',
    'subtitle' => '',
    'inMenu' => false,
])

<a href="{{ $href }}"
   @if($inMenu) role="menuitem" tabindex="-1" @endif
   class="flex items-center gap-3 px-2 py-2 rounded-xl hover:bg-surface-purple transition-colors group">
    <div class="w-8 h-8 rounded-lg bg-surface-purple group-hover:bg-primary group-hover:text-white
                flex items-center justify-center text-primary transition-colors shrink-0">
        <span class="material-symbols-outlined text-base">{{ $icon }}</span>
    </div>
    <div>
        <p class="text-sm font-semibold text-primary-dark group-hover:text-primary transition-colors leading-snug">
            {{ $title }}
        </p>
        @if($subtitle)
            <p class="text-xs text-primary-dark/50 leading-snug mt-0.5">{{ $subtitle }}</p>
        @endif
    </div>
</a>
