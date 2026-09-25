@extends('layouts.app')

@section('content')
    <div data-print-hide>
        <x-waggies.breadcrumb-strip :items="[['label' => 'Tools', 'route' => 'tools.index'], ['label' => 'New Pet Checklist']]" class="border-b border-primary/5 bg-white" />
        <x-waggies.page-header alignment="center" eyebrow="Pet Care Tool" eyebrow-icon="checklist" title="New Pet Checklist" description="An interactive checklist of everything you need before and after bringing a new pet home." />
    </div>

    <section class="bg-surface pb-20 md:pb-28">
        <div x-data="newPetChecklist(@js($checklist))" class="mx-auto max-w-2xl px-4 md:px-10" data-checklist-artifact>
            <header class="print-only mb-6">
                <h1 class="font-serif text-3xl font-bold text-primary-dark">New Pet Checklist</h1>
                <p class="mt-1 text-sm text-primary-dark/65">A practical preparation and settling-in checklist for a new companion.</p>
            </header>

            <div class="w-card p-5 sm:p-6">
                <div class="mb-3 flex items-center justify-between">
                    <h3 class="font-serif text-base font-bold text-primary-dark">Your Progress</h3>
                    <span class="text-sm font-semibold text-primary"><span x-text="completed()"></span> of <span x-text="total()"></span> completed</span>
                </div>
                <div class="h-2.5 w-full overflow-hidden rounded-full bg-primary/10">
                    <div class="h-full rounded-full bg-primary transition-[width] duration-300" :style="`width: ${percentage()}%`" role="progressbar" :aria-valuenow="completed()" aria-valuemin="0" :aria-valuemax="total()" :aria-label="`${completed()} of ${total()} items completed`"></div>
                </div>
                <p class="mt-2 text-xs text-primary-dark/60">Progress is saved in your browser&apos;s local storage.</p>
                <div class="mt-5 print-hidden" data-print-hide><x-waggies.button type="button" variant="secondary" @click="window.print()" aria-label="Print this checklist"><x-waggies.icon name="print" size="18" aria-hidden="true" />Print checklist</x-waggies.button></div>
            </div>

            @foreach($checklist as $category)
                <div class="w-card mt-8 p-5 sm:p-6 print-break-inside-avoid" data-print-break>
                    <div class="mb-4 flex items-center gap-3">
                        @php
                            $categoryIcon = str_contains($category['title'], 'Home') ? 'home' : (str_contains($category['title'], 'Week') ? 'pets' : (str_contains($category['title'], 'Health') ? 'veterinary-care' : (str_contains($category['title'], 'Training') ? 'training' : 'supplies')));
                        @endphp
                        <x-waggies.icon name="{{ $categoryIcon }}" size="22" class="text-primary" aria-hidden="true" />
                        <h3 class="font-serif text-base font-bold text-primary-dark">{{ $category['title'] }}</h3>
                    </div>
                    <ul class="space-y-3">
                        @foreach($category['items'] as $item)
                            <li>
                                <label class="group flex cursor-pointer items-start gap-3">
                                    <span class="mt-0.5 shrink-0 text-primary" aria-hidden="true"><span x-show="checked['{{ $item['id'] }}']"><x-waggies.icon name="checklist" size="20" variant="filled" /></span><span x-show="!checked['{{ $item['id'] }}']"><x-waggies.icon name="pets" size="20" /></span></span>
                                    <input type="checkbox" :checked="Boolean(checked['{{ $item['id'] }}'])" @change="toggle('{{ $item['id'] }}')" class="sr-only" />
                                    <span class="text-sm leading-relaxed transition" :class="checked['{{ $item['id'] }}'] ? 'text-primary-dark/60 line-through' : 'text-primary-dark/70 group-hover:text-primary-dark'">{{ $item['label'] }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <x-waggies.tool-cta medical tool-route="tools.new-pet-checklist" />
        </div>
    </section>

@endsection
