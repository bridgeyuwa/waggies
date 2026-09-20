@props(['features' => []])

<ol class="mt-10 grid grid-cols-1 gap-x-12 md:grid-cols-2">
    @foreach($features as $index => $feature)
        <li class="group relative flex gap-4 border-t border-primary/12 py-6 first:border-t-0 md:[&:nth-child(2)]:border-t-0 {{ $index === 0 ? 'md:col-span-2 md:mb-2 md:rounded-2xl md:border md:border-primary/10 md:bg-surface-purple/45 md:px-6 md:py-7' : '' }}">
            <span class="mt-1 grid size-10 shrink-0 place-items-center rounded-xl bg-surface-purple text-primary transition-[background-color,transform] duration-[180ms] ease-out group-hover:-translate-y-0.5 group-hover:bg-primary group-hover:text-white" aria-hidden="true"><x-waggies.icon name="{{ $feature['icon'] }}" size="22" /></span>
            <div class="min-w-0"><h3 class="font-serif text-lg font-semibold text-primary-dark">{{ $feature['title'] }}</h3><p class="mt-1 text-sm leading-relaxed text-primary-dark/65">{{ $feature['desc'] }}</p></div>
        </li>
    @endforeach
</ol>
