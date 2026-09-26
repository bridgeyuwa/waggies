<?php

use App\Actions\CreateBookingRequest;
use App\Support\BookingRequestSchema;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
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
        $this->step = max(1, min(4, (int) $step));
    }

    public function updated(string $property): void
    {
        $rules = $this->allRules();
        $ruleKey = collect(array_keys($rules))->first(static fn (string $key): bool => $key === $property || Str::is($key, $property));

        if ($ruleKey === null) {
            return;
        }

        $this->validateOnly($property, $rules);
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
        if ($this->step >= 4) {
            return;
        }

        $this->validate($this->rulesForStep($this->step));
        $this->step++;
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function previousStep(): void
    {
        $this->resetValidation();
        $this->step = max(1, $this->step - 1);
        $this->dispatch('booking-wizard-step-changed', step: $this->step);
    }

    public function goToStep(int $step): void
    {
        $this->resetValidation();
        $this->step = max(1, min(4, $step));
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

    public function serviceSummary(array $service): string
    {
        $serviceLabel = BookingRequestSchema::serviceOptions()[$service['service_key'] ?? ''] ?? 'Choose a service';
        $variantLabel = BookingRequestSchema::variantOptions($service['service_key'] ?? null)[$service['service_variant'] ?? ''] ?? null;
        $tierLabel = BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null)[$service['pricing_tier'] ?? ''] ?? null;

        return implode(' · ', array_filter([$serviceLabel, $variantLabel, $tierLabel]));
    }

    public function scheduleSummary(array $service): string
    {
        $date = $service['requested_date'] ?? null;
        $dateLabel = $date ? Carbon::parse($date)->format('D, M j') : null;

        return implode(' · ', array_filter([
            $dateLabel,
            $service['requested_time'] ?? null,
            $service['location'] ?? null,
        ]));
    }

    public function petSpeciesLabel(?string $species): string
    {
        return [
            'dog' => 'Dog',
            'cat' => 'Cat',
            'bird' => 'Bird',
            'rabbit' => 'Rabbit',
            'reptile' => 'Reptile',
            'other' => 'Other',
        ][$species] ?? 'Type not selected';
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
        ];
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rulesForStep(int $step): array
    {
        return match ($step) {
            1 => [
                ...$this->serviceRules(),
                ...$this->serviceDetailRules(),
            ],
            2 => [
                'pets' => ['required', 'array', 'min:1', 'max:8'],
                'pets.*.name' => ['required', 'string', 'max:80'],
                'pets.*.species' => ['required', Rule::in(['dog', 'cat', 'bird', 'rabbit', 'reptile', 'other'])],
                'pets.*.breed' => ['nullable', 'string', 'max:120'],
                'pets.*.age' => ['nullable', 'string', 'max:40'],
                'pets.*.sex' => ['nullable', Rule::in(['female', 'male', 'unknown'])],
                'pets.*.notes' => ['nullable', 'string', 'max:1000'],
            ],
            3 => [
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
        @php
            $stepHeadings = [
                1 => ['eyebrow' => 'STEP 1 OF 4', 'title' => 'Confirm your service and schedule', 'description' => 'Start with the service, date, and location you have in mind.'],
                2 => ['eyebrow' => 'STEP 2 OF 4', 'title' => 'Tell us about your pet'.(count($pets) > 1 ? 's' : ''), 'description' => 'Add names and types first. The extra profile details are optional.'],
                3 => ['eyebrow' => 'STEP 3 OF 4', 'title' => 'How should we contact you?', 'description' => 'Just enough information for a Waggies team member to reach you.'],
                4 => ['eyebrow' => 'STEP 4 OF 4', 'title' => 'Review your request', 'description' => 'Check the details before sending. You can edit any section.'],
            ][$step];
        @endphp

        <div class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-12">
            <div class="min-w-0">
                <div class="mb-6 flex flex-col gap-2">
                    <p class="text-eyebrow text-primary">{{ $stepHeadings['eyebrow'] }}</p>
                    <h2 id="booking-form-title" data-booking-step-heading tabindex="-1" class="font-serif text-2xl font-bold text-primary-dark focus:outline-none sm:text-3xl">{{ $stepHeadings['title'] }}</h2>
                    <p class="max-w-2xl text-sm leading-relaxed text-primary-dark/60">{{ $stepHeadings['description'] }}</p>
                    <p class="max-w-2xl text-sm font-medium leading-relaxed text-primary-dark/75">This is a request, not a confirmed booking. It does not reserve a slot or confirm an appointment; we will confirm availability with you.</p>
                </div>

                <nav class="mb-6" aria-label="Request progress">
                    <ol class="grid grid-cols-4 gap-2 sm:gap-4">
                @foreach(['Service & schedule', 'Your pet(s)', 'Contact', 'Review'] as $progressIndex => $label)
                    @php
                        $progressStep = $progressIndex + 1;
                    @endphp
                    <li class="min-w-0">
                        <div class="flex items-center">
                            @if($step > $progressStep)
                                <button type="button" wire:click="goToStep({{ $progressStep }})" aria-label="Edit {{ $label }}" class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary text-white transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                                    <x-waggies.icon name="check" size="16" />
                                </button>
                            @elseif($step === $progressStep)
                                <span aria-current="step" class="flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-secondary text-sm font-bold text-primary-dark">{{ $progressStep }}</span>
                            @else
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full border border-primary/20 bg-white text-sm font-semibold text-primary-dark/45">{{ $progressStep }}</span>
                            @endif
                            @if($progressStep < 4)
                                <span class="mx-2 h-px flex-1 bg-primary/15 sm:mx-3" aria-hidden="true"></span>
                            @endif
                        </div>
                        <span class="mt-2 block text-xs font-semibold leading-tight {{ $step >= $progressStep ? 'text-primary-dark' : 'text-primary-dark/45' }}">{{ $label }}</span>
                    </li>
                @endforeach
                    </ol>
                </nav>

                <details class="group mb-6 rounded-xl border border-primary/10 bg-surface-purple/40 p-4 lg:hidden">
                    <summary class="items-center justify-between gap-4 text-sm font-semibold text-primary-dark">
                        <span class="min-w-0">
                            <span class="text-label block text-primary-dark/55">YOUR REQUEST</span>
                            <span class="mt-1 block truncate">{{ $this->serviceSummary($services[0] ?? []) }}</span>
                        </span>
                        <span class="shrink-0 text-sm font-semibold text-primary underline decoration-primary/30 underline-offset-4">View details</span>
                    </summary>
                    <div class="mt-5 flex flex-col gap-4 border-t border-primary/10 pt-4 text-sm">
                        @foreach($services as $service)
                            <div>
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-primary-dark">Service and schedule</p>
                                    <button type="button" wire:click="goToStep(1)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                                </div>
                                <p class="mt-1 text-primary-dark/70">{{ $this->serviceSummary($service) }}</p>
                                <p class="mt-1 text-primary-dark/60">{{ $this->scheduleSummary($service) ?: 'Date, time, and location not added yet' }}</p>
                            </div>
                        @endforeach
                        <div class="border-t border-primary/10 pt-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-primary-dark">Pets</p>
                                <button type="button" wire:click="goToStep(2)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                            </div>
                            <ul class="mt-1 space-y-1 text-primary-dark/70">
                                @foreach($pets as $pet)
                                    <li>{{ $pet['name'] ?: 'Pet '.($loop->iteration) }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="border-t border-primary/10 pt-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-primary-dark">Contact</p>
                                <button type="button" wire:click="goToStep(3)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                            </div>
                            <p class="mt-1 text-primary-dark/70">{{ $contact['name'] ?: 'Contact details not added yet' }}</p>
                        </div>
                    </div>
                </details>

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-error/30 bg-error-light px-4 py-4 text-sm text-error" role="alert" tabindex="-1" data-booking-error-summary>
                        <h3 class="font-semibold">There is a problem with your request</h3>
                        <ul class="mt-2 list-disc space-y-1 pl-5">
                            @foreach($errors->toArray() as $errorKey => $messages)
                                <li><a class="underline hover:no-underline" href="#{{ $this->errorAnchor($errorKey) }}">{{ $messages[0] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit="submit" aria-labelledby="booking-form-title" class="flex flex-col gap-7" novalidate>
            <input type="hidden" wire:model.live="step" value="{{ $step }}" aria-hidden="true" tabindex="-1">
            <p data-booking-draft-status class="text-xs text-primary-dark/50" aria-live="polite">Changes save automatically on this device during this visit.</p>

            @if($step === 1)
                <fieldset class="flex flex-col gap-7">
                    <legend class="sr-only">Service and schedule</legend>

                    @foreach($services as $index => $service)
                        @php
                            $variantOptions = BookingRequestSchema::variantOptions($service['service_key'] ?? null);
                            $tierOptions = BookingRequestSchema::tierOptions($service['service_key'] ?? null, $service['service_variant'] ?? null);
                            $fields = BookingRequestSchema::serviceFields((string) ($service['service_key'] ?? ''));
                            $commonFieldKeys = ['requested_date', 'requested_time', 'location'];
                            $optionalFields = array_values(array_filter($fields, static fn (array $field): bool => ! in_array($field['key'], $commonFieldKeys, true) && ! $field['required']));
                        @endphp

                        <div wire:key="booking-service-{{ $index }}" class="flex flex-col gap-6 border-b border-primary/10 pb-7 last:border-b-0 last:pb-0">
                            <details class="group">
                                <summary class="items-center justify-between gap-4 rounded-xl border border-primary/10 bg-surface-purple px-4 py-3">
                                    <span class="min-w-0">
                                        <span class="text-label block text-primary-dark/55">Service {{ $index + 1 }}</span>
                                        <span class="mt-1 block truncate text-sm font-bold text-primary-dark">{{ $this->serviceSummary($service) }}</span>
                                    </span>
                                    <span class="shrink-0 text-sm font-semibold text-primary underline decoration-primary/30 underline-offset-4">Edit service</span>
                                </summary>
                                <div class="mt-4 grid grid-cols-1 gap-5 rounded-xl border border-primary/10 bg-surface p-4 sm:grid-cols-2">
                                    <x-waggies.select id="booking-{{ $index }}-service_key" label="Service needed" data-booking-draft-model="services.{{ $index }}.service_key" wire:change="serviceChanged({{ $index }}, $event.target.value)" :error="$errors->first('services.'.$index.'.service_key')" required>
                                        <option value="" @selected(empty($service['service_key']))>Choose a service</option>
                                        @foreach(BookingRequestSchema::serviceOptions() as $key => $label)
                                            <option value="{{ $key }}" @selected(($service['service_key'] ?? null) === $key)>{{ $label }}</option>
                                        @endforeach
                                    </x-waggies.select>
                                    @if($variantOptions)
                                        <x-waggies.select id="booking-{{ $index }}-service_variant" label="Pet group or service variant" data-booking-draft-model="services.{{ $index }}.service_variant" wire:change="variantChanged({{ $index }}, $event.target.value)" :error="$errors->first('services.'.$index.'.service_variant')" required>
                                            <option value="" @selected(empty($service['service_variant']))>Choose an option</option>
                                            @foreach($variantOptions as $key => $label)
                                                <option value="{{ $key }}" @selected(($service['service_variant'] ?? null) === $key)>{{ $label }}</option>
                                            @endforeach
                                        </x-waggies.select>
                                    @endif
                                    @if($tierOptions)
                                        <x-waggies.select id="booking-{{ $index }}-pricing_tier" label="Package or plan" data-booking-draft-model="services.{{ $index }}.pricing_tier" wire:change="tierChanged({{ $index }}, $event.target.value)" :error="$errors->first('services.'.$index.'.pricing_tier')" required>
                                            <option value="" @selected(empty($service['pricing_tier']))>Choose an option</option>
                                            @foreach($tierOptions as $key => $label)
                                                <option value="{{ $key }}" @selected(($service['pricing_tier'] ?? null) === $key)>{{ $label }}</option>
                                            @endforeach
                                        </x-waggies.select>
                                    @endif
                                </div>
                            </details>

                            <div>
                                <h3 class="font-serif text-xl font-bold text-primary-dark">When and where?</h3>
                                <p class="mt-1 text-sm leading-relaxed text-primary-dark/60">Choose a preferred time and tell us where the service will happen. We confirm availability after review.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                @foreach($fields as $field)
                                    @if(in_array($field['key'], $commonFieldKeys, true) || $field['required'])
                                        @php
                                            $isServiceValue = in_array($field['key'], $commonFieldKeys, true);
                                            $model = $isServiceValue ? "services.{$index}.{$field['key']}" : "services.{$index}.details.{$field['key']}";
                                            $fieldId = 'booking-'.$index.'-'.$field['key'];
                                        @endphp
                                        @if($field['type'] === 'textarea')
                                            <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['placeholder'] ?? null" :error="$errors->first($model)" :required="$field['required']" class="sm:col-span-2">
                                                <textarea id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" rows="4" maxlength="2000" class="contact-input"></textarea>
                                            </x-waggies.field>
                                        @elseif($field['type'] === 'select')
                                            <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model.live="{{ $model }}" :error="$errors->first($model)" :required="$field['required']">
                                                <option value="">Choose an option</option>
                                                @foreach($field['options'] as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </x-waggies.select>
                                        @else
                                            <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['help'] ?? null" :error="$errors->first($model)" :required="$field['required']">
                                                <input id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" type="{{ $field['type'] }}" @if($field['type'] === 'date') min="{{ $minimumDate }}" @endif @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="contact-input">
                                            </x-waggies.field>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                            @if($optionalFields !== [])
                                <details class="rounded-xl bg-surface-purple/55 p-4">
                                    <summary class="items-center justify-between gap-4 text-sm font-semibold text-primary-dark">
                                        <span>More about this service</span>
                                        <span class="text-xs font-medium text-primary-dark/55">Optional</span>
                                    </summary>
                                    <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                        @foreach($optionalFields as $field)
                                            @php
                                                $model = "services.{$index}.details.{$field['key']}";
                                                $fieldId = 'booking-'.$index.'-'.$field['key'];
                                            @endphp
                                            @if($field['type'] === 'textarea')
                                                <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['placeholder'] ?? null" :error="$errors->first($model)" class="sm:col-span-2">
                                                    <textarea id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" rows="4" maxlength="2000" class="contact-input"></textarea>
                                                </x-waggies.field>
                                            @elseif($field['type'] === 'select')
                                                <x-waggies.select :id="$fieldId" :label="$field['label']" wire:model.live="{{ $model }}" :error="$errors->first($model)">
                                                    <option value="">Choose an option</option>
                                                    @foreach($field['options'] as $key => $label)
                                                        <option value="{{ $key }}">{{ $label }}</option>
                                                    @endforeach
                                                </x-waggies.select>
                                            @else
                                                <x-waggies.field :id="$fieldId" :label="$field['label']" :help="$field['help'] ?? null" :error="$errors->first($model)">
                                                    <input id="{{ $fieldId }}" wire:model.live.blur="{{ $model }}" type="{{ $field['type'] }}" @if(isset($field['placeholder'])) placeholder="{{ $field['placeholder'] }}" @endif class="contact-input">
                                                </x-waggies.field>
                                            @endif
                                        @endforeach
                                    </div>
                                </details>
                            @endif

                            @if(count($services) > 1)
                                <button type="button" wire:click="removeService({{ $index }})" class="min-h-11 w-fit rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove this service</button>
                            @endif
                        </div>
                    @endforeach

                    <button type="button" wire:click="addService" class="inline-flex min-h-11 w-fit items-center gap-2 rounded-lg border border-primary/20 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span aria-hidden="true">+</span> Add another service
                    </button>
                </fieldset>
                @endif

            @if($step === 2)
                <fieldset class="flex flex-col gap-6">
                    <legend class="sr-only">Pet details</legend>

                    @foreach($pets as $index => $pet)
                        <div wire:key="booking-pet-{{ $index }}" class="flex flex-col gap-5 border-b border-primary/10 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <h3 class="font-serif text-xl font-bold text-primary-dark">{{ $pet['name'] ? 'Tell us more about '.$pet['name'] : 'Tell us about your pet' }}</h3>
                                    <p class="mt-1 text-sm text-primary-dark/60">{{ $pet['name'] ?: 'New pet' }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</p>
                                </div>
                                @if(count($pets) > 1)
                                    <button type="button" wire:click="removePet({{ $index }})" class="min-h-11 shrink-0 rounded-lg px-3 text-sm font-semibold text-primary-dark/70 underline decoration-primary/30 underline-offset-4 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">Remove</button>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <x-waggies.field id="booking-pet-{{ $index }}-name" label="Pet name" :error="$errors->first('pets.'.$index.'.name')" required>
                                    <input id="booking-pet-{{ $index }}-name" wire:model.live.blur="pets.{{ $index }}.name" type="text" maxlength="80" autocomplete="off" class="contact-input">
                                </x-waggies.field>
                                <x-waggies.select id="booking-pet-{{ $index }}-species" label="Pet type" wire:model.live="pets.{{ $index }}.species" :error="$errors->first('pets.'.$index.'.species')" required>
                                    <option value="">Choose a type</option>
                                    @foreach(['dog' => 'Dog', 'cat' => 'Cat', 'bird' => 'Bird', 'rabbit' => 'Rabbit', 'reptile' => 'Reptile', 'other' => 'Other'] as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </x-waggies.select>
                            </div>

                            <details class="rounded-xl bg-surface-purple/55 p-4">
                                <summary class="items-center justify-between gap-4 text-sm font-semibold text-primary-dark">
                                    <span>More about {{ $pet['name'] ?: 'this pet' }}</span>
                                    <span class="text-xs font-medium text-primary-dark/55">Optional</span>
                                </summary>
                                <div class="mt-5 grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <x-waggies.field id="booking-pet-{{ $index }}-breed" label="Breed" :error="$errors->first('pets.'.$index.'.breed')" help="Optional">
                                        <input id="booking-pet-{{ $index }}-breed" wire:model.live.blur="pets.{{ $index }}.breed" type="text" maxlength="120" class="contact-input">
                                    </x-waggies.field>
                                    <x-waggies.field id="booking-pet-{{ $index }}-age" label="Age or life stage" :error="$errors->first('pets.'.$index.'.age')" help="Optional">
                                        <input id="booking-pet-{{ $index }}-age" wire:model.live.blur="pets.{{ $index }}.age" type="text" maxlength="40" placeholder="For example: 3 years" class="contact-input">
                                    </x-waggies.field>
                                    <x-waggies.select id="booking-pet-{{ $index }}-sex" label="Sex" wire:model.live="pets.{{ $index }}.sex" :error="$errors->first('pets.'.$index.'.sex')">
                                        <option value="">Not specified</option>
                                        <option value="female">Female</option>
                                        <option value="male">Male</option>
                                        <option value="unknown">Prefer not to say</option>
                                    </x-waggies.select>
                                    <x-waggies.field id="booking-pet-{{ $index }}-notes" label="Pet notes" :error="$errors->first('pets.'.$index.'.notes')" help="Optional. Share temperament, routines, or care notes." class="sm:col-span-2">
                                        <textarea id="booking-pet-{{ $index }}-notes" wire:model.live.blur="pets.{{ $index }}.notes" rows="3" maxlength="1000" class="contact-input resize-y"></textarea>
                                    </x-waggies.field>
                                </div>
                            </details>
                        </div>
                    @endforeach

                    <button type="button" wire:click="addPet" class="inline-flex min-h-11 w-fit items-center gap-2 rounded-lg border border-primary/20 px-4 text-sm font-semibold text-primary-dark hover:border-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        <span aria-hidden="true">+</span> Add another pet
                    </button>
                </fieldset>
            @endif

            @if($step === 3)
                <fieldset class="flex max-w-xl flex-col gap-5">
                    <legend class="sr-only">Contact information</legend>
                    <x-waggies.field id="booking-contact-name" label="Your name" :error="$errors->first('contact.name')" required>
                        <input id="booking-contact-name" wire:model.live.blur="contact.name" type="text" autocomplete="name" maxlength="120" class="contact-input">
                    </x-waggies.field>
                    <x-waggies.field id="booking-contact-email" label="Email address" :error="$errors->first('contact.email')" required>
                        <input id="booking-contact-email" wire:model.live.blur="contact.email" type="email" autocomplete="email" maxlength="255" class="contact-input">
                    </x-waggies.field>
                    <x-waggies.field id="booking-contact-phone" label="Phone or WhatsApp number" :error="$errors->first('contact.phone')" required>
                        <input id="booking-contact-phone" wire:model.live.blur="contact.phone" type="tel" autocomplete="tel" maxlength="40" class="contact-input">
                    </x-waggies.field>
                    <x-waggies.select id="booking-contact-method" label="Preferred contact method" wire:model.live="contact.preferred_contact_method" :error="$errors->first('contact.preferred_contact_method')">
                        <option value="">No preference</option>
                        <option value="phone">Phone</option>
                        <option value="email">Email</option>
                        <option value="whatsapp">WhatsApp</option>
                    </x-waggies.select>
                </fieldset>
            @endif

            @if($step === 4)
                <section class="flex flex-col gap-6" aria-labelledby="booking-review-heading">
                    <h3 id="booking-review-heading" class="sr-only">Review your request</h3>

                    <div class="divide-y divide-primary/10 rounded-xl border border-primary/10 bg-surface">
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h4 class="font-semibold text-primary-dark">Service and schedule</h4><button type="button" wire:click="goToStep(1)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <div class="mt-3 flex flex-col gap-3 text-sm text-primary-dark/70">
                                @foreach($services as $service)
                                    <div>
                                        <p class="font-semibold text-primary-dark">{{ $this->serviceSummary($service) }}</p>
                                        <p class="mt-1">{{ $service['requested_date'] ?: 'Date not added' }}@if($service['requested_time']) · {{ $service['requested_time'] }}@endif @if($service['location']) · {{ $service['location'] }}@endif</p>
                                        @foreach($service['details'] ?? [] as $key => $value)
                                            @if($value)
                                                <p class="mt-1"><span class="font-medium text-primary-dark">{{ Str::headline($key) }}:</span> {{ $value }}</p>
                                            @endif
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h4 class="font-semibold text-primary-dark">Pets</h4><button type="button" wire:click="goToStep(2)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <ul class="mt-3 space-y-3 text-sm text-primary-dark/70">
                                @foreach($pets as $pet)
                                    <li>
                                        <p class="font-semibold text-primary-dark">{{ $pet['name'] ?: 'Unnamed pet' }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</p>
                                        @if($pet['breed'] || $pet['age'] || $pet['sex'] || $pet['notes'])
                                            <p class="mt-1">{{ implode(' · ', array_filter([$pet['breed'], $pet['age'], $pet['sex'] ? ucfirst($pet['sex']) : null])) }}@if($pet['notes'])<span class="block">{{ $pet['notes'] }}</span>@endif</p>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-4"><h4 class="font-semibold text-primary-dark">Contact</h4><button type="button" wire:click="goToStep(3)" class="text-sm font-semibold text-primary underline underline-offset-4">Edit</button></div>
                            <p class="mt-3 text-sm text-primary-dark/70">{{ $contact['name'] ?: 'Name not added' }} · {{ $contact['email'] ?: 'Email not added' }} · {{ $contact['phone'] ?: 'Phone not added' }}</p>
                            @if($contact['preferred_contact_method'])
                                <p class="mt-1 text-sm text-primary-dark/60">Preferred contact: {{ ucfirst($contact['preferred_contact_method']) }}</p>
                            @endif
                        </div>
                    </div>
                </section>
            @endif

            <div class="flex flex-col gap-3 border-t border-primary/10 pt-6 sm:flex-row sm:items-center sm:justify-between">
                <p class="max-w-sm text-xs leading-relaxed text-primary-dark/50">Submitting sends a request to Waggies. It does not reserve a slot or confirm an appointment.</p>
                <div class="flex flex-col-reverse gap-3 sm:flex-row">
                    @if($step > 1)
                        <x-waggies.button type="button" variant="secondary" wire:click="previousStep" wire:loading.attr="disabled" wire:target="previousStep" class="w-full sm:w-auto">Back</x-waggies.button>
                    @endif
                    @if($step < 4)
                        <x-waggies.button type="button" wire:click="nextStep" wire:loading.attr="disabled" wire:target="nextStep" class="w-full sm:w-auto">Continue <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                    @else
                        <x-waggies.button type="submit" wire:loading.attr="disabled" wire:target="submit" class="w-full sm:w-auto">
                            <span wire:loading.remove wire:target="submit">Send request</span>
                            <span wire:loading wire:target="submit">Sending request...</span>
                            <x-waggies.icon name="arrow-forward" size="16" />
                        </x-waggies.button>
                    @endif
                </div>
            </div>
                </form>
            </div>

            <aside class="hidden min-w-0 flex-col gap-5 lg:sticky lg:top-24 lg:flex" aria-label="Your request and booking reassurance">
                <section class="rounded-2xl border border-primary/10 bg-surface-purple/40 p-5" aria-labelledby="booking-summary-heading">
                    <div class="flex items-start justify-between gap-4">
                        <h3 id="booking-summary-heading" class="font-serif text-xl font-bold text-primary-dark">Your request</h3>
                        <span class="text-label shrink-0 text-primary-dark/50">STEP {{ $step }} OF 4</span>
                    </div>
                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">Nothing is charged yet. We will confirm availability with you.</p>

                    <div class="mt-5 divide-y divide-primary/10 text-sm">
                        @foreach($services as $service)
                            <div class="py-4 first:pt-0">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-primary-dark">Service and schedule</p>
                                    <button type="button" wire:click="goToStep(1)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                                </div>
                                <p class="mt-1 font-medium text-primary-dark/80">{{ $this->serviceSummary($service) }}</p>
                                <p class="mt-1 text-primary-dark/60">{{ $this->scheduleSummary($service) ?: 'Date, time, and location not added yet' }}</p>
                            </div>
                        @endforeach

                        <div class="py-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-primary-dark">Pets</p>
                                <button type="button" wire:click="goToStep(2)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                            </div>
                            <ul class="mt-1 space-y-1 text-primary-dark/60">
                                @foreach($pets as $pet)
                                    <li>{{ $pet['name'] ?: 'Pet '.($loop->iteration) }} · {{ $this->petSpeciesLabel($pet['species'] ?? null) }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="pt-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-primary-dark">Contact</p>
                                <button type="button" wire:click="goToStep(3)" class="shrink-0 text-xs font-semibold text-primary underline underline-offset-4">Edit</button>
                            </div>
                            <p class="mt-1 text-primary-dark/60">{{ $contact['name'] ?: 'Contact details not added yet' }}</p>
                        </div>
                    </div>
                </section>

                <section class="rounded-2xl bg-primary-dark p-5 text-white shadow-sm" aria-labelledby="booking-next-heading">
                    <p id="booking-next-heading" class="text-eyebrow mb-3 text-secondary">WHAT HAPPENS NEXT</p>
                    <p class="mb-4 text-sm leading-relaxed text-white/80">Share the essentials and we will confirm the details with you.</p>
                    <ol class="flex flex-col gap-3">
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">1</span><span class="pt-1 text-sm leading-relaxed text-white/80">We receive and review your request.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">2</span><span class="pt-1 text-sm leading-relaxed text-white/80">Our team checks the details and gets in touch.</span></li>
                        <li class="flex items-start gap-3"><span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-secondary text-xs font-bold text-primary-dark">3</span><span class="pt-1 text-sm leading-relaxed text-white/80">We confirm the service arrangements with you.</span></li>
                    </ol>
                </section>

                <section class="rounded-2xl border border-primary/10 bg-white p-5" aria-labelledby="booking-whatsapp-heading">
                    <div class="mb-3 flex h-9 w-9 items-center justify-center rounded-xl bg-surface-purple text-primary"><x-waggies.brand-icon name="whatsapp" size="18" /></div>
                    <h3 id="booking-whatsapp-heading" class="font-serif text-xl font-bold text-primary-dark">Prefer to talk now?</h3>
                    <p class="mt-2 text-sm leading-relaxed text-primary-dark/60">After sending your request, you can continue the conversation on WhatsApp.</p>
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex min-h-11 items-center gap-2 text-sm font-semibold text-primary hover:text-primary-dark">Open WhatsApp <x-waggies.icon name="arrow-forward" size="16" /></a>
                </section>
            </aside>
        </div>
    @endif
</div>
