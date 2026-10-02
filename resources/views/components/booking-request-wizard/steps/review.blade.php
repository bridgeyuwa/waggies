                    @if($step === 5)
                        <section class="flex flex-col gap-6" aria-labelledby="booking-review-heading">
                            <h3 id="booking-review-heading" class="sr-only">Review your request</h3>
                            @foreach($services as $index => $service)
                                @php $quote = $this->serviceQuote($service); @endphp
                                <article class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">SERVICE</p>
                                            <h4 class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h4>
                                            <p class="mt-1 text-sm text-primary-dark/60">{{ $this->scheduleSummary($service) }}</p>
                                        </div>
                                        <button type="button" wire:click="editService({{ $index }})" class="shrink-0 text-sm font-semibold text-primary underline underline-offset-4">Change</button>
                                    </div>
                                    <div class="mt-5 grid gap-4 border-t border-primary/10 pt-5 sm:grid-cols-2">
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary-dark/50">Assigned pets</p>
                                            <ul class="mt-2 space-y-1 text-sm text-primary-dark/75">
                                                @foreach($service['assigned_pet_ids'] ?? [] as $petIndex)
                                                    <li>{{ $pets[(int) $petIndex]['name'] ?? 'Pet '.((int) $petIndex + 1) }} · {{ $this->petSpeciesLabel($pets[(int) $petIndex]['species'] ?? null) }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div>
                                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-primary-dark/50">Estimate</p>
                                            <p class="mt-2 text-sm font-semibold text-primary-dark">{{ $this->quoteDisplay($quote, $service) }}</p>
                                            @if($this->quoteQuantitySummary($service, $quote))<p class="mt-1 text-xs text-primary-dark/60">{{ $this->quoteQuantitySummary($service, $quote) }}</p>@endif
                                            @if(($quote['discount']['percentage'] ?? 0) > 0)
                                                <p class="mt-1 text-xs text-primary-dark/60">Includes {{ $quote['discount']['percentage'] }}% multiple-pet discount.</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if($this->serviceReviewDetails($service) !== [])
                                        <dl class="mt-5 grid gap-3 border-t border-primary/10 pt-5 text-sm sm:grid-cols-2">
                                            @foreach($this->serviceReviewDetails($service) as $label => $value)
                                                <div>
                                                    <dt class="font-semibold text-primary-dark">{{ $label }}</dt>
                                                    <dd class="mt-1 text-primary-dark/70">{{ $value }}</dd>
                                                </div>
                                            @endforeach
                                        </dl>
                                    @endif
                                </article>
                            @endforeach

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Pets</h4><button type="button" wire:click="goToStep(2)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <ul class="mt-4 grid gap-4 text-sm text-primary-dark/75 sm:grid-cols-2">
                                    @foreach($this->assignedPetIndexes() as $petIndex)
                                        @php $pet = $pets[$petIndex]; @endphp
                                        <li wire:key="booking-review-pet-{{ $petIndex }}" class="rounded-xl bg-surface-purple/45 p-4">
                                            <p class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Unnamed pet' }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</p>
                                            <p class="mt-1 text-xs text-primary-dark/55">{{ $this->petAssignedTo($petIndex) }}</p>
                                            @if(($pet['size'] ?? null) || $pet['breed'] || $pet['age'] || $pet['sex'])
                                                <p class="mt-1">{{ implode(' · ', array_filter([$pet['size'] ? ucfirst($pet['size']).' size' : null, $pet['breed'], $this->petAgeLabel($pet['age'] ?? null), $pet['sex'] ? ucfirst($pet['sex']) : null])) }}</p>
                                            @endif
                                            @if($pet['notes'] || ($pet['details']['other_description'] ?? null))<p class="mt-2">{{ $pet['notes'] ?: $pet['details']['other_description'] }}</p>@endif
                                        </li>
                                    @endforeach
                                </ul>
                            </section>

                            <section class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                <div class="flex items-center justify-between gap-4"><h4 class="font-serif text-xl font-bold text-primary-dark">Contact</h4><button type="button" wire:click="goToStep(4)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                                <p class="mt-4 text-sm text-primary-dark/75">{{ $contact['name'] ?: 'Name not added' }} · {{ $contact['email'] ?: 'Email not added' }} · {{ $this->contactPhoneDisplay() }}</p>
                                @if($contact['preferred_contact_method'])<p class="mt-1 text-sm text-primary-dark/60">Preferred contact: {{ ucfirst($contact['preferred_contact_method']) }}</p>@endif
                            </section>
                        </section>
                    @endif
