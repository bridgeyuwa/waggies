{{--
    Service card (B4). Full card is clickable via .card-link stretched pseudo-element.

    Props:
      $title       — card heading
      $description — short summary (line-clamped to 2 lines)
      $href        — destination URL
      $imageSrc    — background image URL
      $icon        — Material Symbol name shown in top-right badge
      $ctaLabel    — link text. Default: "Learn more"
--}}
@props([
    'title'       => '',
    'description' => '',
    'href'        => '#',
    'imageSrc'    => '',
    'icon'        => 'pets',
    'ctaLabel'    => 'Learn more',
])

<div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm
            hover:shadow-soft hover:-translate-y-0.5 transition-all duration-300">

    {{-- Image with zoom + dark overlay --}}
    <div class="relative h-48 bg-cover bg-center overflow-hidden transition-transform duration-700 group-hover:scale-105"
         style="background-image: url('{{ $imageSrc }}')">
        <div class="absolute inset-0 bg-primary-dark/20 group-hover:bg-transparent transition-colors duration-300"></div>
        <div class="absolute top-4 right-4">
            <div class="w-9 h-9 rounded-full bg-white/90 flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-primary text-lg icon-filled">{{ $icon }}</span>
            </div>
        </div>
    </div>

    {{-- Body --}}
    <div class="p-6">
        <h3 class="font-serif text-xl font-bold text-primary-dark mb-2">{{ $title }}</h3>
        <p class="text-sm text-primary-dark/60 leading-relaxed line-clamp-2 mb-4">
            {{ $description }}
        </p>
        <a href="{{ $href }}"
           class="card-link inline-flex items-center gap-1.5 text-sm font-semibold text-primary
                  hover:text-primary-dark transition-colors">
            {{ $ctaLabel }}
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>

</div>
