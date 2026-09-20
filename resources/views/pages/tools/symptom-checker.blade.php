@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Tools', 'route' => 'tools.index'], ['label' => 'Symptom Checker']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.tool-hero eyebrow="Pet Care Tool" title="Pet Symptom Checker" subtitle="Select your pet type, choose the affected area, and pick the symptoms you notice to get general guidance." />

    <section class="bg-surface pb-20 md:pb-28">
        <div x-data="symptomChecker(@js($symptomChecker))" class="mx-auto max-w-3xl px-4 md:px-10">
            <div class="flex flex-col gap-8">
                <div class="rounded-2xl border border-primary/10 bg-white p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">1</span>
                        <h3 class="font-serif text-lg font-bold text-primary-dark">Select your pet type</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($symptomChecker['pet_types'] as $pet)
                            <button type="button" @click="selectPetType('{{ $pet['id'] }}')" class="flex flex-col items-center gap-2 rounded-xl border-2 p-4 transition-colors" :class="petType === '{{ $pet['id'] }}' ? 'border-primary bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/70 hover:border-primary/30'">
                                <x-waggies.icon name="{{ $pet['icon'] }}" size="28" class="" />
                                <span class="text-sm font-semibold">{{ $pet['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div x-show="petType" x-cloak class="rounded-2xl border border-primary/10 bg-white p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">2</span>
                        <h3 class="font-serif text-lg font-bold text-primary-dark">Select the body area</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
                        @foreach($symptomChecker['body_areas'] as $area)
                            <button type="button" @click="selectArea('{{ $area['id'] }}')" class="flex flex-col items-center gap-1.5 rounded-xl border p-3 transition-colors" :class="selectedArea === '{{ $area['id'] }}' ? 'border-primary bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/60 hover:border-primary/30'">
                                <x-waggies.icon name="{{ $area['icon'] }}" size="22" />
                                <span class="text-center text-xs font-medium leading-tight">{{ $area['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <div x-show="selectedArea" x-cloak class="rounded-2xl border border-primary/10 bg-white p-6">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary text-sm font-bold text-white">3</span>
                        <h3 class="font-serif text-lg font-bold text-primary-dark">Select the symptoms you notice</h3>
                    </div>
                    @foreach($symptomChecker['body_areas'] as $area)
                        <div x-show="selectedArea === '{{ $area['id'] }}'" x-cloak class="flex flex-wrap gap-2">
                            @foreach($symptomChecker['symptoms'][$area['id']] as $symptom)
                                <button type="button" @click="toggleSymptom('{{ $symptom['id'] }}')" :aria-pressed="selectedSymptoms.includes('{{ $symptom['id'] }}')" class="min-h-[44px] rounded-full border px-4 py-2 text-sm font-medium transition-colors" :class="selectedSymptoms.includes('{{ $symptom['id'] }}') ? 'border-primary/40 bg-surface-purple text-primary' : 'border-primary/10 bg-white text-primary-dark/70 hover:border-primary/30 hover:bg-surface-purple/50'">{{ $symptom['label'] }}</button>
                            @endforeach
                        </div>
                    @endforeach

                    <div x-show="selectedSymptoms.length > 0" x-cloak class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                        <button type="button" @click="checkSymptoms()" class="w-cta w-cta--primary w-full gap-2 sm:w-auto"><x-waggies.icon name="search" size="20" />Check Symptoms (<span x-text="selectedSymptoms.length"></span> selected)</button>
                        <button type="button" @click="clearSelection()" class="inline-flex min-h-[44px] items-center text-sm font-semibold text-primary hover:text-primary-dark">Clear selection</button>
                    </div>
                </div>

                <div x-show="guidance" x-cloak class="rounded-2xl border p-6 md:p-8" :class="guidance?.severity === 'low' ? 'border-green-200 bg-green-50' : guidance?.severity === 'moderate' ? 'border-amber-200 bg-amber-50' : 'border-red-200 bg-red-50'">
                    <div class="mb-6 flex items-start gap-4">
                        <span x-show="guidance?.severity === 'low'" class="mt-0.5 shrink-0 text-green-700"><x-waggies.icon name="check-circle" size="28" variant="filled" /></span>
                        <span x-show="guidance?.severity === 'moderate'" class="mt-0.5 shrink-0 text-amber-700"><x-waggies.icon name="warning" size="28" variant="filled" /></span>
                        <span x-show="guidance?.severity === 'high'" class="mt-0.5 shrink-0 text-red-700"><x-waggies.icon name="emergency" size="28" variant="filled" /></span>
                        <div>
                            <span class="mb-2 inline-block rounded-full px-2.5 py-1 text-xs font-bold uppercase tracking-wider" :class="guidance?.severity === 'low' ? 'bg-green-100 text-green-800' : guidance?.severity === 'moderate' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800'" x-text="guidance?.severity === 'low' ? 'Low Concern' : guidance?.severity === 'moderate' ? 'Moderate Concern' : 'High Concern'"></span>
                            <h3 class="font-serif text-xl font-bold text-primary-dark" x-text="guidance?.title"></h3>
                        </div>
                    </div>
                    <p class="mb-6 leading-relaxed text-primary-dark/70" x-text="guidance?.description"></p>
                    <div class="mb-6">
                        <h4 class="mb-3 text-sm font-bold uppercase tracking-wider text-primary-dark/50">Recommendations</h4>
                        <ul class="space-y-2"><template x-for="recommendation in guidance?.recommendations ?? []" :key="recommendation"><li class="flex items-start gap-3"><x-waggies.icon name="check" size="18" class="mt-0.5 shrink-0 text-primary" /><span class="text-sm text-primary-dark/70" x-text="recommendation"></span></li></template></ul>
                    </div>
                    <div class="flex items-center gap-3 border-t border-primary/10 pt-4"><button type="button" @click="startOver()" class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition-colors hover:text-primary-dark"><x-waggies.icon name="refresh" size="18" />Start over</button></div>
                    <div class="mt-4 flex items-start gap-2.5 rounded-xl bg-white/80 p-4"><x-waggies.icon name="info" size="18" class="mt-0.5 shrink-0 text-primary" /><p class="text-xs leading-relaxed text-primary-dark/50">This is an informational aid only and does not constitute veterinary diagnosis. Please consult a veterinarian for medical advice.</p></div>
                </div>
            </div>

            <x-waggies.tool-cta medical tool-route="tools.symptom-checker" />
            <div class="mt-10"><x-waggies.medical-disclaimer /></div>
        </div>
    </section>

@endsection
