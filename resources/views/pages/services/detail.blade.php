@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => $page['title'], 'route' => 'services.vet-care']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.cover-hero :hero="['eyebrow' => $page['eyebrow'], 'title' => $page['title'], 'description' => $page['hero']['description'], 'imageSrc' => $page['hero']['imageSrc'], 'imageAlt' => $page['hero']['imageAlt'], 'actions' => [['label' => $page['ctaText'], 'url' => route($page['ctaRoute'], $page['ctaParams'] ?? []), 'icon' => 'arrow-forward']]]" size="compact" />
    <x-waggies.service-detail :page="$page" :faqs="$faqs" />
@endsection
