@props(['name', 'role', 'image', 'badge' => null, 'roles' => []])

<div
    class="group relative bg-white rounded-2xl overflow-hidden border border-surface-purple shadow-sm hover:shadow-soft transition duration-300 hover:-translate-y-0.5">

    <!-- IMAGE -->
    <div class="relative aspect-4/5 overflow-hidden bg-surface-purple">

        <img src="{{ $image }}" alt="{{ $name }}" loading="lazy"
            class="w-full h-full object-contain transition-transform duration-700 group-hover:scale-[1.03]" />

        <!-- subtle overlay (kept stable, not flashy on hover) -->
        <div class="absolute inset-0 bg-gradient-to-t from-primary-dark/10 via-transparent to-transparent"></div>

        {{-- BADGE --}}
        @if ($badge)
            <div
                class="absolute top-4 left-4 bg-white/90 backdrop-blur text-primary px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm">
                {{ $badge }}
            </div>
        @endif

    </div>

    <!-- CONTENT -->
    <div class="p-6 flex flex-col gap-4">

        <div class="space-y-1">
            <h3 class="text-primary-dark font-bold font-serif text-xl leading-tight">
                {{ $name }}
            </h3>

            <p class="text-primary text-sm font-medium tracking-wide">
                {{ $role }}
            </p>
        </div>

        <p class="text-primary-dark/60 text-sm leading-relaxed line-clamp-2">
            {{ $slot }}
        </p>

        {{-- ROLE BADGES --}}
        @if (!empty($roles))
            <div class="flex flex-wrap gap-2">
                @foreach ($roles as $r)
                    <span
                        class="text-[10px] uppercase tracking-wide text-primary-dark/60 border border-primary/20 px-2 py-1 rounded-lg">
                        {{ $r }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- CTA --}}
        <div class="pt-4 border-t border-primary/10">

            <a href="#"
                class="inline-flex items-center gap-2 text-primary font-semibold text-sm uppercase tracking-wide hover:text-primary-dark transition-colors">

                View profile

                <span
                    class="material-symbols-outlined text-sm transition-transform duration-200 group-hover:translate-x-0.5">
                    arrow_forward
                </span>

            </a>

        </div>

    </div>

</div>
