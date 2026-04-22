{{--
    Testimonial card (B5).

    Props:
      $stars          — integer 1–5
      $quote          — review text (can use $slot instead)
      $authorInitial  — single character for avatar
      $authorName     — full name
      $authorSubtitle — e.g. "Dog owner · Lagos"
--}}
@props([
    'stars'         => 5,
    'quote'         => null,
    'authorInitial' => 'A',
    'authorName'    => '',
    'authorSubtitle'=> '',
])

<div class="bg-white p-6 rounded-2xl shadow-sm hover:shadow-soft transition-shadow flex flex-col gap-4">

    {{-- Stars --}}
    <div class="flex gap-0.5" aria-label="{{ $stars }} out of 5 stars">
        @for($i = 0; $i < 5; $i++)
            <span class="material-symbols-outlined icon-filled text-gold text-base" aria-hidden="true">
                {{ $i < $stars ? 'star' : 'star_outline' }}
            </span>
        @endfor
    </div>

    {{-- Quote --}}
    <p class="italic text-sm leading-relaxed text-primary-dark/70 flex-1">
        "{{ $quote ?? $slot }}"
    </p>

    {{-- Author --}}
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-surface-purple flex items-center justify-center
                    text-primary font-bold text-sm shrink-0">
            {{ strtoupper(substr($authorInitial, 0, 1)) }}
        </div>
        <div>
            <p class="text-sm font-semibold text-primary-dark">{{ $authorName }}</p>
            <p class="text-xs text-primary-dark/50">{{ $authorSubtitle }}</p>
        </div>
    </div>

</div>
