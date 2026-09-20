@props(['guide', 'featured' => false, 'routeName' => 'guides.show', 'showMeta' => false])

<article {{ $attributes }}>
    <x-waggies.card hover class="group h-full overflow-hidden border border-primary/5 p-0 {{ $featured ? 'md:flex' : '' }}">
        <a href="{{ route($routeName, ['slug' => $guide['slug']]) }}" class="flex h-full flex-col {{ $featured ? 'md:flex-row' : '' }}">
            <div class="relative overflow-hidden {{ $featured ? 'md:w-1/2 md:shrink-0' : 'aspect-[16/10]' }}">
                <x-waggies.image src="{{ $guide['image'] }}" alt="{{ $guide['title'] }}" class="absolute inset-0 h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.025] motion-reduce:transition-none motion-reduce:transform-none" />
            </div>

            <div class="flex flex-col gap-3 p-5 md:p-6 {{ $featured ? 'md:flex-1 md:justify-center' : '' }}">
                <span class="inline-flex self-start items-center rounded-full bg-surface-purple px-3 py-1 text-label text-primary">{{ $guide['category'] }}</span>

                <h3 class="line-clamp-2 font-serif font-bold text-primary-dark transition-colors group-hover:text-primary {{ $featured ? 'text-h3' : 'text-h4' }}">{{ $guide['title'] }}</h3>

                <p class="line-clamp-3 text-body-sm {{ $featured ? 'md:line-clamp-4' : '' }}">{{ $guide['excerpt'] }}</p>

                <div class="mt-auto flex flex-wrap items-center gap-x-4 gap-y-1 pt-1 text-meta">
                    @if($showMeta && !empty($guide['author']))
                        <span class="flex items-center gap-1"><x-waggies.icon name="team-member" size="14" />{{ $guide['author'] }}</span>
                    @endif
                    @if($showMeta && !empty($guide['date']))
                        @php($shortMonths = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sept', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'])
                        <span class="flex items-center gap-1"><x-waggies.icon name="calendar" size="14" />{{ date('j', strtotime($guide['date'])) }} {{ $shortMonths[(int) date('n', strtotime($guide['date']))] }} {{ date('Y', strtotime($guide['date'])) }}</span>
                    @endif
                    @if(($showMeta || $routeName === 'guides.show') && !empty($guide['readTime']))
                        <span class="flex items-center gap-1"><x-waggies.icon name="hours" size="14" />{{ $guide['readTime'] }}</span>
                    @endif
                </div>
            </div>
        </a>
    </x-waggies.card>
</article>
