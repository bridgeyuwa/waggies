{{--
    Breadcrumb strip — constrained page width, placed above heroes or page headers.

    Props:
      hiddenOnHome — default true; set false to force render on home (rare)
--}}
@props([
    'hiddenOnHome' => true,
])

@if (! ($hiddenOnHome && request()->routeIs('home')))
    <div {{ $attributes->class(['max-w-7xl mx-auto px-4 md:px-10 lg:px-12 py-4']) }}>
        <x-breadcrumb />
    </div>
@endif
