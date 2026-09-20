@props(['tier', 'unit' => '/night', 'serviceKey', 'variantKey' => null, 'tierKey'])
@php
    $price = $tier['type'] === 'quote' ? 'Quote' : ($tier['type'] === 'estimate' ? '₦'.number_format($tier['amount']).' - ₦'.number_format($tier['max_amount'] ?? $tier['amount']) : '₦'.number_format($tier['amount']));
    $href = route('services.pricing', array_filter(['service' => $serviceKey, 'variant' => $variantKey, 'tier' => $tierKey], fn ($value) => $value !== null && $value !== ''));
@endphp
<x-waggies.card hover class="relative flex h-full flex-col border {{ !empty($tier['featured']) ? 'border-2 border-primary shadow-soft' : 'border-surface-purple' }} bg-white p-8">
    @if(!empty($tier['featured']))<div class="absolute -top-4 left-1/2 -translate-x-1/2"><span class="whitespace-nowrap rounded-full bg-primary px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-white shadow-sm">{{ $tier['badge'] ?? 'Most Popular' }}</span></div>@endif
    <p class="mb-3 text-xs font-bold uppercase tracking-widest text-primary/60">{{ $tier['label'] }}</p>
    <div class="mb-6 flex items-baseline gap-1"><span class="font-serif text-3xl font-bold text-primary-dark">{{ $price }}</span><span class="text-base text-primary-dark/60">{{ $unit }}</span></div>
    @if(!empty($tier['duration']))<p class="-mt-4 mb-5 text-xs text-primary-dark/60">{{ $tier['duration'] }}</p>@endif
    <ul class="mb-8 flex flex-col gap-3">@foreach($tier['features'] as $feature)@php($included = is_array($feature) ? ($feature['included'] ?? true) : true)@php($label = is_array($feature) ? $feature['label'] : $feature)<li class="flex items-start gap-3 text-sm {{ $included ? 'text-primary-dark' : 'text-primary-dark/35' }}"><x-waggies.icon name="{{ $included ? 'check' : 'remove' }}" size="16" class="mt-0.5 shrink-0 {{ $included ? 'text-primary' : 'text-primary-dark/30' }}" />{{ $label }}</li>@endforeach</ul>
    <x-waggies.button href="{{ $href }}" variant="{{ !empty($tier['featured']) ? 'primary' : 'secondary' }}" class="mt-auto w-full justify-center">Get Estimate @if(!empty($tier['featured']))<x-waggies.icon name="arrow-forward" size="16" />@endif</x-waggies.button>
</x-waggies.card>
