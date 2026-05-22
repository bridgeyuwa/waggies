{{--
    Day-in-the-life section: timeline + photo mosaic.

    Props:
      $headingId — id for aria-labelledby
      $heading — section title
      $subtitle — intro copy
      $schedule — array of ['icon', 'title', 'description']
      $images — array of ['src', 'alt', 'loading' => eager|lazy, 'class' => optional height class]
--}}
@props([
    'headingId' => 'daily-heading',
    'heading' => 'A Day in the Life at Waggies',
    'subtitle' => '',
    'schedule' => [],
    'images' => [],
])

<section aria-labelledby="{{ $headingId }}" class="py-20 w-full bg-surface-purple">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
        <div class="flex flex-col lg:flex-row gap-12 items-center">
            <div class="flex-1 space-y-8">
                <div>
                    <h2 id="{{ $headingId }}"
                        class="font-serif text-3xl md:text-4xl font-bold text-primary-dark leading-tight mb-4">
                        {{ $heading }}
                    </h2>
                    @if ($subtitle)
                        <p class="text-primary-dark/60 text-lg max-w-prose">{{ $subtitle }}</p>
                    @endif
                </div>

                <ol class="space-y-6" aria-label="Daily schedule">
                    @foreach ($schedule as $index => $step)
                        <li class="grid grid-cols-[32px_1fr] gap-4 items-start">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 || $loop->last ? 'bg-primary' : 'bg-primary/80' }} border-4 border-white">
                                <span class="material-symbols-outlined text-sm text-white"
                                    aria-hidden="true">{{ $step['icon'] }}</span>
                            </div>
                            <div>
                                <h3 class="font-serif font-bold text-primary-dark">{{ $step['title'] }}</h3>
                                <p class="text-sm text-primary-dark/60">{{ $step['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>

            @if (count($images) >= 4)
                <div class="flex-1 w-full" aria-hidden="true">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-4 pt-8">
                            <img loading="{{ $images[0]['loading'] ?? 'lazy' }}" decoding="async"
                                class="rounded-2xl w-full h-48 object-cover shadow-[var(--shadow-soft)]"
                                alt="{{ $images[0]['alt'] }}" src="{{ $images[0]['src'] }}">
                            <img loading="{{ $images[1]['loading'] ?? 'lazy' }}" decoding="async"
                                class="rounded-2xl w-full h-64 object-cover shadow-[var(--shadow-soft)]"
                                alt="{{ $images[1]['alt'] }}" src="{{ $images[1]['src'] }}">
                        </div>
                        <div class="space-y-4">
                            <img loading="{{ $images[2]['loading'] ?? 'lazy' }}" decoding="async"
                                class="rounded-2xl w-full h-64 object-cover shadow-[var(--shadow-soft)]"
                                alt="{{ $images[2]['alt'] }}" src="{{ $images[2]['src'] }}">
                            <img loading="{{ $images[3]['loading'] ?? 'lazy' }}" decoding="async"
                                class="rounded-2xl w-full h-48 object-cover shadow-[var(--shadow-soft)]"
                                alt="{{ $images[3]['alt'] }}" src="{{ $images[3]['src'] }}">
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
