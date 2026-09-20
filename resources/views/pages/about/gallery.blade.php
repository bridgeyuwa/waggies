@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Gallery']]" class="bg-white border-b border-primary/5" />
    <x-waggies.page-header :eyebrow="$hero['eyebrow']" :title="$hero['title']" :subtitle="$hero['subtitle']" align="center" />
    <section class="py-16 md:py-20 bg-white"><div class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12"><x-waggies.gallery-lightbox :images="$images" :categories="$categories" /></div></section>

@endsection
