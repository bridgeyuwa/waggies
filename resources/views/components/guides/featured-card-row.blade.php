{{--
    Pet Guides — Guide Row
    ======================
    Props:
        $href           (string)       Link URL. Default: '#'
        $icon           (string)       Material Symbols icon name. Default: 'article'
        $title          (string)       Guide title. Required.
        $meta           (string)       Metadata line (e.g. "Vet-approved · 8 min read · Vaccination").
        $badge          (string|null)  Optional badge label. Omit to hide.
        $badgeVariant   (string)       'success' (green tick) | 'primary' (purple pill). Default: 'primary'
--}}

@props([
    'href'         => '#',
    'icon'         => 'article',
    'title'        => '',
    'meta'         => '',
    'badge'        => null,
    'badgeVariant' => 'primary',
])

<a href="{{ $href }}"
   class="flex items-center gap-5 px-6 py-5 hover:bg-surface-purple/50 transition-colors group cursor-pointer">

    <div class="w-11 h-11 rounded-xl bg-surface-purple flex items-center justify-center text-primary shrink-0 group-hover:bg-primary group-hover:text-white transition-colors">
        <span class="material-symbols-outlined text-xl">{{ $icon }}</span>
    </div>

    <div class="flex-1 min-w-0">
        <p class="font-semibold text-primary-dark text-sm leading-snug mb-0.5 group-hover:text-primary transition-colors">
            {{ $title }}
        </p>
        @if($meta)
            <p class="text-primary-dark/45 text-xs">{{ $meta }}</p>
        @endif
    </div>

    <div class="flex items-center gap-3 shrink-0">
        @if($badge)
            @if($badgeVariant === 'success')
                <span class="hidden sm:inline-flex items-center gap-1 bg-success-light text-success text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    <span class="material-symbols-outlined icon-filled text-xs">check_circle</span>
                    {{ $badge }}
                </span>
            @else
                <span class="hidden sm:inline-flex items-center gap-1 bg-surface-purple text-primary text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                    {{ $badge }}
                </span>
            @endif
        @endif
        <span class="material-symbols-outlined text-primary-dark/30 group-hover:text-primary transition-colors">arrow_forward</span>
    </div>

</a>
