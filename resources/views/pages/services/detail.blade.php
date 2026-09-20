@extends('layouts.app')
@section('content')
@php($breadcrumbLabel = ['grooming' => 'Grooming', 'training' => 'Dog Training', 'vet-care' => 'Veterinary Care'][$service] ?? $page['title'])
<x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => $breadcrumbLabel, 'route' => 'services.'.($service === 'vet-care' ? 'vet-care' : $service)]]" class="border-b border-primary/5 bg-white" />
<x-waggies.cover-hero :hero="['eyebrow' => $page['eyebrow'], 'title' => $page['title'], 'description' => $page['hero']['description'], 'imageSrc' => $page['hero']['imageSrc'], 'imageAlt' => $page['hero']['imageAlt'], 'actions' => [['label' => $page['ctaText'], 'route' => $page['ctaRoute'], 'params' => $page['ctaParams'] ?? []]]]" size="compact" />
<x-waggies.service-detail :page="$page" :faqs="$faqs" />
<x-waggies.service-standards :standards="$page['standards']" />
<x-waggies.share-row :title="$page['shareTitle']" :description="$page['shareDescription']" />
@endsection
