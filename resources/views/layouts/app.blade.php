@props([
    'title'       => null,
    'description' => null,
    'navSection'  => '',
    'ogImage'     => null,
    'canonical'   => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ $title ? $title . ' — Waggies' : 'Waggies — Luxury Pet Care, Abuja' }}</title>
    <meta name="description" content="{{ $description ?? 'Waggies is Abuja\'s most trusted luxury pet boarding, grooming, vet care and relocation specialists.' }}" />

    @if($canonical)
        <link rel="canonical" href="{{ $canonical }}" />
    @endif

    {{-- Open Graph --}}
    <meta property="og:type"        content="website" />
    <meta property="og:title"       content="{{ $title ? $title . ' — Waggies' : 'Waggies — Luxury Pet Care, Abuja' }}" />
    <meta property="og:description" content="{{ $description ?? 'Waggies is Abuja\'s most trusted luxury pet care specialist.' }}" />
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}" />
    @endif

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="bg-surface text-primary-dark antialiased" data-nav-section="{{ $navSection }}">

    <x-skip-link />

    <x-navbar :nav-section="$navSection" />

    <main id="main-content" tabindex="-1">
        {{ $slot }}
    </main>

    <x-footer />

    <x-whatsapp-fab />

    <x-cookie-banner />

    @stack('scripts')

</body>
</html>
