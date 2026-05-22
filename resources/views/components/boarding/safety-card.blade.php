{{--
    Safety policy callout card.

    Props:
      $title — heading text
      $intro — lead paragraph
      $bullets — array of strings
      $linkLabel — optional CTA link text
      $linkHref — optional CTA href
--}}
@props([
    'title' => 'Safety First Policy',
    'intro' => '',
    'bullets' => [],
    'linkLabel' => null,
    'linkHref' => null,
])

<section class="w-full py-20 bg-surface border-y border-surface-purple">
    <div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12">
        <div class="bg-white rounded-2xl p-6 md:p-12 shadow-sm border border-surface-purple">
            <h2 class="font-serif font-bold text-primary-dark mb-4 flex items-center gap-3 text-xl">
                <span class="material-symbols-outlined text-primary" aria-hidden="true">health_and_safety</span>
                {{ $title }}
            </h2>

            @if ($intro)
                <p class="text-primary-dark/70 mb-6 leading-relaxed max-w-prose">{{ $intro }}</p>
            @endif

            @if (count($bullets) > 0)
                <ul class="space-y-2 text-sm text-primary-dark/70 mb-6" role="list">
                    @foreach ($bullets as $bullet)
                        <li>{{ $bullet }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($linkLabel && $linkHref)
                <a href="{{ $linkHref }}"
                    class="text-primary font-bold hover:underline inline-flex items-center gap-1 focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2 rounded">
                    {{ $linkLabel }}
                    <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
                </a>
            @endif
        </div>
    </div>
</section>
