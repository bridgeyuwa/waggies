@props([
    'name',
    'role',
    'image',
    'badge' => null,   // primary identity badge (single)
    'roles' => [],     // credential / role badges
])

<div class="group relative bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-soft transition duration-200 hover:-translate-y-0.5 border border-surface-purple cursor-pointer">

  <!-- IMAGE -->
  <div class="relative h-56 overflow-hidden">

    <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105 group-hover:-translate-y-1"
         style="background-image: url('{{ $image }}')">
    </div>

    <div class="absolute inset-0 bg-primary-dark/10 group-hover:bg-primary-dark/0 transition-colors duration-300"></div>

    {{-- PRIMARY BADGE --}}
    @if($badge)
      <div class="absolute top-4 right-4 bg-white/90 backdrop-blur text-primary px-3 py-1.5 rounded-full text-xs font-semibold shadow-sm transition transform group-hover:scale-105">
        {{ $badge }}
      </div>
    @endif

  </div>

  <!-- CONTENT -->
  <div class="p-6">

    <h3 class="text-primary-dark font-bold font-serif mb-1 text-xl">
      {{ $name }}
    </h3>

    <p class="text-primary text-sm font-medium mb-3 tracking-wide">
      {{ $role }}
    </p>

    <p class="text-primary-dark/50 text-sm mb-4 line-clamp-2">
      {{ $slot }}
    </p>

    {{-- ROLE BADGES --}}
    @if(!empty($roles))
      <div class="flex flex-wrap gap-2 mb-4 opacity-[.85] translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition duration-300 delay-75">

        @foreach($roles as $r)
          <span class="text-[10px] uppercase tracking-wide text-primary-dark/60 border border-primary/20 px-2 py-1 rounded-lg">
            {{ $r }}
          </span>
        @endforeach

      </div>
    @endif

    <a href="#"
       class="inline-flex items-center text-primary font-semibold text-sm tracking-wide uppercase transition-colors group-hover:text-primary-light">

      View profile

      <span class="material-symbols-outlined text-sm ml-1 transition-transform duration-200 group-hover:translate-x-0.5">
        arrow_forward
      </span>

    </a>

  </div>

</div>