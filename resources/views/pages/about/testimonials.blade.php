@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Testimonials']]" class="bg-white border-b border-primary/5" />
    <x-waggies.cover-hero :hero="['eyebrow' => $hero['eyebrow'], 'eyebrowIcon' => $hero['eyebrowIcon'], 'title' => $hero['title'], 'subtitle' => $hero['subtitle'], 'imageSrc' => $hero['imageSrc'], 'imageAlt' => $hero['imageAlt'], 'primaryCta' => $hero['primaryCta'], 'secondaryCta' => $hero['secondaryCta']]" :trust-slot="null" />
    <x-waggies.testimonials-grid :items="$items" :filters="$filters" />
    <section class="py-20 bg-surface-purple"><div class="max-w-3xl mx-auto px-4 md:px-10 lg:px-12"><div class="text-center mb-10"><h2 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-3">Share Your Experience</h2><p class="text-primary-dark/60 max-w-xl mx-auto">Help other pet owners discover Waggies.</p></div><x-waggies.testimonial-form /></div></section>
    <section class="py-20 bg-primary-dark"><div class="max-w-3xl mx-auto px-4 text-center"><h2 class="font-serif text-3xl font-bold text-white mb-3">{{ $bottomCta['heading'] }}</h2><p class="text-white/70 mb-6">{{ $bottomCta['body'] }}</p><a href="{{ route($bottomCta['ctaRoute'], $bottomCta['ctaParams'] ?? []) }}" class="w-cta w-cta--primary !bg-secondary !text-primary-dark hover:!bg-secondary-hover">{{ $bottomCta['ctaLabel'] }} <x-waggies.icon name="arrow-forward" size="18" /></a></div></section>

@endsection
