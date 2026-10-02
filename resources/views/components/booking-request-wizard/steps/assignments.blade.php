                    @if($step === 3)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Match pets and service details</legend>
                            <section class="rounded-xl border border-primary/10 bg-surface-purple/45 p-4" aria-labelledby="assignment-overview-heading">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-eyebrow text-primary-dark/50">ASSIGNMENT CHECK</p>
                                        <h3 id="assignment-overview-heading" class="mt-1 text-sm font-bold text-primary-dark">Every pet needs a service</h3>
                                    </div>
                                    <span class="text-xs font-semibold text-primary-dark/55">{{ collect($services)->sum(fn (array $service): int => $this->serviceAssignedCount($service)) }} matches</span>
                                </div>
                                <ul class="mt-3 grid gap-2 text-sm sm:grid-cols-2">
                                    @foreach($pets as $petIndex => $pet)
                                        <li id="booking-pet-assignment-{{ $petIndex }}" class="flex items-start justify-between gap-3 rounded-lg bg-white/70 px-3 py-2 {{ $this->petAssignedServiceCount($petIndex) === 0 ? 'ring-1 ring-error/30' : '' }}">
                                            <span><span class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span><span class="mt-0.5 block text-xs text-primary-dark/55">{{ $this->petAssignedServiceCount($petIndex) > 0 ? $this->petAssignedServiceCount($petIndex).' service'.($this->petAssignedServiceCount($petIndex) === 1 ? '' : 's') : 'Not assigned yet' }}</span></span>
                                            <span class="flex shrink-0 items-center gap-3">
                                                @if($this->petAssignedServiceCount($petIndex) > 0)
                                                    <x-waggies.icon name="check-circle" variant="filled" size="18" class="text-success" />
                                                @else
                                                    <span class="text-xs font-semibold text-error">Needs a match</span>
                                                @endif
                                                @if(count($pets) > 1)
                                                    <button type="button" wire:click="removePet({{ $petIndex }})" class="text-xs font-semibold text-primary underline decoration-primary/30 underline-offset-2 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                                @endif
                                            </span>
                                            @error('pets.'.$petIndex.'.assignments') <span class="sr-only">{{ $message }}</span> @enderror
                                        </li>
                                    @endforeach
                                </ul>
                            </section>
                            @foreach($services as $index => $service)
                                @php $fields = $this->serviceFields($service); @endphp
                                <section wire:key="booking-service-details-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="booking-service-details-heading-{{ $index }}">
                                    <div>
                                        <p class="text-eyebrow text-primary-dark/50">SERVICE</p>
                                        <h3 id="booking-service-details-heading-{{ $index }}" class="mt-1 font-serif text-xl font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</h3>
                                        <p class="mt-1 text-sm text-primary-dark/60">{{ $this->servicePetRequirement($service) }} Assign at least one compatible pet.</p>
                                        @if($this->isDuplicateService($index))
                                            <p class="mt-3 rounded-lg border border-danger/25 bg-error-light p-3 text-xs font-medium leading-relaxed text-primary-dark" role="alert">This is an identical service item. Assign more pets to one service, or change this service’s schedule or details.</p>
                                        @endif
                                    </div>

                                    <div id="booking-service-{{ $index }}-assignment" class="mt-5 grid gap-3 sm:grid-cols-2">
                                        @foreach($pets as $petIndex => $pet)
                                            @php $compatible = $this->petCompatible($service, $pet); @endphp
                                            <label class="flex min-h-16 items-center gap-3 rounded-xl border p-4 transition-colors {{ in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true) ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15' }} {{ ! $compatible ? 'cursor-not-allowed bg-surface/70 opacity-60' : 'cursor-pointer hover:border-primary/40' }}">
                                                <input type="checkbox" value="{{ $petIndex }}" wire:model.live="services.{{ $index }}.assigned_pet_ids" @disabled(! $compatible) aria-describedby="booking-service-{{ $index }}-pet-{{ $petIndex }}-status" class="size-5 rounded border-primary/30 text-primary focus:ring-primary">
                                                <span class="min-w-0">
                                                    <span class="block font-semibold text-primary-dark">{{ $pet['name'] ?: 'Pet '.($petIndex + 1) }}</span>
                                                    <span id="booking-service-{{ $index }}-pet-{{ $petIndex }}-status" class="mt-1 block text-xs text-primary-dark/60">{{ $this->petSpeciesLabel($pet['species'] ?? null) }}{{ ! $compatible ? ' · '.$this->petCompatibilityReason($service, $pet) : '' }}</span>
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="mt-3 text-xs font-semibold {{ $this->serviceAssignedCount($service) > 0 ? 'text-success' : 'text-error' }}">{{ $this->serviceAssignedCount($service) > 0 ? $this->serviceAssignedCount($service).' pet'.($this->serviceAssignedCount($service) === 1 ? '' : 's').' assigned' : 'Needs one compatible pet' }}</p>
                                    @error('services.'.$index.'.assigned_pet_ids') <p class="mt-2 text-sm text-error">{{ $message }}</p> @enderror

                                    @if($service['service_key'])
                                        <div class="mt-6 border-t border-primary/10 pt-5">
                                            <p class="text-sm font-semibold text-primary-dark">Details for this service</p>
                                            @if($service['service_key'] === 'relocation' && $service['service_variant'])
                                                <div class="mt-4 rounded-2xl border border-primary/15 bg-surface-purple/30 p-4" aria-labelledby="booking-service-{{ $index }}-route-heading">
                                                    <p id="booking-service-{{ $index }}-route-heading" class="text-eyebrow text-primary-dark/50">FLIGHT ROUTE</p>
                                                    <div class="mt-3 grid items-stretch gap-3 sm:grid-cols-[minmax(0,1fr)_auto_minmax(0,1fr)] sm:items-center">
                                                        <div class="rounded-xl border border-primary/10 bg-white p-3">
                                                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.12em] text-primary-dark/45">From</p>
                                                            <p class="mt-1 text-sm font-semibold leading-relaxed text-primary-dark">{{ $this->relocationEndpointLabel($service, 'origin') }}</p>
                                                        </div>
                                                        <span class="hidden text-xl font-semibold text-primary/55 sm:block" aria-hidden="true">→</span>
                                                        <span class="text-center text-xl font-semibold text-primary/55 sm:hidden" aria-hidden="true">↓</span>
                                                        <div class="rounded-xl border border-primary/10 bg-white p-3">
                                                            <p class="text-[0.68rem] font-bold uppercase tracking-[0.12em] text-primary-dark/45">To</p>
                                                            <p class="mt-1 text-sm font-semibold leading-relaxed text-primary-dark">{{ $this->relocationEndpointLabel($service, 'destination') }}</p>
                                                        </div>
                                                    </div>
                                                    <p class="mt-3 text-xs leading-relaxed text-primary-dark/55">The Abuja airport endpoint is fixed. Choose the other country below, then add any flight details you already have.</p>
                                                </div>
                                            @endif
                                            @if($this->serviceAssignedCount($service) > 1)
                                                <p class="mt-2 rounded-lg bg-surface-purple/45 p-3 text-xs leading-relaxed text-primary-dark/65">This information applies to every pet assigned to this service. If their needs differ, mention each pet by name.</p>
                                            @endif
                                            @php $hasDateField = collect($fields)->contains(fn (array $field): bool => ($field['type'] ?? null) === 'date'); @endphp
                                            @if($hasDateField)
                                                <p class="mt-3 text-xs font-medium text-primary-dark/55">Dates and times use Africa/Lagos time — WAT (UTC+1).</p>
                                            @endif
                                            <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                @foreach($fields as $field)
                                                    @continue(! $this->fieldVisible($field, $service))
                                                    @continue(($field['fixed'] ?? false) === true)
                                                    @php
                                                        $model = $this->fieldModel($index, $field);
                                                        $fieldId = 'booking-'.$index.'-'.$field['key'];
                                                        $fieldValue = ($field['scope'] ?? 'details') === 'service' ? ($service[$field['key']] ?? null) : ($service['details'][$field['key']] ?? null);
                                                    @endphp
                                                    @if($field['type'] === 'textarea')
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['placeholder'] ?? null" :required="$field['required']" class="sm:col-span-2">
                                                            <textarea id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" rows="3" maxlength="2000" class="contact-input resize-y"></textarea>
                                                        </x-waggies.field>
                                                    @elseif($field['type'] === 'country')
                                                        <x-waggies.searchable-select :id="$fieldId" :label="$field['label']" :options="$field['options']" :placeholder="$field['placeholder']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']" />
                                                    @elseif($field['type'] === 'select' && count($field['options']) <= 3)
                                                        <fieldset id="{{ $fieldId }}" aria-labelledby="{{ $fieldId }}-label">
                                                            <legend id="{{ $fieldId }}-label" class="text-sm font-medium text-primary-dark">{{ $field['label'] }}@if($field['required']) <span class="text-danger" aria-hidden="true">*</span>@endif</legend>
                                                            <div class="mt-2 grid gap-3">
                                                                @foreach($field['options'] as $key => $label)
                                                                    <label wire:key="{{ $fieldId }}-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-3 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ $fieldValue === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                                        <input type="radio" name="{{ $fieldId }}" value="{{ $key }}" @checked($fieldValue === $key) wire:model.live="{{ $model }}" class="sr-only peer">
                                                                        <span class="text-sm font-semibold text-primary-dark">{{ $label }}</span>
                                                                        <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                                    </label>
                                                                @endforeach
                                                            </div>
                                                            @if($errors->first($model)) <p class="mt-2 text-sm text-danger" role="alert">{{ $errors->first($model) }}</p> @endif
                                                        </fieldset>
                                                    @elseif($field['type'] === 'select')
                                                        <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']">
                                                            <option value="">Select an option</option>
                                                            @foreach($field['options'] as $key => $label)
                                                                <option value="{{ $key }}">{{ $label }}</option>
                                                            @endforeach
                                                        </x-waggies.select>
                                                    @elseif($field['type'] === 'date')
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['help'] ?? null" :required="$field['required']">
                                                            @php $fieldMinimum = $this->dateMinimum($service, $field); @endphp
                                                            <div x-data="waggiesDatePicker({ value: @js($fieldValue), minimum: @js($fieldMinimum) })" @keydown.escape="open = false" class="relative">
                                                                <input id="{{ $fieldId }}-native" x-ref="native" wire:model.live="{{ $model }}" x-on:input="value = $event.target.value" x-on:change="value = $event.target.value" type="date" min="{{ $fieldMinimum }}" hidden aria-hidden="true" tabindex="-1">
                                                                <button id="{{ $fieldId }}" x-ref="trigger" type="button" @click="open = ! open" :aria-expanded="open" aria-haspopup="dialog" class="contact-input flex items-center justify-between gap-3 text-left focus-visible:outline-none" :class="open ? 'ring-2 ring-primary/40' : ''">
                                                                    <span class="min-w-0 flex-1 truncate" :class="value ? 'text-primary-dark' : 'text-primary-dark/45'" x-text="formattedValue() || 'Choose a date'"></span>
                                                                    <x-waggies.icon name="calendar" size="18" class="shrink-0 text-primary/65" />
                                                                </button>
                                                                <div x-show="open" x-cloak @click.outside="open = false" role="dialog" aria-modal="false" aria-label="Choose a date" class="absolute left-0 top-[calc(100%+0.5rem)] z-20 w-full min-w-[18rem] rounded-2xl border border-primary/15 bg-white p-4 shadow-lg">
                                                                    <div class="flex items-center justify-between gap-3">
                                                                        <button type="button" @click="changeMonth(-1)" :disabled="isBeforeMinimumMonth()" aria-label="Previous month" class="flex size-10 items-center justify-center rounded-lg text-primary transition-colors hover:bg-surface-purple disabled:cursor-not-allowed disabled:opacity-35 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"><x-waggies.icon name="arrow-back" size="18" /></button>
                                                                        <p class="text-sm font-bold text-primary-dark" aria-live="polite" x-text="monthLabel()"></p>
                                                                        <button type="button" @click="changeMonth(1)" aria-label="Next month" class="flex size-10 items-center justify-center rounded-lg text-primary transition-colors hover:bg-surface-purple focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary"><x-waggies.icon name="arrow-forward" size="18" /></button>
                                                                    </div>
                                                                    <div class="mt-3 grid grid-cols-7 gap-1 text-center text-[0.68rem] font-bold uppercase tracking-wide text-primary-dark/45" aria-hidden="true">
                                                                        <template x-for="weekday in weekdays()" :key="weekday"><span x-text="weekday"></span></template>
                                                                    </div>
                                                                    <div class="mt-2 grid grid-cols-7 gap-1" role="grid" aria-label="Calendar dates">
                                                                        <template x-for="(day, dayIndex) in days()" :key="day || `empty-${dayIndex}`">
                                                                            <span class="flex aspect-square items-center justify-center">
                                                                                <button x-show="day" type="button" @click="choose(day)" :disabled="isDisabled(day)" :aria-current="isToday(day) ? 'date' : null" :aria-pressed="isSelected(day)" :class="{ 'bg-primary text-white': isSelected(day), 'ring-1 ring-primary': isToday(day) && ! isSelected(day), 'text-primary-dark/30': isDisabled(day), 'text-primary-dark hover:bg-surface-purple': ! isDisabled(day) && ! isSelected(day) }" class="flex size-9 items-center justify-center rounded-lg text-sm font-semibold transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary" x-text="day ? Number(day.slice(-2)) : ''"></button>
                                                                            </span>
                                                                        </template>
                                                                    </div>
                                                                </div>
                                                                <p x-show="value" x-cloak class="mt-2 text-xs font-medium text-primary-dark/55" x-text="'Selected: ' + formattedValue()"></p>
                                                            </div>
                                                        </x-waggies.field>
                                                    @else
                                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :error="$errors->first($model)" :help="$field['help'] ?? null" :required="$field['required']">
                                                            <input id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" type="{{ $field['type'] }}" @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="contact-input">
                                                        </x-waggies.field>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @php $quote = $this->serviceQuote($service); @endphp
                                    <div class="mt-6 rounded-xl bg-surface-purple/55 p-4" aria-live="polite">
                                        <div class="flex flex-wrap items-center justify-between gap-3">
                                            <p class="text-sm font-semibold text-primary-dark" wire:loading.remove>Current estimate</p>
                                            <p class="text-sm font-semibold text-primary-dark" wire:loading>Updating estimate…</p>
                                            <p class="font-semibold text-primary-dark" wire:loading.remove>{{ $this->quoteDisplay($quote, $service) }}</p>
                                        </div>
                                        @if($this->quoteQuantitySummary($service, $quote))
                                            <p class="mt-1 text-xs font-semibold text-primary-dark/60">{{ $this->quoteQuantitySummary($service, $quote) }}</p>
                                        @endif
                                        @if(($quote['status'] ?? null) === 'needs_input')
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">{{ $quote['reason'] ?? 'Complete the assigned pets and service details to see an estimate.' }}</p>
                                        @elseif(($quote['discount']['percentage'] ?? 0) > 0)
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">Includes {{ $quote['discount']['percentage'] }}% multiple-pet boarding discount.</p>
                                        @else
                                            <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">This is an estimate or starting price. Waggies confirms final availability and pricing with you.</p>
                                        @endif
                                    </div>
                                </section>
                            @endforeach
                        </fieldset>
                    @endif
