{{--
    Breadcrumb navigation (B6) — diglactic/laravel-breadcrumbs + Waggies styling.

    Route-bound (preferred): omit props on named routes; definitions live in routes/breadcrumbs.php.
        <x-breadcrumb />

    Explicit render:
        <x-breadcrumb name="blog.show" :parameters="[$post->slug]" />

    Legacy manual crumbs (discouraged):
        <x-breadcrumb :crumbs="[['label' => 'Services', 'href' => route('services.index')], ...]" />
--}}
@props([
    'name' => null,
    'parameters' => [],
    'crumbs' => null,
])

@php
    use Diglactic\Breadcrumbs\Breadcrumbs as BreadcrumbTrail;
    use Diglactic\Breadcrumbs\Exceptions\InvalidBreadcrumbException;
    use Diglactic\Breadcrumbs\Exceptions\UnnamedRouteException;

    $breadcrumbs = collect();

    if (is_array($crumbs) && $crumbs !== []) {
        $breadcrumbs = collect($crumbs)->map(fn (array $crumb): object => (object) [
            'title' => $crumb['label'],
            'url' => $crumb['href'] ?? null,
        ]);
    } elseif (! request()->routeIs('home')) {
        try {
            $breadcrumbs = $name
                ? BreadcrumbTrail::generate($name, ...$parameters)
                : BreadcrumbTrail::generate();
        } catch (InvalidBreadcrumbException|UnnamedRouteException) {
            $breadcrumbs = collect();
        }
    }
@endphp

@include('vendor.breadcrumbs.waggies', ['breadcrumbs' => $breadcrumbs])
