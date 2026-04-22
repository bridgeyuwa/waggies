<x-layouts.utility title="Pet Relocation Checklist" nav-section="relocation">

    <x-section-heading
        eyebrow="Relocation Checklist"
        title="Your Pet Relocation<br/>Checklist"
        subtitle="Use this checklist to stay on top of every requirement. Start early — some steps can take weeks to complete."
        class="mb-12"
    />

    @foreach([
        [
            'heading' => '3+ Months Before Travel',
            'icon'    => 'event_upcoming',
            'items'   => [
                'Research the destination country\'s pet import requirements',
                'Confirm your pet is microchipped (ISO 11784/11785 standard)',
                'Check vaccination requirements — especially rabies',
                'Contact Waggies to begin the relocation process',
                'Book your travel and confirm airline pet policies',
            ],
        ],
        [
            'heading' => '6–8 Weeks Before Travel',
            'icon'    => 'assignment',
            'items'   => [
                'Apply for export permit from the Nigerian Federal Department of Livestock',
                'Book health examination with our on-site vet',
                'Order an IATA-approved travel crate (allow time for crate-training)',
                'Confirm destination country quarantine requirements',
                'Arrange travel insurance for your pet',
            ],
        ],
        [
            'heading' => '2–4 Weeks Before Travel',
            'icon'    => 'checklist',
            'items'   => [
                'Complete official veterinary health certificate (signed and endorsed)',
                'Confirm all vaccinations are within the required validity windows',
                'Confirm airline booking — cargo or cabin — with airline reference',
                'Prepare a comfort pack: familiar toy, blanket, and food for travel',
                'Check crate sizing meets airline and IATA requirements',
            ],
        ],
        [
            'heading' => 'Day of Travel',
            'icon'    => 'flight_takeoff',
            'items'   => [
                'Arrive at the airport early — cargo check-in takes longer than passenger',
                'Carry all original documents in your hand luggage',
                'Do not feed your pet within 4 hours of the flight (unless advised otherwise)',
                'Attach a "Live Animal" label and your contact details to the crate',
                'Confirm collection arrangements at the destination',
            ],
        ],
    ] as $phase)
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary icon-filled">{{ $phase['icon'] }}</span>
            </div>
            <h2 class="font-serif text-xl font-bold text-primary-dark">{{ $phase['heading'] }}</h2>
        </div>
        <ul class="flex flex-col gap-3 pl-12">
            @foreach($phase['items'] as $item)
            <li class="flex items-start gap-3 text-sm text-primary-dark/70">
                <span class="material-symbols-outlined icon-filled text-base text-primary/40 mt-0.5 shrink-0">check_box_outline_blank</span>
                {{ $item }}
            </li>
            @endforeach
        </ul>
    </div>
    @endforeach

    <div class="mt-12 p-6 bg-surface-purple rounded-2xl">
        <h3 class="font-serif text-lg font-bold text-primary-dark mb-2">Need help working through this?</h3>
        <p class="text-sm text-primary-dark/60 mb-4">Our relocation team manages every item on this list for you. Get in touch to start the process.</p>
        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold text-sm transition shadow-glow hover:-translate-y-1">
            Contact Us <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>


@push('head')
@php
echo \Spatie\SchemaOrg\Schema::webPage()
    ->name('Pet Relocation Checklist — Waggies')
    ->description('Comprehensive step-by-step checklist for relocating your pet internationally — from 3 months out to the day of travel.')
    ->url(url()->current())
    ->toScript();
@endphp
@endpush
</x-layouts.utility>
