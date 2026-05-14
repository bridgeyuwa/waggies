@props([
    'heading' => null,
    'subtitle' => null,
    'items' => [],
])

<section class="w-full py-[var(--spacing-section)] bg-white">
    <div class="max-w-[var(--width-container)] mx-auto px-4 md:px-10 lg:px-12">

        @if ($heading || $subtitle)
            <div class="mb-12">
                @if ($heading)
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                        {{ $heading }}
                    </h2>
                @endif

                @if ($subtitle)
                    <p class="text-primary-dark/60 text-lg max-w-prose">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
        @endif

        <ol class="relative space-y-8 pl-8
                   before:absolute before:left-[11px] before:top-2 before:bottom-2
                   before:w-0.5 before:bg-primary/20"
            aria-label="Timeline">

            @foreach ($items as $item)
                <li class="relative">

                    {{-- Icon --}}
                    <div class="absolute -left-[41px] bg-primary rounded-full p-1 border-4 border-white"
                        aria-hidden="true">
                        <span class="material-symbols-outlined text-sm text-white">
                            {{ $item['icon'] ?? 'schedule' }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="font-serif font-bold text-primary-dark">
                        {{ $item['title'] }}
                    </h3>

                    {{-- Description --}}
                    @if (!empty($item['description']))
                        <p class="text-sm text-primary-dark/60">
                            {{ $item['description'] }}
                        </p>
                    @endif

                </li>
            @endforeach

        </ol>

    </div>
</section>
