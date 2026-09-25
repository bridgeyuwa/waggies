@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Testimonials']]" class="bg-white border-b border-primary/5" />
    <x-waggies.cover-hero :hero="$hero" />
    <x-waggies.testimonials-grid :items="$items" :filters="$filters" />
    <section class="py-20 bg-surface-purple"><div class="max-w-3xl mx-auto px-4 md:px-10 lg:px-12"><div class="text-center mb-10"><h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-3">Share Your Experience</h2><p class="text-primary-dark/60 max-w-xl mx-auto">Help other pet owners discover Waggies.</p></div><x-waggies.testimonial-form /></div></section>
    @php($testimonialsCta = ['heading' => $bottomCta['heading'], 'body' => $bottomCta['body'], 'primaryLabel' => $bottomCta['ctaLabel'], 'primaryRoute' => $bottomCta['ctaRoute'], 'primaryParams' => $bottomCta['ctaParams'] ?? []])
    <section class="bg-primary-dark py-20"><div class="mx-auto max-w-3xl px-4"><x-waggies.cta-centered :cta="$testimonialsCta" /></div></section>

@endsection
