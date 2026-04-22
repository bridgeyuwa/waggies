{{--
    Blog Card Component
    Usage:
        <x-blog-card
            :post="$post"
            featured
        />

    Props:
        $post     → array/object with keys: image, category, categoryStyle, readTime,
                     title, excerpt, authorInitial, authorName, authorDate, url
        $featured → boolean (optional) – shows "Featured" badge
--}}

@props([
    'post',
    'featured' => false,
])

@php
    $categoryStyle = $post['categoryStyle'] ?? 'primary'; // 'primary' | 'secondary'
    $badgeClass = $categoryStyle === 'secondary'
        ? 'bg-secondary/60 text-primary-dark'
        : 'bg-surface-purple text-primary';
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white border border-surface-purple rounded-2xl overflow-hidden card-lift cursor-pointer flex flex-col']) }}>

    {{-- Image --}}
    <div class="img-zoom h-52 w-full relative">
        <div
            class="bg-img h-full w-full bg-cover bg-center"
            style="background-image: url('{{ $post['image'] }}');"
        ></div>
        <div class="absolute inset-0 bg-primary-dark/10 group-hover:bg-primary-dark/0 transition-colors"></div>

        @if ($featured)
            <span class="absolute top-4 left-4 bg-primary text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider shadow-glow">
                Featured
            </span>
        @endif
    </div>

    {{-- Body --}}
    <div class="p-6 flex flex-col flex-1">

        {{-- Meta --}}
        <div class="flex items-center gap-2 mb-3">
            <span class="{{ $badgeClass }} text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider">
                {{ $post['category'] }}
            </span>
            <span class="text-primary-dark/40 text-xs">{{ $post['readTime'] }}</span>
        </div>

        {{-- Title --}}
        <h3 class="font-serif font-bold text-primary-dark {{ $featured ? 'text-xl' : 'text-lg' }} leading-snug mb-2 line-clamp-2">
            {{ $post['title'] }}
        </h3>

        {{-- Excerpt --}}
        <p class="text-primary-dark/55 text-sm leading-relaxed {{ $featured ? 'line-clamp-3' : 'line-clamp-2' }} flex-1">
            {{ $post['excerpt'] }}
        </p>

        {{-- Footer --}}
        <div class="flex items-center justify-between mt-5 pt-4 border-t border-surface-purple">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-full bg-surface-purple flex items-center justify-center text-primary font-bold font-serif text-xs">
                    {{ $post['authorInitial'] }}
                </div>
                <span class="text-xs font-medium text-primary-dark/60">
                    {{ $post['authorName'] }} · {{ $post['authorDate'] }}
                </span>
            </div>
            <a
                href="{{ $post['url'] }}"
                class="inline-flex items-center gap-1 text-primary text-xs font-semibold uppercase tracking-wide group-hover:text-primary-light transition-colors"
            >
                Read <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

    </div>
</div>
