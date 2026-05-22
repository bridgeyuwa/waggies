{{-- Waggies B6 breadcrumb template for diglactic/laravel-breadcrumbs --}}
@unless ($breadcrumbs->isEmpty())
    <nav aria-label="Breadcrumb">
        <ol class="flex items-center flex-wrap gap-2 text-xs font-medium text-primary-dark/50">
            <li>
                <a href="{{ route('home') }}"
                   class="hover:text-primary transition-colors flex items-center gap-1"
                   aria-label="Home">
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">home</span>
                </a>
            </li>

            @foreach ($breadcrumbs as $breadcrumb)
                <li class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-xs text-primary-dark/30" aria-hidden="true">chevron_right</span>

                    @if ($breadcrumb->url && ! $loop->last)
                        <a href="{{ $breadcrumb->url }}" class="hover:text-primary transition-colors">
                            {{ $breadcrumb->title }}
                        </a>
                    @else
                        <span class="text-primary-dark font-semibold" @if($loop->last) aria-current="page" @endif>
                            {{ $breadcrumb->title }}
                        </span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endunless
