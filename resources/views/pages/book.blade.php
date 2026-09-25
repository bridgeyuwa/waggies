@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'Book a service']]" class="border-b border-primary/5 bg-white" />

    <x-waggies.page-header
        eyebrow="BOOKING REQUEST"
        title-id="booking-page-title"
        title="Start with a request, then we will confirm the details"
        description="Share the essentials about your pet and the service you need. A Waggies team member will review your request and confirm the next steps with you."
        class="bg-surface-purple"
    />

    <section class="bg-surface py-12 md:py-20" aria-labelledby="booking-form-title">
        <div class="page-container grid grid-cols-1 items-start gap-8 lg:grid-cols-[minmax(0,1fr)_20rem] lg:gap-12">
            <div class="order-2 rounded-2xl border border-primary/10 bg-white p-5 shadow-sm sm:p-8 lg:order-1">
                @if($bookingSubmitted)
                    <div class="flex flex-col gap-5" role="status" tabindex="-1">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-light text-success">
                            <x-waggies.icon name="check-circle" variant="filled" size="28" />
                        </div>
                        <div>
                            <h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark">Your request was received</h2>
                            <p class="mt-3 max-w-xl text-sm leading-relaxed text-primary-dark/70">Our team will review your service, date, and pet details, then contact you to confirm the arrangements. Your requested time is not reserved until Waggies confirms it.</p>
                        </div>
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <x-waggies.button href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                                Continue on WhatsApp <x-waggies.icon name="arrow-forward" size="16" />
                            </x-waggies.button>
                            <x-waggies.button href="{{ route('book') }}" variant="secondary" class="w-full sm:w-auto">Send another request</x-waggies.button>
                        </div>
                    </div>
                @else
                    @livewire('booking-request-wizard', ['initialContext' => $bookingContext])
                @endif
            </div>

            <aside class="order-1 flex flex-col gap-5 lg:order-2 lg:sticky lg:top-28" aria-label="What happens next">
                <div class="rounded-2xl bg-primary-dark p-6 text-white shadow-sm sm:p-7">
                    <p class="text-eyebrow mb-3 text-secondary">WHAT HAPPENS NEXT</p>
                    <ol class="flex flex-col gap-5">
                        <li class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-bold text-primary-dark">1</span><span class="pt-1 text-sm leading-relaxed text-white/80">We receive and review your request.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-bold text-primary-dark">2</span><span class="pt-1 text-sm leading-relaxed text-white/80">Our team checks the details and gets in touch.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-secondary text-sm font-bold text-primary-dark">3</span><span class="pt-1 text-sm leading-relaxed text-white/80">We confirm the service arrangements with you.</span></li>
                    </ol>
                </div>
                <div class="rounded-2xl border border-primary/10 bg-white p-6 sm:p-7">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-surface-purple text-primary"><x-waggies.brand-icon name="whatsapp" size="20" /></div>
                    <h2 class="font-serif text-xl font-bold text-primary-dark">Prefer to talk now?</h2>
                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">After sending your request, you can continue the conversation on WhatsApp. Your request is saved before you leave this page.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark">Open WhatsApp <x-waggies.icon name="arrow-forward" size="16" /></a>
                </div>
            </aside>
        </div>
    </section>
@endsection
