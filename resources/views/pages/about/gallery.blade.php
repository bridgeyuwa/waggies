@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Gallery']]" class="bg-white border-b border-primary/5" />
    <x-waggies.page-header :eyebrow="$hero['eyebrow']" :title="$hero['title']" :description="$hero['description']" alignment="center" />
    <section class="py-16 md:py-20 bg-white"><div class="page-container"><x-waggies.gallery-lightbox :images="$images" :categories="$categories" /></div></section>

@endsection
