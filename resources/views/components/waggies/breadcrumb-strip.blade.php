@props(['items' => []])
@php
    $breadcrumbs = \Diglactic\Breadcrumbs\Breadcrumbs::generate('waggies-path', $items);
    $schemaItems = $items;

    if ($schemaItems !== []) {
        $lastSchemaItem = array_key_last($schemaItems);

        if ($lastSchemaItem !== null
            && empty($schemaItems[$lastSchemaItem]['href'])
            && empty($schemaItems[$lastSchemaItem]['route'])) {
            $schemaItems[$lastSchemaItem]['href'] = url()->current();
        }
    }
@endphp
@push('head')
    {!! \Diglactic\Breadcrumbs\Breadcrumbs::view('breadcrumbs::json-ld', 'waggies-path', $schemaItems)->render() !!}
@endpush
<nav aria-label="Breadcrumb">
    <div {{ $attributes->merge(['class' => 'page-container py-4']) }}>
        <ol class="flex flex-wrap items-center gap-2 text-xs font-medium text-primary-dark/50">
            @foreach($breadcrumbs as $breadcrumb)
                <li class="flex items-center gap-2"><x-waggies.icon name="chevron-right" size="12" class="text-primary-dark/30" />@if($breadcrumb->url && ! $loop->last)<a href="{{ $breadcrumb->url }}" class="transition-colors hover:text-primary">{{ $breadcrumb->title }}</a>@else<span class="font-semibold text-primary-dark" @if($loop->last) aria-current="page" @endif>{{ $breadcrumb->title }}</span>@endif</li>
            @endforeach
        </ol>
    </div>
</nav>
