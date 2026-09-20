@props(['steps' => [], 'class' => ''])

<ol aria-label="Process steps" class="relative grid grid-cols-1 gap-x-8 gap-y-0 md:grid-cols-2 lg:grid-cols-4 {{ $class }}">
    @foreach($steps as $index => $step)
        <li class="relative border-t border-primary/15 py-7 md:px-6 md:first:pl-0 lg:border-l lg:border-t-0 lg:first:border-l-0 lg:first:pl-0 {{ $index === 0 ? 'md:rounded-tl-2xl md:border-primary/30 md:bg-surface-purple/35 md:py-8' : '' }}">
            <div class="flex items-start gap-4">
                <span class="font-serif text-4xl font-bold leading-none text-primary/30" aria-hidden="true">{{ $step['step'] }}</span>
                <div class="min-w-0"><h3 class="font-serif text-xl font-semibold leading-tight text-primary-dark">{{ $step['title'] }}</h3><p class="mt-2 text-sm leading-relaxed text-primary-dark/65">{{ $step['description'] }}</p></div>
            </div>
            @if($index < count($steps) - 1)<x-waggies.icon name="arrow-forward" size="18" class="absolute -bottom-2 right-4 hidden text-primary/50 lg:block" aria-hidden="true" />@endif
            <x-waggies.icon name="check-circle" size="17" class="mt-5 text-primary/60 lg:hidden" aria-hidden="true" />
        </li>
    @endforeach
</ol>
