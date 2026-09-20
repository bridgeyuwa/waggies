@extends('layouts.app')

@section('content')
<x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Relocation', 'route' => 'services.relocation'], ['label' => 'Local Transport', 'route' => 'relocation.transport']]" class="border-b border-primary/5 bg-white" />
<x-waggies.cover-hero :hero="array_merge($page['hero'], ['eyebrow' => 'Local Transport · Relocation', 'eyebrowIcon' => 'transport', 'title' => 'Local Transport', 'description' => 'Air-conditioned, purpose-fitted vehicles for pet pickup and drop-off anywhere across Abuja. Part of Waggies relocation services.', 'actions' => [['label' => 'Request Transport', 'route' => 'contact', 'params' => ['intent' => 'transport', 'service' => 'local-transport']]]])" />
<x-waggies.service-detail :page="$page" :faqs="$faqs" pricing-variant="quote" />

<section class="border-t border-primary/5 bg-surface py-20"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><div class="mx-auto mb-14 max-w-xl text-center"><span class="text-eyebrow mb-2 block">TRANSIT STANDARDS</span><h2 class="text-h2 text-primary-dark">Safe &amp; Climate-Controlled Pet Taxi</h2><p class="text-body-sm text-primary-dark/70">Purpose-built vehicles and trained handlers for stress-free travel across Abuja.</p></div><div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">@foreach($page['safetyStandards'] as $standard)<div class="w-card flex flex-col justify-between rounded-2xl border border-primary/10 bg-white p-6"><div><div class="mb-4 flex size-11 items-center justify-center rounded-xl bg-surface-purple text-primary"><x-waggies.icon name="{{ $standard['icon'] }}" size="24" /></div><h3 class="mb-2 font-serif text-lg font-bold text-primary-dark">{{ $standard['title'] }}</h3><p class="text-xs leading-relaxed text-primary-dark/65">{{ $standard['desc'] }}</p></div></div>@endforeach</div></div></section>

<section class="border-t border-primary/5 bg-white py-20"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><div class="max-w-2xl"><span class="text-eyebrow">How it works</span><h2 class="mt-2 text-h2 text-primary-dark">A simple handoff from door to door</h2><p class="mt-3 text-body-sm">Local transport is quote-led because route, timing, and pet needs change each journey.</p></div><x-waggies.process-steps :steps="$page['processSteps']" class="mt-10" /></div></section>

<x-waggies.share-row title="Local Transport - Waggies" description="Air-conditioned, door-to-door pet transport and pickup across Abuja. Part of Waggies relocation services." />
@endsection
