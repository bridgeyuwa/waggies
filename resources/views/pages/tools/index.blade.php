@extends('layouts.app')

@php
    $tools = config('waggies_tools.catalogue');
    $featured = $tools[0];
    $labels = ['health' => 'Health Tools', 'calculator' => 'Calculators', 'reference' => 'Reference', 'utility' => 'Utilities'];
    $grouped = collect($tools)->groupBy('category');
    $destination = fn (array $tool): string => route($tool['route']);
@endphp

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Tools']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.page-header alignment="center" eyebrow="Pet Care Tools" title="Useful Tools for Pet Owners" description="Free interactive tools and reference guides to help you take better care of your pets." />
    <section class="bg-surface pb-20 md:pb-28">
        <div class="mx-auto max-w-6xl px-4 md:px-10 lg:px-12">
            <x-waggies.card hover class="group mb-10 p-6 sm:p-8 md:p-10">
                <a href="{{ $destination($featured) }}" class="block">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-surface-purple text-primary transition-colors group-hover:bg-primary group-hover:text-white sm:h-16 sm:w-16"><x-waggies.icon name="{{ $featured['icon'] }}" size="28" class="sm:text-[32px]" /></div>
                    <div class="min-w-0 flex-1"><span class="mb-1 inline-block text-[10px] font-semibold uppercase tracking-[0.14em] text-primary/60">Featured Tool</span><h2 class="font-serif text-xl font-bold text-primary-dark transition-colors group-hover:text-primary sm:text-2xl">{{ $featured['name'] }}</h2><p class="mt-1 text-sm leading-relaxed text-primary-dark/50 sm:text-base">{{ $featured['longDescription'] }}</p></div>
                    <div class="shrink-0 self-start sm:self-center"><span class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary transition-[gap] group-hover:gap-2.5">Try it out <x-waggies.icon name="arrow-forward" size="16" /></span></div>
                    </div>
                </a>
            </x-waggies.card>
            @foreach(['health', 'calculator', 'reference', 'utility'] as $category)
                @php($items = $grouped->get($category, collect())->reject(fn ($tool) => $tool['id'] === $featured['id'])->values())
                @if($items->isNotEmpty())
                    <div class="mb-12 last:mb-0"><h3 class="text-eyebrow mb-5 text-primary-dark/40">{{ $labels[$category] }}</h3><div class="grid grid-cols-1 gap-4 {{ $items->count() <= 2 ? 'sm:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3' }}">
                        @foreach($items as $tool)
                            <x-waggies.card hover class="group h-full p-5"><a href="{{ $destination($tool) }}" class="block h-full"><div class="flex items-start gap-4"><div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-surface-purple text-primary transition-colors group-hover:bg-primary group-hover:text-white"><x-waggies.icon name="{{ $tool['icon'] }}" size="20" /></div><div class="min-w-0 flex-1"><h4 class="truncate font-serif text-base font-bold text-primary-dark transition-colors group-hover:text-primary">{{ $tool['name'] }}</h4><p class="mt-1 line-clamp-2 text-sm leading-relaxed text-primary-dark/50">{{ $tool['description'] }}</p><span class="mt-3 inline-block rounded-full bg-surface-purple px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wider text-primary">{{ $labels[$category] }}</span></div></div></a></x-waggies.card>
                        @endforeach
                    </div></div>
                @endif
            @endforeach
            <div class="mt-12"><x-waggies.medical-disclaimer /></div>
            <div class="mt-16 rounded-2xl bg-primary-dark p-8 text-center sm:p-10"><h2 class="font-serif text-xl font-bold text-white sm:text-2xl">Need Professional Advice?</h2><p class="mx-auto mt-2 max-w-lg text-sm text-white/70 sm:text-base">Our veterinary team is available for consultations. Send a request or call us directly.</p><div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row"><x-waggies.button href="{{ route('contact') }}?intent=veterinary&amp;service=vet-care" class="!bg-secondary !text-primary-dark hover:!bg-secondary-hover">Request Vet Care</x-waggies.button><x-waggies.button href="tel:{{ preg_replace('/\s+/', '', config('waggies.phone_international')) }}" variant="secondary" class="border-white/30 bg-transparent text-white hover:bg-white/10">Call {{ config('waggies.phone') }}</x-waggies.button></div></div>
        </div>
    </section>
@endsection
