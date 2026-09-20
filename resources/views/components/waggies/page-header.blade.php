@props(['eyebrow' => null, 'title', 'subtitle' => null, 'align' => 'left'])

<section class="w-page-intro bg-white border-b border-primary/10">
    <div class="w-page-intro__content page-container py-16 md:py-20 flex flex-col">
        <div class="flex flex-col {{ $align === 'center' ? 'text-center items-center' : 'text-left items-start' }}">
            @if($eyebrow)<span class="text-eyebrow block mb-4">{{ $eyebrow }}</span>@endif
            <h1 class="text-display text-primary-dark mb-3">{!! $title !!}</h1>
            @if($subtitle)<p class="text-body max-w-xl text-pretty {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
        </div>
    </div>
</section>
