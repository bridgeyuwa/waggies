{{--
    Featured story — prominent in-content highlight (blog & guides index/category).

    Matches guides.featured-card / home blog patterns: section hero stays separate;
    this sits below the hub hero inside the listing content area.

    Props:
      post — Post model (required)
      type — blog | guide
--}}
@props([
    'post',
    'type' => 'blog',
])

@php
    $badge = $type === 'guide' ? 'Featured Guide' : 'Featured';
    $badgeIcon = $type === 'guide' ? 'menu_book' : 'star';
    $readLabel = $type === 'guide' ? 'Read guide' : 'Read article';
@endphp

<a href="{{ $post->url }}"
   class="group block mb-10 rounded-2xl overflow-hidden bg-primary-dark relative card-lift focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">

    <div class="absolute top-0 right-0 w-72 h-72 bg-primary/50 rounded-full blur-3xl pointer-events-none translate-x-1/3 -translate-y-1/3"
         aria-hidden="true"></div>

    <div class="relative z-10 flex flex-col lg:flex-row">

        <div class="lg:w-2/5 aspect-[16/10] lg:aspect-auto lg:min-h-[240px] relative overflow-hidden shrink-0">
            <img src="{{ $post->hero_image_url }}" alt=""
                 class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="eager"
                 aria-hidden="true" />
            <div class="absolute inset-0 bg-gradient-to-r from-transparent to-primary-dark/90 lg:to-primary-dark"></div>
        </div>

        <div class="flex flex-col justify-between gap-4 p-8 lg:p-10 flex-1">
            <div>
                <span class="inline-flex items-center gap-1.5 bg-secondary/20 text-secondary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">
                    <span class="material-symbols-outlined text-sm icon-filled" aria-hidden="true">{{ $badgeIcon }}</span>
                    {{ $badge }}
                </span>

                <span class="text-xs font-bold uppercase tracking-widest text-white/50 capitalize block mb-2">
                    {{ str_replace('-', ' ', $post->category) }}
                </span>

                <h2 class="font-serif font-bold text-white text-2xl md:text-3xl leading-snug group-hover:text-secondary transition-colors">
                    {{ $post->title }}
                </h2>

                @if ($post->excerpt)
                    <p class="text-white/60 text-sm leading-relaxed max-w-lg mt-3 line-clamp-3">{{ $post->excerpt }}</p>
                @endif
            </div>

            <div class="flex flex-wrap items-center justify-between gap-4">
                <p class="text-white/40 text-xs">
                    {{ $post->author }}
                    · {{ $post->published_at->format('F j, Y') }}
                    · {{ $post->reading_time }} min read
                </p>
                <span class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover text-primary-dark px-6 py-2.5 rounded-full font-bold text-sm transition shadow-glow group-hover:-translate-y-0.5">
                    {{ $readLabel }}
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                </span>
            </div>
        </div>

    </div>
</a>
