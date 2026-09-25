@props(['items' => []])
@php
    $breadcrumbItems = $items;

    if ($breadcrumbItems !== []) {
        $lastBreadcrumbItem = array_key_last($breadcrumbItems);

        if ($lastBreadcrumbItem !== null
            && empty($breadcrumbItems[$lastBreadcrumbItem]['href'])
            && empty($breadcrumbItems[$lastBreadcrumbItem]['route'])) {
            $breadcrumbItems[$lastBreadcrumbItem]['href'] = url()->current();
        }
    }

    $breadcrumbs = \Diglactic\Breadcrumbs\Breadcrumbs::generate('waggies-path', $breadcrumbItems);

    if ($breadcrumbItems !== []) {
        app(\App\Support\WaggiesPageHead::class)->registerBreadcrumbs($breadcrumbs);
    }
@endphp
<nav aria-label="Breadcrumb">
    <div {{ $attributes->merge(['class' => 'page-container py-4']) }}>
        <ol class="flex flex-wrap items-center gap-2 text-xs font-medium text-primary-dark/50">
            @foreach($breadcrumbs as $breadcrumb)
                <li class="flex items-center gap-2"><x-waggies.icon name="chevron-right" size="12" class="text-primary-dark/30" />@if($breadcrumb->url && ! $loop->last)<a href="{{ $breadcrumb->url }}" class="transition-colors hover:text-primary">{{ $breadcrumb->title }}</a>@else<span class="font-semibold text-primary-dark" @if($loop->last) aria-current="page" @endif>{{ $breadcrumb->title }}</span>@endif</li>
            @endforeach
        </ol>
    </div>
</nav>
