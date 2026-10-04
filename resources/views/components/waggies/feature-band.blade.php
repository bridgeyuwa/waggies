@props(['band'])

@php
    $stats = $band['stats'] ?? [];
    $features = $band['features'] ?? [];
    $cta = $band['cta'] ?? null;
    $ctaDestination = static fn (array $cta): string => isset($cta['route']) ? route($cta['route'], $cta['params'] ?? []) : url($cta['href']);
@endphp

<section class="w-full bg-primary-dark py-24 text-white">
    <div class="page-container">
        @if($stats !== [])
            <div class="mb-16 grid grid-cols-2 gap-6 border-b border-white/10 pb-14 text-center md:grid-cols-4">
                @foreach($stats as $stat)
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex size-10 items-center justify-center rounded-full bg-white/10 text-secondary">
                            <x-waggies.icon name="{{ $stat['icon'] }}" size="20" />
                        </div>
                        <p class="text-sm font-bold text-white">{{ $stat['title'] }}</p>
                        <p class="text-xs text-white/60">{{ $stat['subtitle'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 items-center gap-16 lg:grid-cols-2">
            <div class="flex max-w-xl flex-col gap-6">
                @if(!empty($band['eyebrow']))
                    <p class="text-xs font-bold uppercase tracking-widest text-secondary">{{ $band['eyebrow'] }}</p>
                @endif
                <h2 class="font-serif text-3xl font-bold leading-tight text-white md:text-4xl">{!! $band['title'] !!}</h2>
                @if(!empty($band['subtitle']))
                    <p class="text-base leading-relaxed text-white/70">{{ $band['subtitle'] }}</p>
                @endif
                @if($cta !== null)
                    <div class="pt-2">
                        <x-waggies.button href="{{ $ctaDestination($cta) }}" class="bg-secondary! text-primary-dark! hover:bg-secondary-hover!">
                            {{ $cta['label'] }}
                            <x-waggies.icon name="arrow-forward" size="16" />
                        </x-waggies.button>
                    </div>
                @endif
            </div>

            <div class="flex flex-col gap-5">
                @foreach($features as $feature)
                    <div class="flex items-start gap-4 rounded-2xl border border-white/10 bg-white/3 p-5 transition hover:bg-white/6">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-full bg-white/10 text-secondary">
                            <x-waggies.icon name="{{ $feature['icon'] }}" size="18" />
                        </div>
                        <div>
                            <h3 class="font-serif text-lg font-bold text-white">{{ $feature['title'] }}</h3>
                            <p class="mt-1 text-sm leading-relaxed text-white/60">{{ $feature['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
