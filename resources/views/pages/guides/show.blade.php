@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Guides', 'route' => 'guides.index'], ['label' => $guide['title']]]" class="bg-white border-b border-primary/5" />

    <article class="page-container py-10 md:py-16">
        <div class="lg:grid lg:grid-cols-[1fr_240px] lg:gap-12">
            <div class="max-w-3xl">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary px-3 py-1 text-label text-white"><x-waggies.icon name="guide" size="16" />Guide</span>
                        <span class="inline-flex items-center rounded-full bg-surface-purple px-3 py-1 text-label text-primary">{{ $guide['category'] }}</span>
                    </div>

                    <h1 class="font-serif text-2xl md:text-3xl lg:text-4xl font-bold text-primary-dark leading-tight mb-6">{{ $guide['title'] }}</h1>

                    @if(!empty($guide['readTime']))
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-meta mb-8 pb-8 border-b border-border-subtle"><span class="flex items-center gap-1.5"><x-waggies.icon name="hours" size="16" />{{ $guide['readTime'] }}</span></div>
                    @endif

                    @if(!empty($guide['relocation']['isGuide']))
                        <section class="mb-8 rounded-2xl border border-primary/10 bg-surface-purple/40 p-5 sm:p-6" aria-labelledby="relocation-guide-details">
                            <h2 id="relocation-guide-details" class="font-serif text-lg font-bold text-primary-dark">Relocation guide details</h2>

                            @if(!empty($guide['relocation']['originCountry']) && !empty($guide['relocation']['destinationCountry']))
                                <p class="mt-2 text-sm font-semibold text-primary-dark">
                                    {{ $guide['relocation']['originCountry'] }} <span aria-hidden="true">→</span> {{ $guide['relocation']['destinationCountry'] }}
                                </p>
                            @endif

                            @if(!empty($guide['relocation']['lastReviewedAt']))
                                <p class="mt-2 text-sm text-primary-dark/70">Route information last reviewed {{ $guide['relocation']['lastReviewedAt'] }}.</p>
                            @endif

                            <p class="mt-3 text-sm leading-relaxed text-primary-dark/70">Relocation requirements can change. Waggies reviews the route, timing, documentation, veterinary steps, and airline arrangements before confirming a request.</p>
                        </section>
                    @endif
                </div>

                <div class="relative aspect-video rounded-2xl overflow-hidden mb-10">
                    <img src="{{ $guide['image'] }}" @if(filled($guide['imageSrcset'] ?? null)) srcset="{{ $guide['imageSrcset'] }}" sizes="(min-width: 1024px) 800px, 100vw" @endif alt="{{ $guide['imageAlt'] }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
                </div>

                <x-waggies.article-toc :headings="$headings" mobile />

                <div class="prose max-w-none">{!! $processedContent !!}</div>

                @if(!empty($guide['relocation']['sourceLinks']))
                    <section class="mt-10 border-t border-border-subtle pt-8" aria-labelledby="official-sources-heading">
                        <h2 id="official-sources-heading" class="font-serif text-xl font-bold text-primary-dark">Official sources</h2>
                        <ul class="mt-4 space-y-2 text-sm">
                            @foreach($guide['relocation']['sourceLinks'] as $sourceLink)
                                <li>
                                    <a href="{{ $sourceLink['url'] }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-primary underline decoration-primary/30 underline-offset-4 hover:decoration-primary">
                                        {{ $sourceLink['label'] }} <span aria-hidden="true">↗</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if(!empty($guide['relocation']['isGuide']))
                    @php($bookingParameters = ['service' => 'relocation'])
                    @if(!empty($guide['relocation']['direction']))
                        @php($bookingParameters['direction'] = $guide['relocation']['direction'])
                    @endif
                    <div class="mt-10 rounded-2xl bg-primary-dark p-6 text-white sm:p-8">
                        <h2 class="font-serif text-2xl font-bold">Planning your pet’s move?</h2>
                        <p class="mt-2 max-w-2xl text-sm leading-relaxed text-white/75">Share your route and timing with Waggies. Our team will review the details and contact you about the next step.</p>
                        <x-waggies.button href="{{ route('book', $bookingParameters) }}" class="mt-5">Request a relocation quote <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    </div>
                @endif

                <div class="mt-10"><x-waggies.share-row :title="$guide['title']" :description="$guide['excerpt']" context-label="Share this page" /></div>
            </div>

            <x-waggies.article-toc :headings="$headings" />
        </div>
    </article>

@endsection
