                    @if($step === 1)
                        <fieldset class="flex flex-col gap-5">
                            <legend class="sr-only">Services</legend>
                            @foreach($services as $index => $service)
                                <div wire:key="booking-service-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">SERVICE {{ $index + 1 }}</p>
                                            <h3 class="mt-1 font-serif text-xl font-bold text-primary-dark">Choose a service</h3>
                                            <p class="mt-1 text-sm text-primary-dark/60">Select the care your pet needs. You can add another service below.</p>
                                        </div>
                                        @if(count($services) > 1)
                                            <button type="button" wire:click="removeService({{ $index }})" aria-label="Remove {{ $this->serviceLabel($service['service_key'] ?? null) }} service" class="min-h-11 shrink-0 rounded-lg border border-transparent px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 transition-colors hover:border-error/30 hover:bg-error-light hover:text-error focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">Remove</button>
                                        @endif
                                    </div>

                                    <fieldset class="mt-5" aria-labelledby="booking-service-{{ $index }}-choice-heading">
                                        <legend id="booking-service-{{ $index }}-choice-heading" class="text-sm font-semibold text-primary-dark">Which service do you need?</legend>
                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                        @foreach($this->serviceOptions() as $serviceKey => $serviceLabel)
                                            @php $available = $this->serviceAvailable($serviceKey); @endphp
                                            <label class="group flex min-h-16 items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors {{ $service['service_key'] === $serviceKey ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                <input type="radio" name="booking-service-{{ $index }}" value="{{ $serviceKey }}" @checked($service['service_key'] === $serviceKey) @disabled(! $available) wire:click="serviceChanged({{ $index }}, '{{ $serviceKey }}')" class="sr-only peer">
                                                <span>
                                                    <span class="block font-semibold text-primary-dark">{{ $serviceLabel }}</span>
                                                    @if(! $available)
                                                        <span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>
                                                    @endif
                                                </span>
                                                <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                            </label>
                                        @endforeach
                                        </div>
                                    </fieldset>

                                    @if($service['service_key'])
                                            @php
                                                $selectionMode = \App\Support\BookingRequestSchema::serviceSelectionMode($service['service_key']);
                                                $variantOptions = $this->allVariantOptions($service['service_key']);
                                            @endphp
                                            @if($selectionMode === 'pet_types')
                                                <div class="mt-6 rounded-xl border border-primary/10 bg-surface-purple/45 p-4">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Add your pets next</h4>
                                                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/65">This boarding service can include dogs, cats, or both. Add each pet in the next step and we will match them to this stay.</p>
                                                </div>
                                            @elseif($selectionMode === 'multiple')
                                                @php
                                                    $careNeeds = is_array($service['details']['care_needs'] ?? null)
                                                        ? $service['details']['care_needs']
                                                        : [];
                                                @endphp
                                                <div class="mt-6 border-t border-primary/10 pt-5">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Choose all care needs that apply</h4>
                                                    <p class="mt-1 text-sm leading-relaxed text-primary-dark/60">Select one or more reasons for the veterinary request. The team will review them together.</p>
                                                </div>
                                                <div class="mt-5">
                                                    <fieldset id="booking-service-{{ $index }}-care-needs" tabindex="-1" class="rounded-2xl border border-primary/15 bg-surface-purple/30 p-4 {{ $errors->has('services.'.$index.'.details.care_needs') || $errors->has('services.'.$index.'.details.care_needs.*') ? 'border-danger/60 ring-2 ring-danger/15' : '' }}">
                                                        <legend class="px-1 text-sm font-semibold text-primary-dark">What does your pet need help with? <span class="text-danger" aria-hidden="true">*</span></legend>
                                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                                            @foreach($variantOptions as $key => $label)
                                                                @php $available = $this->variantAvailable($service['service_key'], $key); @endphp
                                                                <label wire:key="booking-service-{{ $index }}-care-need-{{ $key }}" class="flex min-h-14 w-full items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm font-semibold transition-colors {{ in_array($key, $careNeeds, true) ? 'border-primary bg-white ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                                    <input type="checkbox" name="booking-service-{{ $index }}-care-needs" value="{{ $key }}" @checked(in_array($key, $careNeeds, true)) @disabled(! $available) wire:model.live="services.{{ $index }}.details.care_needs" class="sr-only peer">
                                                                    <span>{{ $label }}@if(! $available)<span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                                    <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @if($errors->has('services.'.$index.'.details.care_needs') || $errors->has('services.'.$index.'.details.care_needs.*'))
                                                            <p class="mt-2 text-sm font-medium text-danger" role="alert">{{ $errors->first('services.'.$index.'.details.care_needs') ?: $errors->first('services.'.$index.'.details.care_needs.*') }}</p>
                                                        @endif
                                                    </fieldset>
                                                    @if($this->serviceNeedsSelection($service))
                                                        <p class="mt-4 rounded-xl bg-surface-purple/55 p-3 text-sm leading-relaxed text-primary-dark/65">Choose at least one care need to continue.</p>
                                                    @endif
                                                </div>
                                            @elseif($variantOptions)
                                                <div class="mt-6 border-t border-primary/10 pt-5">
                                                    <p class="text-eyebrow text-primary-dark/50">NEXT</p>
                                                    <h4 class="mt-1 text-base font-bold text-primary-dark">Choose a service option</h4>
                                                    <p class="mt-1 text-sm leading-relaxed text-primary-dark/60">Choose the option that best matches what your pet needs.</p>
                                                </div>
                                                <div class="mt-5">
                                                    <fieldset id="booking-service-{{ $index }}-variant" tabindex="-1" class="rounded-2xl border border-primary/15 bg-surface-purple/30 p-4 {{ $errors->has('services.'.$index.'.service_variant') ? 'border-danger/60 ring-2 ring-danger/15' : '' }}">
                                                        <legend class="px-1 text-sm font-semibold text-primary-dark">{{ $this->serviceVariantQuestion($service['service_key']) }} <span class="text-danger" aria-hidden="true">*</span></legend>
                                                        <div class="mt-3 flex flex-wrap gap-3">
                                                            @foreach($variantOptions as $key => $label)
                                                                @php $available = $this->variantAvailable($service['service_key'], $key); @endphp
                                                                <label class="flex min-h-14 w-full items-center justify-between gap-3 rounded-xl border px-4 py-3 text-left text-sm font-semibold transition-colors sm:w-[calc(50%-0.375rem)] lg:w-auto lg:min-w-40 lg:flex-1 {{ $service['service_variant'] === $key ? 'border-primary bg-white ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }} {{ ! $available ? 'cursor-not-allowed opacity-55' : 'cursor-pointer' }}">
                                                                    <input type="radio" name="booking-service-{{ $index }}-variant" value="{{ $key }}" @checked($service['service_variant'] === $key) @disabled(! $available) wire:click="variantChanged({{ $index }}, '{{ $key }}')" class="sr-only peer">
                                                                    <span>{{ $label }}@if(! $available)<span class="mt-1 block text-xs font-medium text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                                    <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                        @if($errors->has('services.'.$index.'.service_variant'))
                                                            <p class="mt-2 text-sm font-medium text-danger" role="alert">{{ $errors->first('services.'.$index.'.service_variant') }}</p>
                                                        @endif
                                                    </fieldset>
                                                    @if(! $service['service_variant'])
                                                        <p class="mt-4 rounded-xl bg-surface-purple/55 p-3 text-sm leading-relaxed text-primary-dark/65">Choose one option to continue.</p>
                                                    @endif
                                                </div>
                                            @endif
                                    @else
                                        <p class="mt-5 rounded-xl bg-surface-purple/55 p-4 text-sm leading-relaxed text-primary-dark/65">Choose a service above to see the options and details it needs.</p>
                                    @endif
                                </div>
                            @endforeach

                            @if($choosingService)
                                <section class="rounded-2xl border-2 border-primary/20 bg-surface-purple/35 p-5 sm:p-6" aria-labelledby="add-service-heading">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">ADD TO YOUR REQUEST</p>
                                            <h3 id="add-service-heading" class="mt-1 font-serif text-xl font-bold text-primary-dark">Which service do you also need?</h3>
                                            <p class="mt-2 text-sm leading-relaxed text-primary-dark/65">Choose a service first. We will then show only the options and details that belong to it.</p>
                                        </div>
                                        <button type="button" wire:click="cancelAddService" class="min-h-11 shrink-0 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Cancel</button>
                                    </div>
                                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($this->serviceOptions() as $serviceKey => $serviceLabel)
                                            @php $available = $this->serviceAvailable($serviceKey); @endphp
                                            <button type="button" @if($available) wire:click="chooseAdditionalService('{{ $serviceKey }}')" @else disabled @endif class="flex min-h-16 items-center justify-between gap-3 rounded-xl border border-primary/15 bg-white p-4 text-left transition-colors hover:border-primary/40 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary {{ ! $available ? 'cursor-not-allowed opacity-55' : '' }}">
                                                <span><span class="block font-semibold text-primary-dark">{{ $serviceLabel }}</span>@if(! $available)<span class="mt-1 block text-xs text-primary-dark/60">Temporarily unavailable</span>@endif</span>
                                                <x-waggies.icon name="arrow-forward" size="18" class="shrink-0 text-primary" />
                                            </button>
                                        @endforeach
                                    </div>
                                </section>
                            @else
                                <button type="button" wire:click="addService" @disabled(count($services) >= $this->maxServiceItems()) class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:cursor-not-allowed disabled:opacity-50">
                                    <span aria-hidden="true" class="text-lg leading-none">+</span> Add another service
                                </button>
                                @if(count($services) >= $this->maxServiceItems())
                                    <p class="text-xs font-medium text-primary-dark/60" role="status">You can include up to {{ $this->maxServiceItems() }} service items in one request.</p>
                                @endif
                            @endif
                        </fieldset>
                    @endif
