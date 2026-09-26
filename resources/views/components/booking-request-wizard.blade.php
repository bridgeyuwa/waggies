<?php

use App\Actions\CreateBookingRequest;
use App\Support\BookingRequestSchema;
use Illuminate\Validation\Rule;
use Livewire\Component;

new class extends Component
{
    public int $step = 1;

    public bool $submitted = false;

    public string $minimumDate = '';

    public string $draftContextKey = '';

    public ?string $source = null;

    public string $whatsappUrl = '#';

    /** @var array<int, array<string, mixed>> */
    public array $services = [];

    /** @var array<int, array<string, mixed>> */
    public array $pets = [];

    /** @var array<string, string|null> */
    public array $contact = [
        'name' => null,
        'email' => null,
        'phone' => null,
        'preferred_contact_method' => null,
    ];

    /**
     * @param array{service?: ?string, variant?: ?string, tier?: ?string, source?: ?string, whatsappUrl?: string} $initialContext
     */
    public function mount(array $initialContext = []): void
    {
        $serviceOptions = BookingRequestSchema::serviceOptions();
        $service = $initialContext['service'] ?? array_key_first($serviceOptions);
        $service = array_key_exists($service, $serviceOptions) ? $service : array_key_first($serviceOptions);
        $variant = $initialContext['variant'] ?? BookingRequestSchema::defaultVariant($service);
        $tier = $initialContext['tier'] ?? BookingRequestSchema::defaultTier($service, $variant);

        $this->minimumDate = now()->toDateString();
        $this->draftContextKey = implode('|', [$service, $variant ?? '', $tier ?? '', $initialContext['source'] ?? '']);
        $this->source = $initialContext['source'] ?? null;
        $this->whatsappUrl = $initialContext['whatsappUrl'] ?? '#';
        $this->services = [$this->newService($service, $variant, $tier)];
        $this->pets = [$this->newPet()];
    }

    public function serviceChanged(int $index, string $service): void
    {
        if (! array_key_exists($service, BookingRequestSchema::serviceOptions())) {
            return;
        }

        $this->services[$index]['service_key'] = $service;
        $this->services[$index]['service_variant'] = BookingRequestSchema::defaultVariant($service);
        $this->services[$index]['pricing_tier'] = BookingRequestSchema::defaultTier($service, $this->services[$index]['service_variant']);
        $this->services[$index]['details'] = [];
        $this->resetValidation();
    }

    public function updatedStep(int|string $step): void
    {
        $this->step = max(1, min(5, (int) $step));
    }

    public function variantChanged(int $index, ?string $variant): void
    {
        $service = (string) ($this->services[$index]['service_key'] ?? '');
        $this->services[$index]['service_variant'] = $variant ?: null;
        $this->services[$index]['pricing_tier'] = BookingRequestSchema::defaultTier($service, $variant);
        $this->resetValidation();
    }

    public function tierChanged(int $index, ?string $tier): void
    {
        $service = (string) ($this->services[$index]['service_key'] ?? '');
        $variant = $this->services[$index]['service_variant'] ?? null;
        $tierOptions = BookingRequestSchema::tierOptions($service, $variant);

        $this->services[$index]['pricing_tier'] = array_key_exists((string) $tier, $tierOptions)
            ? $tier
            : null;
        $this->resetValidation();
    }

    public function addService(): void
    {
        $service = array_key_first(BookingRequestSchema::serviceOptions());
        $variant = BookingRequestSchema::defaultVariant($service);

        $this->services[] = $this->newService($service, $variant, BookingRequestSchema::defaultTier($service, $variant));
    }

    public function removeService(int $index): void
    {
        if (count($this->services) <= 1) {
            return;
        }

        unset($this->services[$index]);
        $this->services = array_values($this->services);
        $this->resetValidation();
    }

    public function addPet(): void
    {
        $this->pets[] = $this->newPet();
    }

    public function removePet(int $index): void
    {
        if (count($this->pets) <= 1) {
            return;
        }

        unset($this->pets[$index]);
        $this->pets = array_values($this->pets);
        $this->resetValidation();
    }

    public function nextStep(): void
    {
        $this->validate($this->rulesForStep($this->step));
        $this->step = min(5, $this->step + 1);
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function previousStep(): void
    {
        $this->resetValidation();
        $this->step = max(1, $this->step - 1);
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function errorAnchor(string $key): string
    {
        $parts = explode('.', $key);

        return match ($parts[0] ?? null) {
            'services' => 'booking-'.($parts[1] ?? '0').'-'.(($parts[2] ?? null) === 'details' ? ($parts[3] ?? 'service') : ($parts[2] ?? 'service')),
            'pets' => 'booking-pet-'.($parts[1] ?? '0').'-'.($parts[2] ?? 'name'),
            'contact' => 'booking-contact-'.($parts[1] ?? 'name'),
            default => 'booking-'.str_replace(['.', '*'], '-', $key),
        };
    }

    public function submit(CreateBookingRequest $createBookingRequest): void
    {
        $this->validate($this->allRules());

        $createBookingRequest->handle([
            'contact' => $this->contact,
            'pets' => $this->pets,
            'services' => $this->services,
            'source' => $this->source,
            'context' => [
                'submitted_from' => 'livewire-booking-wizard',
            ],
        ]);

        $this->submitted = true;
        $this->dispatch('booking-request-submitted');
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function allRules(): array
    {
        return [
            ...$this->rulesForStep(1),
            ...$this->rulesForStep(2),
            ...$this->rulesForStep(3),
            ...$this->rulesForStep(4),
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => $this->serviceRules(),
            2 => [
                'pets' => ['required', 'array', 'min:1', 'max:8'],
                'pets.*.name' => ['required', 'string', 'max:80'],
                'pets.*.species' => ['required', Rule::in(['dog', 'cat', 'bird', 'rabbit', 'reptile', 'other'])],
                'pets.*.breed' => ['nullable', 'string', 'max:120'],
                'pets.*.age' => ['nullable', 'string', 'max:40'],
                'pets.*.sex' => ['nullable', Rule::in(['female', 'male', 'unknown'])],
                'pets.*.notes' => ['nullable', 'string', 'max:1000'],
            ],
            3 => $this->serviceDetailRules(),
            4 => [
                'contact.name' => ['required', 'string', 'max:120'],
                'contact.email' => ['required', 'email', 'max:255'],
                'contact.phone' => ['required', 'string', 'max:40'],
                'contact.preferred_contact_method' => ['nullable', Rule::in(['phone', 'email', 'whatsapp'])],
            ],
            default => [],
        };
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function serviceRules(): array
    {
        $rules = [
            'services' => ['required', 'array', 'min:1', 'max:4'],
            'services.*.service_key' => ['required', Rule::in(array_keys(BookingRequestSchema::serviceOptions()))],
        ];

        foreach ($this->services as $index => $service) {
            $variantOptions = BookingRequestSchema::variantOptions($service['service_key'] ?? null);
            $tierOptions = BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null);

            $rules["services.{$index}.service_variant"] = [
                empty($variantOptions) ? 'nullable' : 'required',
                Rule::in(array_keys($variantOptions)),
            ];
            $rules["services.{$index}.pricing_tier"] = [
                empty($tierOptions) ? 'nullable' : 'required',
                Rule::in(array_keys($tierOptions)),
            ];
        }

        return $rules;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function serviceDetailRules(): array
    {
        $rules = [];

        foreach ($this->services as $index => $service) {
            $fields = BookingRequestSchema::serviceFields((string) ($service['service_key'] ?? ''));

            foreach ($fields as $field) {
                $key = $field['key'];
                $path = in_array($key, ['requested_date', 'requested_time', 'location'], true)
                    ? "services.{$index}.{$key}"
                    : "services.{$index}.details.{$key}";
                $fieldRules = [$field['required'] ? 'required' : 'nullable'];

                if ($field['type'] === 'date') {
                    $fieldRules = [...$fieldRules, 'date_format:Y-m-d', 'after_or_equal:today'];
                } elseif ($field['type'] === 'time') {
                    $fieldRules[] = 'date_format:H:i';
                } elseif ($field['type'] === 'select') {
                    $fieldRules[] = Rule::in(array_keys($field['options'] ?? []));
                } else {
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'max:2000';
                }

                $rules[$path] = $fieldRules;
            }
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    private function newService(?string $service, ?string $variant, ?string $tier): array
    {
        return [
            'service_key' => $service,
            'service_variant' => $variant,
            'pricing_tier' => $tier,
            'requested_date' => null,
            'requested_time' => null,
            'location' => null,
            'details' => [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function newPet(): array
    {
        return [
            'name' => null,
            'species' => null,
            'breed' => null,
            'age' => null,
            'sex' => null,
            'notes' => null,
            'details' => [],
        ];
    }
};
?>

<div data-booking-draft="waggies-booking-request-v1" data-booking-context="{{ $draftContextKey }}" data-booking-draft-label="Booking request">
    @if($submitted)
        <div class="flex flex-col gap-5" role="status" tabindex="-1" data-booking-success>
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-success-light text-success">
                <x-waggies.icon name="check-circle" variant="filled" size="28" />
            </div>
            <div>
                <h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">Your request was received</h2>
                <p class="mt-3 max-w-xl text-sm leading-relaxed text-primary-dark/70">Our team will review your service, date, and pet details, then contact you to confirm the next steps. Your requested time is not reserved until Waggies confirms it.</p>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row">
                <x-waggies.button href="{{ $this->whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="w-full sm:w-auto">
                    Continue on WhatsApp <x-waggies.icon name="arrow-forward" size="16" />
                </x-waggies.button>
                <x-waggies.button href="{{ route('book') }}" variant="secondary" class="w-full sm:w-auto">Send another request</x-waggies.button>
            </div>
        </div>
    @else
        <div class="mb-8">
            <p class="text-eyebrow mb-2 text-primary">YOUR REQUEST</p>
            <h2 id="booking-form-title" class="font-serif text-2xl font-bold text-primary-dark sm:text-3xl">Tell us what your pet needs</h2>
            <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">We will ask only the details that help us review this request. Required fields are marked with <span class="text-primary" aria-hidden="true">*</span>.</p>
        </div>

        <div class="mb-8 grid grid-cols-5 gap-2" aria-label="Request progress">
            @foreach(['Service', 'Pets', 'Details', 'Contact', 'Review'] as $progressIndex => $label)
                @php
                    $progressStep = $progressIndex + 1;
                @endphp
                <div class="flex flex-col gap-2">
                    <div class="h-1.5 rounded-full {{ $step >= $progressStep ? 'bg-primary' : 'bg-primary/10' }}" aria-hidden="true"></div>
                    <span class="text-[0.68rem] font-semibold uppercase tracking-[0.08em] {{ $step >= $progressStep ? 'text-primary-dark' : 'text-primary-dark/45' }}">{{ $label }}</span>
                </div>
            @endforeach
        </div>

        @if($errors->any())
            <div class="mb-8 rounded-xl border border-error/30 bg-error-light px-4 py-4 text-sm text-error" role="alert" tabindex="-1" data-booking-error-summary>
                    <h3 class="font-semibold">There is a problem with your request</h3>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach($errors->toArray() as $errorKey => $messages)
                        <li><a class="underline hover:no-underline" href="#{{ $this->errorAnchor($errorKey) }}">{{ $messages[0] }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form wire:submit="submit" aria-labelledby="booking-form-title" class="flex flex-col gap-8" novalidate>
            <input type="hidden" wire:model.live="step" value="{{ $step }}" aria-hidden="true" tabindex="-1">
            <p data-booking-draft-status class="text-xs text-primary-dark/50" aria-live="polite">Changes save automatically on this device during this visit.</p>
            @if($step === 1)
                <fieldset class="flex flex-col gap-6">
                    <legend class="font-serif text-xl font-bold text-primary-dark">Which services should we review?</legend>
                    <p class="text-sm leading-relaxed text-primary-dark/60">You can request more than one service for the same pet or group of pets.</p>

                    @foreach($services as $index => $service)
                        @php
                            $variantOptions = \App\Support\BookingRequestSchema::variantOptions($service['service_key'] ?? null);
                            $tierOptions = \App\Support\BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null);
                        @endphp
                        <div wire:key="booking-service-{{ $index }}" class="flex flex-col gap-5 rounded-xl border border-primary/10 bg-surface p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="font-semibold text-primary-dark">Service {{ $index + 1 }}</h3>
                                    <p class="mt-1 text-sm text-primary-dark/60">Choose the service you want to discuss.</p>
                                </div>
                                @if(count($services) > 1)
                                    <button type="button" wire:click="removeService({{ $index }})" class="min-h-11 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-waggies.select id="booking-{{ $index }}-service" label="Service needed" data-booking-draft-model="services.{{ $index }}.service_key" wire:change="serviceChanged({{ $index }}, $event.target.value)" required>
                                    <option value="" @selected(empty($service['service_key']))>Choose a service</option>
                                    @foreach(\App\Support\BookingRequestSchema::serviceOptions() as $key => $label)
                                        <option value="{{ $key }}" @selected(($service['service_key'] ?? null) === $key)>{{ $label }}</option>
                                    @endforeach
                                </x-waggies.select>
                                @if($variantOptions)
                                    <x-waggies.select id="booking-{{ $index }}-variant" label="Pet group or service variant" data-booking-draft-model="services.{{ $index }}.service_variant" wire:change="variantChanged({{ $index }}, $event.target.value)" required>
                                        <option value="" @selected(empty($service['service_variant']))>Choose an option</option>
                                        @foreach($variantOptions as $key => $label)
                                            <option value="{{ $key }}" @selected(($service['service_variant'] ?? null) === $key)>{{ $label }}</option>
                                        @endforeach
                                    </x-waggies.select>
                                @endif
                                @if($tierOptions)
                                    <x-waggies.select id="booking-{{ $index }}-tier" label="Package or plan" data-booking-draft-model="services.{{ $index }}.pricing_tier" wire:change="tierChanged({{ $index }}, $event.target.value)" required>
                                        <option value="" @selected(empty($service['pricing_tier']))>Choose an option</option>
                                        @foreach($tierOptions as $key => $label)
                                            <option value="{{ $key }}" @selected(($service['pricing_tier'] ?? null) === $key)>{{ $label }}</option>
                                        @endforeach
                                    </x-waggies.select>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addService" class="inline-flex min-h-11 w-fit items-center gap-2 rounded-lg border border-primary/20 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span aria-hidden="true">+</span> Add another service
                    </button>
                </fieldset>
            @elseif($step === 2)
                <fieldset class="flex flex-col gap-6">
                    <legend class="font-serif text-xl font-bold text-primary-dark">Tell us about your pet{{ count($pets) > 1 ? 's' : '' }}</legend>
                    <p class="text-sm leading-relaxed text-primary-dark/60">Add each pet who may be included in the request.</p>

                    @foreach($pets as $index => $pet)
                        <div wire:key="booking-pet-{{ $index }}" class="flex flex-col gap-5 rounded-xl border border-primary/10 bg-surface p-5">
                            <div class="flex items-start justify-between gap-4">
                                <h3 class="font-semibold text-primary-dark">Pet {{ $index + 1 }}</h3>
                                @if(count($pets) > 1)
                                    <button type="button" wire:click="removePet({{ $index }})" class="min-h-11 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                @endif
                            </div>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" required>
                                    <input id="booking-pet-{{ $index }}-name" wire:model="pets.{{ $index }}.name" type="text" maxlength="80" autocomplete="off" class="contact-input">
                                </x-waggies.field>
                                <x-waggies.select id="booking-pet-{{ $index }}-species" label="Pet type" wire:model="pets.{{ $index }}.species" required>
                                    <option value="">Choose a type</option>
                                    @foreach(['dog' => 'Dog', 'cat' => 'Cat', 'bird' => 'Bird', 'rabbit' => 'Rabbit', 'reptile' => 'Reptile', 'other' => 'Other'] as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </x-waggies.select>
                                <x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed" help="Optional">
                                    <input id="booking-pet-{{ $index }}-breed" wire:model="pets.{{ $index }}.breed" type="text" maxlength="120" class="contact-input">
                                </x-waggies.field>
                                <x-waggies.field id="booking-pet-{{ $index }}-age" label="Age or life stage" help="Optional">
                                    <input id="booking-pet-{{ $index }}-age" wire:model="pets.{{ $index }}.age" type="text" maxlength="40" placeholder="For example: 3 years" class="contact-input">
                                </x-waggies.field>
                            </div>
                            <x-waggies.field id="booking-pet-{{ $index }}-notes" label="Pet notes" help="Optional. Share temperament, routines, or care notes.">
                                <textarea id="booking-pet-{{ $index }}-notes" wire:model="pets.{{ $index }}.notes" rows="3" maxlength="1000" class="contact-input"></textarea>
                            </x-waggies.field>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addPet" class="inline-flex min-h-11 w-fit items-center gap-2 rounded-lg border border-primary/20 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span aria-hidden="true">+</span> Add another pet
                    </button>
                </fieldset>
            @elseif($step === 3)
                <fieldset class="flex flex-col gap-8">
                    <legend class="font-serif text-xl font-bold text-primary-dark">Service details</legend>
                    <p class="text-sm leading-relaxed text-primary-dark/60">These details help the team prepare a useful response. Availability is confirmed after review.</p>

                    @foreach($services as $index => $service)
                        @php
                            $fields = \App\Support\BookingRequestSchema::serviceFields((string) ($service['service_key'] ?? ''));
                        @endphp
                        <div wire:key="booking-details-{{ $index }}" class="flex flex-col gap-5 rounded-xl border border-primary/10 bg-surface p-5">
                            <h3 class="font-semibold text-primary-dark">{{ \App\Support\BookingRequestSchema::serviceOptions()[$service['service_key'] ?? ''] ?? 'Service' }}</h3>
                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                @foreach($fields as $field)
                                    @php
                                        $isServiceValue = in_array($field['key'], ['requested_date', 'requested_time', 'location'], true);
                                        $model = $isServiceValue ? "services.{$index}.{$field['key']}" : "services.{$index}.details.{$field['key']}";
                                        $fieldId = 'booking-'.$index.'-'.$field['key'];
                                    @endphp
                                    @if($field['type'] === 'textarea')
                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['placeholder'] ?? null" :required="$field['required']" class="sm:col-span-2">
                                            <textarea id="{{ $fieldId }}" wire:model="{{ $model }}" rows="4" maxlength="2000" class="contact-input"></textarea>
                                        </x-waggies.field>
                                    @elseif($field['type'] === 'select')
                                        <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model="{{ $model }}" :required="$field['required']">
                                            <option value="">Choose an option</option>
                                            @foreach($field['options'] as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}</option>
                                            @endforeach
                                        </x-waggies.select>
                                    @else
                                        <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['help'] ?? null" :required="$field['required']">
                                            <input id="{{ $fieldId }}" wire:model="{{ $model }}" type="{{ $field['type'] }}" @if($field['type'] === 'date') min="{{ $minimumDate }}" @endif @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="contact-input">
                                        </x-waggies.field>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </fieldset>
            @elseif($step === 4)
                <fieldset class="flex flex-col gap-6">
                    <legend class="font-serif text-xl font-bold text-primary-dark">How should we contact you?</legend>
                    <p class="text-sm leading-relaxed text-primary-dark/60">We use these details only to review and respond to this request.</p>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <x-waggies.field id="booking-contact-name" label="Your name" required>
                            <input id="booking-contact-name" wire:model="contact.name" type="text" autocomplete="name" maxlength="120" class="contact-input">
                        </x-waggies.field>
                        <x-waggies.field id="booking-contact-email" label="Email address" required>
                            <input id="booking-contact-email" wire:model="contact.email" type="email" autocomplete="email" maxlength="255" class="contact-input">
                        </x-waggies.field>
                        <x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" required>
                            <input id="booking-contact-phone" wire:model="contact.phone" type="tel" autocomplete="tel" maxlength="40" class="contact-input">
                        </x-waggies.field>
                        <x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model="contact.preferred_contact_method">
                            <option value="">No preference</option>
                            <option value="phone">Phone</option>
                            <option value="email">Email</option>
                            <option value="whatsapp">WhatsApp</option>
                        </x-waggies.select>
                    </div>
                </fieldset>
            @else
                <section class="flex flex-col gap-6" aria-labelledby="booking-review-heading">
                    <div>
                        <h2 id="booking-review-heading" class="font-serif text-xl font-bold text-primary-dark">Review your request</h2>
                        <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">Check the details before sending. You can go back to make changes.</p>
                    </div>
                    <div class="divide-y divide-primary/10 rounded-xl border border-primary/10 bg-surface">
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h3 class="font-semibold text-primary-dark">Services</h3><button type="button" wire:click="$set('step', 1)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <ul class="mt-3 space-y-2 text-sm text-primary-dark/70">
                                @foreach($services as $service)
                                    <li>{{ \App\Support\BookingRequestSchema::serviceOptions()[$service['service_key'] ?? ''] ?? 'Service' }}@if($service['pricing_tier']) · {{ \App\Support\BookingRequestSchema::tierOptions($service['service_key'], $service['service_variant'])[$service['pricing_tier']] ?? $service['pricing_tier'] }}@endif</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h3 class="font-semibold text-primary-dark">Pets</h3><button type="button" wire:click="$set('step', 2)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <ul class="mt-3 space-y-2 text-sm text-primary-dark/70">
                                @foreach($pets as $pet)
                                    <li>{{ $pet['name'] ?: 'Unnamed pet' }} · {{ ucfirst($pet['species'] ?: 'type not selected') }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h3 class="font-semibold text-primary-dark">Contact</h3><button type="button" wire:click="$set('step', 4)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <p class="mt-3 text-sm text-primary-dark/70">{{ $contact['name'] ?: 'Name not added' }} · {{ $contact['email'] ?: 'Email not added' }}</p>
                        </div>
                    </div>
                </section>
            @endif

            <div class="flex flex-col gap-3 border-t border-primary/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="max-w-sm text-xs leading-relaxed text-primary-dark/50">Submitting sends a request to Waggies. It does not reserve a slot or confirm an appointment.</p>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    @if($step > 1)
                        <x-waggies.button type="button" variant="secondary" wire:click="previousStep" wire:loading.attr="disabled" wire:target="previousStep">Back</x-waggies.button>
                    @endif
                    @if($step < 5)
                        <x-waggies.button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep">Continue <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    @else
                        <x-waggies.button type="submit" wire:loading.attr="disabled" wire:target="submit">
                            <span wire:loading.remove wire:target="submit">Send request</span>
                            <span wire:loading wire:target="submit">Sending request...</span>
                            <x-waggies.icon name="arrow-forward" size="16" />
                        </x-waggies.button>
                    @endif
                </div>
            </div>
        </form>
    @endif
</div>
