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
                    <div class="mb-8">
                        <p class="text-eyebrow mb-2 text-primary">YOUR DETAILS</p>
                        <h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">What should we know?</h2>
                        <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">Required fields are marked with <span class="text-primary" aria-hidden="true">*</span>. We only use these details to respond to your request.</p>
                    </div>

                    <form action="{{ route('booking-requests.store') }}" method="post" class="flex flex-col gap-8" x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return; } submitting = true">
                        @csrf
                        <input type="hidden" name="service_variant" value="{{ old('service_variant', $selectedVariant) }}">
                        <input type="hidden" name="pricing_tier" value="{{ old('pricing_tier', $selectedTier) }}">
                        <input type="hidden" name="source" value="{{ old('source', $source) }}">
                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                            <x-waggies.field id="booking-name" label="Your name" :error="$errors->first('name')" required>
                                <input id="booking-name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" maxlength="120" required aria-describedby="booking-name-help{{ $errors->has('name') ? ' booking-name-error' : '' }}" @if($errors->has('name')) aria-invalid="true" @endif class="contact-input">
                                <p id="booking-name-help" class="text-xs text-primary-dark/50">The name our team should use when we reply.</p>
                            </x-waggies.field>
                            <x-waggies.field id="booking-email" label="Email address" :error="$errors->first('email')" required>
                                <input id="booking-email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" maxlength="255" required @if($errors->has('email')) aria-describedby="booking-email-error" aria-invalid="true" @endif class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-phone" label="Phone or WhatsApp number" :error="$errors->first('phone')" required>
                                <input id="booking-phone" name="phone" type="tel" value="{{ old('phone') }}" autocomplete="tel" maxlength="40" required @if($errors->has('phone')) aria-describedby="booking-phone-error" aria-invalid="true" @endif class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-service" label="Service needed" :error="$errors->first('service_key')" required>
                                <select id="booking-service" name="service_key" required @if($errors->has('service_key')) aria-describedby="booking-service-error" aria-invalid="true" @endif class="contact-input">
                                    <option value="">Choose a service</option>
                                    @foreach($serviceOptions as $key => $label)
                                        <option value="{{ $key }}" @selected(old('service_key', $selectedService) === $key)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </x-waggies.field>
                        </div>

                        <div class="border-t border-primary/10 pt-7">
                            <h3 class="mb-5 font-serif text-xl font-bold text-primary-dark">Request details</h3>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-waggies.field id="booking-date" label="Requested date" help="This is your preferred date, not a confirmed appointment." :error="$errors->first('requested_date')" required>
                                    <input id="booking-date" name="requested_date" type="date" value="{{ old('requested_date') }}" min="{{ $minimumDate }}" required aria-describedby="booking-date-help{{ $errors->has('requested_date') ? ' booking-date-error' : '' }}" @if($errors->has('requested_date')) aria-invalid="true" @endif class="contact-input">
                                </x-waggies.field>
                                <x-waggies.field id="booking-time" label="Requested time" help="Optional. Share a preferred time or leave it open." :error="$errors->first('requested_time')">
                                    <input id="booking-time" name="requested_time" type="time" value="{{ old('requested_time') }}" aria-describedby="booking-time-help{{ $errors->has('requested_time') ? ' booking-time-error' : '' }}" @if($errors->has('requested_time')) aria-invalid="true" @endif class="contact-input">
                                </x-waggies.field>
                                <x-waggies.field id="booking-pet-name" label="Pet name" :error="$errors->first('pet_name')" required>
                                    <input id="booking-pet-name" name="pet_name" type="text" value="{{ old('pet_name') }}" maxlength="80" required @if($errors->has('pet_name')) aria-describedby="booking-pet-name-error" aria-invalid="true" @endif class="contact-input">
                                </x-waggies.field>
                                <x-waggies.field id="booking-pet-type" label="Pet type" :error="$errors->first('pet_type')" required>
                                    <select id="booking-pet-type" name="pet_type" required @if($errors->has('pet_type')) aria-describedby="booking-pet-type-error" aria-invalid="true" @endif class="contact-input">
                                        <option value="">Choose a type</option>
                                        @foreach(['dog' => 'Dog', 'cat' => 'Cat', 'bird' => 'Bird', 'rabbit' => 'Rabbit', 'reptile' => 'Reptile', 'other' => 'Other'] as $key => $label)
                                            <option value="{{ $key }}" @selected(old('pet_type') === $key)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </x-waggies.field>
                                <x-waggies.field id="booking-location" label="Location" help="Useful for transport or relocation requests. Optional for other services." :error="$errors->first('location')">
                                    <input id="booking-location" name="location" type="text" value="{{ old('location') }}" autocomplete="street-address" maxlength="255" aria-describedby="booking-location-help{{ $errors->has('location') ? ' booking-location-error' : '' }}" @if($errors->has('location')) aria-invalid="true" @endif class="contact-input">
                                </x-waggies.field>
                                <x-waggies.field id="booking-message" label="Additional notes" help="Tell us anything that will help the team prepare." :error="$errors->first('message')">
                                    <textarea id="booking-message" name="message" rows="4" maxlength="2000" aria-describedby="booking-message-help{{ $errors->has('message') ? ' booking-message-error' : '' }}" @if($errors->has('message')) aria-invalid="true" @endif class="contact-input">{{ old('message') }}</textarea>
                                </x-waggies.field>
                            </div>
                        </div>

                        <div class="absolute left-[-9999px] h-px w-px overflow-hidden" aria-hidden="true">
                            <label for="booking-website">Website</label>
                            <input id="booking-website" name="website" type="text" value="{{ old('website') }}" tabindex="-1" autocomplete="off">
                        </div>

                        @if($errors->has('website'))
                            <p class="rounded-lg border border-error/30 bg-error-light px-4 py-3 text-sm text-error" role="alert">We could not accept this request. Please try again.</p>
                        @endif

                        <div class="flex flex-col gap-3 border-t border-primary/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                            <p class="max-w-sm text-xs leading-relaxed text-primary-dark/50">Submitting sends a request to Waggies. It does not reserve a slot or confirm an appointment.</p>
                            <x-waggies.button type="submit" class="w-full sm:w-auto" x-bind:disabled="submitting" x-bind:aria-busy="submitting">
                                <span x-show="!submitting">Send booking request</span>
                                <span x-show="submitting" x-cloak>Sending request...</span>
                                <x-waggies.icon name="arrow-forward" size="16" />
                            </x-waggies.button>
                        </div>
                    </form>
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
