                    @if($step === 4)
                        <fieldset class="flex max-w-xl flex-col gap-5">
                            <legend class="sr-only">Contact information</legend>
                            <x-waggies.field id="booking-contact-name" label="Your name" :error="$errors->first('contact.name')" required>
                                <input id="booking-contact-name" wire:model.live.blur="contact.name" wire:blur="fieldBlurred('contact.name')" type="text" autocomplete="name" maxlength="120" class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-contact-email" label="Email address" :error="$errors->first('contact.email')" required>
                                <input id="booking-contact-email" wire:model.live.blur="contact.email" wire:blur="fieldBlurred('contact.email')" type="email" autocomplete="email" maxlength="255" class="contact-input">
                            </x-waggies.field>
                            <x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" :error="$errors->first('contact.phone_number') ?: $errors->first('contact.phone_country')" required>
                                <div class="grid gap-3 sm:grid-cols-[minmax(0,15rem)_minmax(0,1fr)]">
                                    <div>
                                        <label for="booking-contact-phone-country" class="sr-only">Country calling code</label>
                                        <select id="booking-contact-phone-country" wire:model.live="contact.phone_country" class="contact-input">
                                            @foreach($this->phoneCountryOptions() as $countryCode => $countryLabel)
                                                <option value="{{ $countryCode }}">{{ $countryLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label for="booking-contact-phone-number" class="sr-only">Phone number</label>
                                        <input id="booking-contact-phone-number" wire:model.live.blur="contact.phone_number" wire:blur="fieldBlurred('contact.phone_number')" type="tel" autocomplete="tel-national" inputmode="tel" maxlength="40" placeholder="808 081 1902" class="contact-input">
                                    </div>
                                </div>
                                @if(($contact['phone_country'] ?? null) === 'OTHER')
                                    <div class="mt-3">
                                        <label for="booking-contact-other-country-code" class="text-xs font-semibold text-primary-dark">Country calling code</label>
                                        <input id="booking-contact-other-country-code" wire:model.live.blur="contact.phone_other_country_code" wire:blur="fieldBlurred('contact.phone_other_country_code')" type="text" inputmode="numeric" autocomplete="tel-country-code" maxlength="4" placeholder="+___" class="contact-input mt-1">
                                    </div>
                                @endif
                                @if($errors->has('contact.phone_other_country_code'))
                                    <p class="mt-2 text-sm text-danger" role="alert">{{ $errors->first('contact.phone_other_country_code') }}</p>
                                @endif
                                <p class="mt-2 text-xs leading-relaxed text-primary-dark/60">Choose the country code, then enter the number without the country code. You can include spaces or a leading 0.</p>
                            </x-waggies.field>
                            <x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model.live="contact.preferred_contact_method" :error="$errors->first('contact.preferred_contact_method')">
                                <option value="">No preference</option>
                                <option value="phone">Phone</option>
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                            </x-waggies.select>
                        </fieldset>
                    @endif
