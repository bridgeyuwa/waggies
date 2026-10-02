                    @if($step === 2)
                        <fieldset class="flex flex-col gap-6">
                            <legend class="sr-only">Pet details</legend>
                            @foreach($pets as $index => $pet)
                                <div wire:key="booking-pet-{{ $index }}" class="rounded-2xl border border-primary/15 bg-white p-5 shadow-sm sm:p-6">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-eyebrow text-primary-dark/50">PET PROFILE</p>
                                            <h3 id="booking-pet-{{ $index }}-heading" tabindex="-1" class="mt-1 font-serif text-xl font-bold text-primary-dark focus:outline-none">{{ $pet['name'] ? 'About '.$pet['name'] : 'Add a pet' }}</h3>
                                        </div>
                                        @if(count($pets) > 1)
                                            <button type="button" wire:click="removePet({{ $index }})" aria-label="Remove {{ $pet['name'] ?: 'pet '.($index + 1) }}" class="min-h-11 shrink-0 rounded-lg border border-transparent px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 transition-colors hover:border-error/30 hover:bg-error-light hover:text-error focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-error">Remove</button>
                                        @endif
                                    </div>

                                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                        <x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" :error="$errors->first('pets.'.$index.'.name')" required>
                                            <input id="booking-pet-{{ $index }}-name" wire:model.live.blur="pets.{{ $index }}.name" type="text" maxlength="80" autocomplete="off" class="contact-input">
                                        </x-waggies.field>
                                        <fieldset class="sm:col-span-2" aria-labelledby="booking-pet-{{ $index }}-species-heading">
                                            <legend id="booking-pet-{{ $index }}-species-heading" class="text-sm font-medium text-primary-dark">Pet type <span class="text-danger" aria-hidden="true">*</span></legend>
                                            <div class="mt-2 grid gap-3 sm:grid-cols-2">
                                                @foreach($this->petTypeOptions() as $key => $label)
                                                    <label wire:key="booking-pet-{{ $index }}-species-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-4 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ ($pet['species'] ?? null) === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                        <input type="radio" name="booking-pet-{{ $index }}-species" value="{{ $key }}" @checked(($pet['species'] ?? null) === $key) wire:model.live="pets.{{ $index }}.species" class="sr-only peer">
                                                        <span class="font-semibold text-primary-dark">{{ $label }}</span>
                                                        <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                    </label>
                                                @endforeach
                                            </div>
                                            @error('pets.'.$index.'.species') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                        </fieldset>
                                        @if($pet['species'] === 'dog' && $this->petNeedsSize($index))
                                            <fieldset class="sm:col-span-2" aria-labelledby="booking-pet-{{ $index }}-size-heading">
                                                <legend id="booking-pet-{{ $index }}-size-heading" class="text-sm font-semibold text-primary-dark">Dog size <span class="text-danger" aria-hidden="true">*</span></legend>
                                                <p class="mt-1 text-xs leading-relaxed text-primary-dark/60">Choose the closest size. You do not need to know your dog’s exact weight.</p>
                                                <div class="mt-3 grid gap-3 sm:grid-cols-3">
                                                    @foreach($this->petSizeOptions() as $size => $sizeOption)
                                                        <label class="cursor-pointer rounded-xl border p-4 transition-colors {{ ($pet['size'] ?? null) === $size ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                            <input type="radio" name="booking-pet-{{ $index }}-size" value="{{ $size }}" @checked(($pet['size'] ?? null) === $size) wire:model.live="pets.{{ $index }}.size" class="sr-only peer">
                                                            <span class="block font-semibold text-primary-dark">{{ $sizeOption['label'] }}</span>
                                                            @if($sizeOption['examples'])<span class="mt-1 block text-xs leading-relaxed text-primary-dark/60">{{ $sizeOption['examples'] }}</span>@endif
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('pets.'.$index.'.size') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                            </fieldset>
                                        @endif
                                    </div>


                                    <div class="mt-6 border-t border-primary/10 pt-5">
                                        <p class="text-sm font-semibold text-primary-dark">Optional details about this pet</p>
                                        <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed" :error="$errors->first('pets.'.$index.'.breed')" :help="$this->petBreedHelp($index)">
                                                <input id="booking-pet-{{ $index }}-breed" wire:model.live.blur="pets.{{ $index }}.breed" type="text" maxlength="120" class="contact-input">
                                            </x-waggies.field>
                                            <x-waggies.select id="booking-pet-{{ $index }}-age" label="Age or life stage" wire:model.live="pets.{{ $index }}.age" :error="$errors->first('pets.'.$index.'.age')" :help="$this->petAgeHelp($index)" :required="$this->petAgeRequired($index)">
                                                <option value="">Select life stage</option>
                                                @foreach($this->petAgeOptions() as $ageKey => $ageLabel)
                                                    <option value="{{ $ageKey }}">{{ $ageLabel }}</option>
                                                @endforeach
                                            </x-waggies.select>
                                            <fieldset aria-labelledby="booking-pet-{{ $index }}-sex-heading">
                                                <legend id="booking-pet-{{ $index }}-sex-heading" class="text-sm font-medium text-primary-dark">Sex <span class="text-danger" aria-hidden="true">*</span></legend>
                                                <div class="mt-2 grid gap-3 sm:grid-cols-2">
                                                    @foreach(['male' => 'Male', 'female' => 'Female'] as $key => $label)
                                                        <label wire:key="booking-pet-{{ $index }}-sex-{{ $key }}" class="flex min-h-12 cursor-pointer items-center justify-between gap-3 rounded-xl border p-3 text-left transition-colors focus-within:outline-none focus-within:ring-2 focus-within:ring-primary {{ ($pet['sex'] ?? null) === $key ? 'border-primary bg-surface-purple ring-1 ring-primary' : 'border-primary/15 bg-white hover:border-primary/40' }}">
                                                            <input type="radio" name="booking-pet-{{ $index }}-sex" value="{{ $key }}" @checked(($pet['sex'] ?? null) === $key) wire:model.live="pets.{{ $index }}.sex" class="sr-only peer">
                                                            <span class="font-semibold text-primary-dark">{{ $label }}</span>
                                                            <span class="hidden size-5 shrink-0 items-center justify-center rounded-full bg-primary text-white peer-checked:flex"><x-waggies.icon name="check" size="13" /></span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('pets.'.$index.'.sex') <p class="mt-2 text-sm text-danger" role="alert">{{ $message }}</p> @enderror
                                            </fieldset>
                                            <x-waggies.field id="booking-pet-{{ $index }}-notes" label="Pet notes" :error="$errors->first('pets.'.$index.'.notes')" help="Optional. Share temperament, routines, or care notes." class="sm:col-span-2">
                                                <textarea id="booking-pet-{{ $index }}-notes" wire:model.live.blur="pets.{{ $index }}.notes" rows="3" maxlength="1000" class="contact-input resize-y"></textarea>
                                            </x-waggies.field>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addPet" @disabled(count($pets) >= $this->maxPets()) aria-describedby="booking-pet-limit" class="inline-flex min-h-12 w-fit items-center gap-2 rounded-xl border border-primary/25 px-4 text-sm font-semibold text-primary-dark transition-colors hover:border-primary hover:bg-surface-purple disabled:cursor-not-allowed disabled:opacity-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                <span aria-hidden="true" class="text-lg leading-none">+</span> {{ count($pets) >= $this->maxPets() ? 'Maximum pets reached' : 'Add another pet' }}
                            </button>
                            <p id="booking-pet-limit" class="text-xs text-primary-dark/55">You can add up to {{ $this->maxPets() }} pets. Each pet can receive one or more of your selected services.</p>
                        </fieldset>
                    @endif
