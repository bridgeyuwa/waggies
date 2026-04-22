{{--
    Pet Guides — Featured Card
    ==========================
    Props:
        $title          (string)  Card heading. Required.
        $description    (string)  Supporting body text.
        $badge          (string)  Badge text. Default: 'Free Download'
        $downloadUrl    (string)  Primary button URL. Default: '#'
        $downloadLabel  (string)  Primary button label. Default: 'Download Free PDF'
        $readUrl        (string)  Secondary button URL. Default: '#'
        $readLabel      (string)  Secondary button label. Default: 'Read online'
        $pages          (int)     Page count on the document badge. Optional.
--}}

@props([
    'title'         => '',
    'description'   => '',
    'badge'         => 'Free Download',
    'downloadUrl'   => '#',
    'downloadLabel' => 'Download Free PDF',
    'readUrl'       => '#',
    'readLabel'     => 'Read online',
    'pages'         => null,
])

<div class="group bg-primary-dark rounded-2xl overflow-hidden mb-6 relative flex flex-col sm:flex-row card-lift cursor-pointer">

    <div class="absolute top-0 right-0 w-56 h-56 bg-primary/50 rounded-full blur-3xl pointer-events-none translate-x-1/3 -translate-y-1/3"></div>

    <div class="relative z-10 p-8 flex flex-col justify-between flex-1 gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 bg-secondary/20 text-secondary text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">
                <span class="material-symbols-outlined text-sm">download</span>
                {{ $badge }}
            </span>
            <h3 class="font-serif font-bold text-white text-2xl leading-snug mb-2">
                {{ $title }}
            </h3>
            @if($description)
                <p class="text-white/60 text-sm leading-relaxed max-w-sm">{{ $description }}</p>
            @endif
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ $downloadUrl }}"
               class="inline-flex items-center gap-2 bg-secondary hover:bg-secondary-hover text-primary-dark px-6 py-2.5 rounded-full font-bold text-sm transition shadow-glow hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-secondary/60">
                <span class="material-symbols-outlined text-sm">download</span>
                {{ $downloadLabel }}
            </a>
            <a href="{{ $readUrl }}"
               class="inline-flex items-center gap-2 border border-white/25 text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/30">
                {{ $readLabel }}
            </a>
        </div>
    </div>

    <div class="hidden sm:flex items-center justify-center p-8 relative z-10 shrink-0">
        <div class="w-32 h-32 rounded-2xl bg-white/10 border border-white/15 flex flex-col items-center justify-center gap-2">
            <span class="material-symbols-outlined text-secondary text-4xl">description</span>
            <p class="text-white text-[10px] font-bold uppercase tracking-widest">PDF Guide</p>
            @if($pages)
                <p class="text-white/40 text-[10px]">{{ $pages }} pages</p>
            @endif
        </div>
    </div>

</div>
