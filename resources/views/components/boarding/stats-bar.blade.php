{{--
    Floating stats bar below hero.

    Props:
      $stats — array of ['value' => string, 'label' => string]
--}}
@props([
    'stats' => [],
])

<div
    class="relative z-30 -mt-8 -mb-8 mx-4 sm:mx-10 max-w-[1200px] lg:mx-auto bg-white rounded-2xl shadow-soft border border-surface-purple">
    <div class="px-6 py-8 sm:px-10 grid grid-cols-2 md:grid-cols-4 gap-8 md:divide-x md:divide-primary/10">
        @foreach ($stats as $stat)
            <div class="flex flex-col items-center text-center">
                <span class="font-serif text-4xl font-bold text-primary mb-1">{{ $stat['value'] }}</span>
                <span class="text-xs uppercase tracking-widest text-primary-dark/50 font-semibold">{{ $stat['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>
