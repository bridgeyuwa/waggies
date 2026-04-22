{{--
    Contact info block (B14). Displays address, phone, email, hours in icon + text rows.

    Props:
      $address — street address
      $phone   — phone number (displayed + href)
      $email   — email address
      $hours   — opening hours string
--}}
@props([
    'address' => '123 Wuse II, Abuja, Nigeria',
    'phone'   => '+234 800 000 0000',
    'email'   => 'hello@waggies.ng',
    'hours'   => 'Mon–Sun: 7am – 8pm',
])

<div class="flex flex-col gap-4">
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-primary text-xl shrink-0" aria-hidden="true">location_on</span>
        <span class="text-sm text-primary-dark/70">{{ $address }}</span>
    </div>
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-primary text-xl shrink-0" aria-hidden="true">call</span>
        <a href="tel:{{ preg_replace('/\s+/', '', $phone) }}"
           class="text-sm text-primary-dark/70 hover:text-primary transition-colors">
            {{ $phone }}
        </a>
    </div>
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-primary text-xl shrink-0" aria-hidden="true">mail</span>
        <a href="mailto:{{ $email }}"
           class="text-sm text-primary-dark/70 hover:text-primary transition-colors">
            {{ $email }}
        </a>
    </div>
    <div class="flex items-start gap-3">
        <span class="material-symbols-outlined text-primary text-xl shrink-0" aria-hidden="true">schedule</span>
        <span class="text-sm text-primary-dark/70">{{ $hours }}</span>
    </div>

    {{-- Slot for any additional rows --}}
    {{ $slot }}
</div>
