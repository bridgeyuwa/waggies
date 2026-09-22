@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Knowledge Base', 'route' => 'knowledge-base.index'], ['label' => $article['title']]]" class="bg-white border-b border-primary/5" />

    <article class="page-container py-10 md:py-16">
        <div class="lg:grid lg:grid-cols-[1fr_240px] lg:gap-12">
            <div class="max-w-3xl">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-primary px-3 py-1 text-label text-white"><x-waggies.icon name="training" size="16" />Knowledge Base</span>
                        <span class="inline-flex items-center rounded-full bg-surface-purple px-3 py-1 text-label text-primary">{{ $article['category'] }}</span>
                    </div>

                    <h1 class="font-serif text-2xl md:text-3xl lg:text-4xl font-bold text-primary-dark leading-tight mb-6">{{ $article['title'] }}</h1>

                    @if(!empty($article['author']) || !empty($article['date']) || !empty($article['readTime']))
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-meta mb-8 pb-8 border-b border-border-subtle">
                            @if(!empty($article['author']))<span class="flex items-center gap-1.5"><x-waggies.icon name="team-member" size="16" />{{ $article['author'] }}</span>@endif
                            @if(!empty($article['date']))<span class="flex items-center gap-1.5"><x-waggies.icon name="calendar" size="16" />{{ date('j F Y', strtotime($article['date'])) }}</span>@endif
                            @if(!empty($article['readTime']))<span class="flex items-center gap-1.5"><x-waggies.icon name="hours" size="16" />{{ $article['readTime'] }}</span>@endif
                        </div>
                    @endif
                </div>

                <div class="relative aspect-[16/9] rounded-2xl overflow-hidden mb-10">
                    <img src="{{ $article['image'] }}" @if(filled($article['imageSrcset'] ?? null)) srcset="{{ $article['imageSrcset'] }}" sizes="(min-width: 1024px) 800px, 100vw" @endif alt="{{ $article['imageAlt'] }}" class="absolute inset-0 h-full w-full object-cover" fetchpriority="high">
                </div>

                <x-waggies.article-toc :headings="$headings" mobile />
                <div class="prose max-w-none">{!! $processedContent !!}</div>
                <div class="mt-10"><x-waggies.share-row :title="$article['title']" :description="$article['excerpt']" context-label="Share this page" /></div>

                @if(count($related) > 0)
                    <section class="mt-16">
                        <h2 class="font-serif text-h3 font-bold text-primary-dark mb-8">Related Articles</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($related as $relatedArticle)
                                <x-waggies.article-card :guide="$relatedArticle" route-name="knowledge-base.show" show-meta />
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <x-waggies.article-toc :headings="$headings" />
        </div>
    </article>

@endsection
