{{--
    Breadcrumb navigation (B6).

    Props:
      $crumbs — array of ['label' => string, 'href' => string|null]
                The last item should have href = null (renders as plain text).

    Example:
      <x-breadcrumb :crumbs="[
          ['label' => 'Home', 'href' => route('home')],
          ['label' => 'Services', 'href' => route('services.index')],
          ['label' => 'Dog Boarding', 'href' => null],
      ]" />
--}}
@props([
    'crumbs' => [],
])

<nav aria-label="Breadcrumb">
    <ol class="flex items-center flex-wrap gap-2 text-xs font-medium text-primary-dark/50">
        <li>
            <a href="{{ route('home') }}"
               class="hover:text-primary transition-colors"
               aria-label="Home">
                <span class="material-symbols-outlined text-sm" aria-hidden="true">home</span>
            </a>
        </li>
        @foreach($crumbs as $crumb)
            <li class="flex items-center gap-2">
                <span class="material-symbols-outlined text-xs text-primary-dark/30" aria-hidden="true">chevron_right</span>
                @if(!empty($crumb['href']))
                    <a href="{{ $crumb['href'] }}" class="hover:text-primary transition-colors">
                        {{ $crumb['label'] }}
                    </a>
                @else
                    <span class="text-primary-dark font-semibold" aria-current="page">
                        {{ $crumb['label'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
