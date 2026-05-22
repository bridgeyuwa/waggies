@props(['content' => ''])

<div {{ $attributes->merge(['class' => 'prose max-w-none']) }}>
    {!! \Illuminate\Support\Str::markdown($content ?? '', [
        'html_input' => 'strip',
        'allow_unsafe_links' => false,
    ]) !!}
</div>
