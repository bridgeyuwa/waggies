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
                </div>

                <div class="relative aspect-video rounded-2xl overflow-hidden mb-10">
                    <img src="{{ $guide['image'] }}" @if(filled($guide['imageSrcset'] ?? null)) srcset="{{ $guide['imageSrcset'] }}" sizes="(min-width: 1024px) 800px, 100vw" @endif alt="{{ $guide['imageAlt'] }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
                </div>

                <x-waggies.article-toc :headings="$headings" mobile />

                <div class="prose max-w-none">{!! $processedContent !!}</div>

                <div class="mt-10"><x-waggies.share-row :title="$guide['title']" :description="$guide['excerpt']" context-label="Share this page" /></div>
            </div>

            <x-waggies.article-toc :headings="$headings" />
        </div>
    </article>

@endsection
