{{--
    Pagination (Laravel paginator compatible).

    Usage with Laravel paginator:
      <x-pagination :paginator="$posts" />

    Props:
      $paginator — Laravel LengthAwarePaginator instance
--}}
@props([
    'paginator' => null,
])

@if($paginator && $paginator->hasPages())
    <nav aria-label="Pagination" class="flex items-center justify-center gap-2 flex-wrap">

        {{-- Previous --}}
        @if($paginator->onFirstPage())
            <span class="size-10 flex items-center justify-center rounded-lg border border-primary/30
                         text-primary-dark/30 cursor-not-allowed"
                  aria-disabled="true" aria-label="Previous page">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="size-10 flex items-center justify-center rounded-lg border border-primary/30
                      text-primary-dark hover:border-primary hover:text-primary transition-colors
                      focus:outline-none focus:ring-2 focus:ring-primary/60"
               aria-label="Previous page">
                <span class="material-symbols-outlined text-base">chevron_left</span>
            </a>
        @endif

        {{-- Page numbers --}}
        @foreach($paginator->links()->elements as $element)
            @if(is_string($element))
                <span class="text-primary-dark/30 px-1">{{ $element }}</span>
            @endif

            @if(is_array($element))
                @foreach($element as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="size-10 flex items-center justify-center rounded-full
                                     bg-primary text-white text-sm font-bold"
                              aria-current="page" aria-label="Page {{ $page }}">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="size-10 flex items-center justify-center rounded-lg border border-primary/30
                                  text-primary-dark text-sm hover:border-primary hover:text-primary transition-colors
                                  focus:outline-none focus:ring-2 focus:ring-primary/60"
                           aria-label="Page {{ $page }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="size-10 flex items-center justify-center rounded-lg border border-primary/30
                      text-primary-dark hover:border-primary hover:text-primary transition-colors
                      focus:outline-none focus:ring-2 focus:ring-primary/60"
               aria-label="Next page">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </a>
        @else
            <span class="size-10 flex items-center justify-center rounded-lg border border-primary/30
                         text-primary-dark/30 cursor-not-allowed"
                  aria-disabled="true" aria-label="Next page">
                <span class="material-symbols-outlined text-base">chevron_right</span>
            </span>
        @endif

    </nav>
@endif
