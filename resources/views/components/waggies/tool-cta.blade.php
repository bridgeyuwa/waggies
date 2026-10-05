@props(['medical' => false, 'toolRoute' => null])
@if($toolRoute)
    <x-waggies.related-tools :current-route="$toolRoute" />
@endif
<section class="relative left-1/2 mt-12 w-screen max-w-[100vw] -translate-x-1/2 overflow-hidden bg-primary-dark py-12 sm:py-14">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="text-center font-serif text-xl font-bold text-white sm:text-2xl">{{ $medical ? 'Need Professional Help?' : 'Ready to Get Started?' }}</h2>
        <p class="mx-auto mt-2 max-w-lg text-center text-sm leading-relaxed text-white/70 sm:text-base">{{ $medical ? 'Our veterinary team is available for consultations. Request a consultation or call us directly.' : 'Speak with our care team about your pet’s needs or request a service today.' }}</p>
        @php
            $actions = [
                [
                    'href' => $medical
                        ? route('book', ['service' => 'vet-care', 'source' => 'tool-medical-cta'])
                        : route('contact', ['intent' => 'tool-assistance', 'source' => 'tool-general-cta']),
                    'label' => $medical ? 'Book a Vet Appointment' : 'Contact Waggies',
                    'tone' => 'dark',
                    'iconAfter' => 'arrow-forward',
                ],
            ];

            if ($medical) {
                $actions[] = [
                    'href' => $businessProfile->toPublicArray()['phoneHref'],
                    'label' => 'Call '.$businessProfile->phone,
                    'variant' => 'secondary',
                    'tone' => 'dark',
                    'iconBefore' => 'phone',
                ];
            }
        @endphp
        <x-waggies.cta-actions :actions="$actions" class="mx-auto mt-6 max-w-md" />
    </div>
</section>
