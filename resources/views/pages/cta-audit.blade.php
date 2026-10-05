@extends('layouts.app')

@php
    $primitiveHref = route('book');
    $heroActions = [
        ['label' => 'Submit Booking Request', 'href' => route('book')],
        ['label' => 'View Services', 'href' => route('services.index'), 'iconBefore' => 'pets'],
    ];
    $sharedActions = [
        ['label' => 'Primary action', 'href' => route('book'), 'iconAfter' => 'arrow-forward'],
        ['label' => 'Secondary action', 'href' => route('contact'), 'variant' => 'secondary', 'iconBefore' => 'arrow-back'],
        ['label' => 'Tertiary link', 'href' => route('faq'), 'variant' => 'link', 'iconAfter' => 'arrow-forward', 'iconSize' => 16],
    ];
    $centeredDarkCta = [
        'heading' => 'Ready to care for your pet?',
        'headingAccent' => 'with clarity',
        'body' => 'A centered CTA with a strong next step and a quieter alternative.',
        'primaryLabel' => 'Request a booking',
        'primaryHref' => route('book'),
        'secondaryLabel' => 'View services',
        'secondaryHref' => route('services.index'),
        'secondaryIcon' => 'arrow-back',
    ];
    $centeredLightCta = [
        'heading' => 'Need a little guidance?',
        'body' => 'The light version keeps the same action contract on a soft surface.',
        'primaryLabel' => 'Contact Waggies',
        'primaryHref' => route('contact'),
        'secondaryLabel' => 'Read FAQs',
        'secondaryHref' => route('faq'),
        'secondaryIcon' => 'arrow-back',
    ];
    $splitCta = [
        'eyebrow' => 'A calmer next step',
        'heading' => 'Make a clear request',
        'headingAccent' => 'for your pet',
        'body' => 'The split CTA gives the message more room while keeping the actions together.',
        'primaryLabel' => 'Start a request',
        'primaryHref' => route('book'),
        'secondaryLabel' => 'Contact the team',
        'secondaryHref' => route('contact'),
        'secondaryIcon' => 'arrow-back',
    ];
    $featureBand = [
        'eyebrow' => 'Parent module CTA',
        'title' => 'Care that feels considered',
        'subtitle' => 'This specimen shows the CTA embedded inside the reusable feature-band layout.',
        'cta' => ['label' => 'Explore services', 'route' => 'services.index'],
        'features' => [
            ['icon' => 'verified', 'title' => 'Clear next steps', 'description' => 'The action remains attached to the content it follows.'],
            ['icon' => 'favorite', 'title' => 'Warm support', 'description' => 'Supporting detail can sit beside the primary action.'],
        ],
    ];
@endphp

@section('content')
    <section class="border-b border-primary/10 bg-primary-dark text-white">
        <div class="page-container py-16 sm:py-20">
            <p class="text-eyebrow text-secondary">LOCAL-ONLY REVIEW SURFACE</p>
            <h1 class="mt-3 max-w-3xl font-serif text-4xl font-bold leading-tight text-white sm:text-5xl">CTA Review Lab</h1>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-white/70 sm:text-lg">Every specimen below uses the current Waggies CTA components. Compare the labels, hierarchy, spacing, icon treatment, and responsive behavior before deciding what to redesign.</p>
            <p class="mt-5 inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.12em] text-white/70">Temporary route · /__test/ctas</p>
        </div>
    </section>

    <section class="bg-surface py-12 sm:py-16">
        <div class="page-container">
            <div class="mb-8 max-w-2xl">
                <p class="text-eyebrow text-primary/60">01 · PRIMITIVE</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Button variants and states</h2>
                <p class="mt-3 text-sm leading-relaxed text-primary-dark/65">These are the smallest reusable CTA building blocks. The labels identify the production component contract and the visual variant being reviewed.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Primary</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Default commitment action.</p>
                    <x-waggies.button href="{{ $primitiveHref }}" class="mt-5">Request a booking <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Secondary</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Quieter alternative action.</p>
                    <x-waggies.button href="{{ route('contact') }}" variant="secondary" class="mt-5"><x-waggies.icon name="arrow-back" size="16" />Contact Waggies</x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Secondary / light</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">The quieter action on a light surface.</p>
                    <x-waggies.button href="{{ route('services.index') }}" variant="secondary" class="mt-5">View services <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Link / tertiary</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Low-emphasis contextual link.</p>
                    <x-waggies.button href="{{ route('faq') }}" variant="link" class="mt-5 text-sm">Read FAQs <x-waggies.icon name="arrow-forward" size="16" /></x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Compact</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Small size contract for dense contexts.</p>
                    <x-waggies.button href="{{ route('contact') }}" size="sm" class="mt-5">Ask a question</x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">External / icon before</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">External handoff with a leading service icon.</p>
                    <x-waggies.button href="{{ $businessProfile->whatsapp_url }}" target="_blank" rel="noopener noreferrer" variant="secondary" class="mt-5"><x-waggies.brand-icon name="whatsapp" size="16" />Continue on WhatsApp</x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Disabled</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Unavailable state with preserved semantics.</p>
                    <x-waggies.button :disabled="true" class="mt-5">Unavailable</x-waggies.button>
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 shadow-sm">
                    <p class="text-eyebrow text-primary/55">x-waggies.button</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Loading</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">Busy state with `aria-busy`.</p>
                    <x-waggies.button :loading="true" class="mt-5">Sending request</x-waggies.button>
                </div>
            </div>
        </div>
    </section>

    <section class="border-y border-primary/10 bg-white py-12 sm:py-16">
        <div class="page-container">
            <div class="mb-8 max-w-2xl">
                <p class="text-eyebrow text-primary/60">02 · SHARED ACTION GROUP</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Normalized action rendering</h2>
                <p class="mt-3 text-sm leading-relaxed text-primary-dark/65">One action-group component can render mixed primary, secondary, and tertiary actions without repeating button markup in each parent CTA.</p>
            </div>
            <div class="rounded-2xl bg-surface-purple p-6 sm:p-8">
                <p class="text-eyebrow text-primary/55">x-waggies.cta-actions</p>
                <x-waggies.cta-actions :actions="$sharedActions" class="mt-5 justify-start" />
            </div>
        </div>
    </section>

    <section class="bg-surface py-12 sm:py-16">
        <div class="page-container space-y-8">
            <div>
                <p class="text-eyebrow text-primary/60">03 · CONTEXTUAL COMPONENTS</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Hero and centered compositions</h2>
            </div>

            <div class="rounded-2xl bg-primary-dark p-6 sm:p-10">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div><p class="text-eyebrow text-secondary">x-waggies.hero-actions</p><p class="mt-1 text-sm text-white/60">Image tone</p></div>
                    <span class="rounded-full border border-white/15 px-3 py-1 text-xs text-white/60">Primary + secondary</span>
                </div>
                <x-waggies.hero-actions :actions="$heroActions" tone="image" />
            </div>

            <div class="rounded-2xl border border-primary/10 bg-white p-6 sm:p-10">
                <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
                    <div><p class="text-eyebrow text-primary/60">x-waggies.hero-actions</p><p class="mt-1 text-sm text-primary-dark/60">Light tone</p></div>
                    <span class="rounded-full bg-surface-purple px-3 py-1 text-xs text-primary-dark/60">Primary + secondary</span>
                </div>
                <x-waggies.hero-actions :actions="$heroActions" tone="light" />
            </div>

            <div class="rounded-2xl bg-primary-dark px-6 py-10 sm:px-10 sm:py-14">
                <p class="text-center text-eyebrow text-secondary">x-waggies.cta-centered · dark</p>
                <x-waggies.cta-centered :cta="$centeredDarkCta" class="mt-3" />
            </div>

            <div class="rounded-2xl border border-primary/10 bg-surface-purple px-6 py-10 sm:px-10 sm:py-14">
                <p class="text-center text-eyebrow text-primary/60">x-waggies.cta-centered · light</p>
                <x-waggies.cta-centered :cta="$centeredLightCta" tone="light" class="mt-3" />
            </div>

            <div class="rounded-2xl bg-primary-dark px-6 py-8 sm:px-10 sm:py-10">
                <p class="text-center text-eyebrow text-secondary">x-waggies.cta-centered · small</p>
                <x-waggies.cta-centered :cta="$centeredLightCta" size="sm" class="mt-3" />
            </div>
        </div>
    </section>

    <section class="border-y border-primary/10 bg-white py-12 sm:py-16">
        <div class="page-container space-y-8">
            <div>
                <p class="text-eyebrow text-primary/60">04 · SPLIT COMPOSITION</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Message-led CTA panel</h2>
            </div>
            <div>
                <p class="mb-4 text-eyebrow text-primary/55">x-waggies.cta-split</p>
                <x-waggies.cta-split :cta="$splitCta" />
            </div>
        </div>
    </section>

    <section class="bg-surface py-12 sm:py-16">
        <div class="page-container space-y-8">
            <div>
                <p class="text-eyebrow text-primary/60">05 · SPECIALIZED GROUPS</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Domain-specific action sets</h2>
            </div>

            <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                <div class="rounded-2xl border border-primary/10 bg-white p-6 sm:p-8">
                    <p class="text-eyebrow text-primary/55">x-waggies.booking-request-success-actions</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Booking request received</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">The shared success action group used by the booking page and wizard.</p>
                    <x-waggies.booking-request-success-actions :whatsapp-url="$businessProfile->whatsapp_url" class="mt-5" />
                </div>

                <div class="rounded-2xl border border-primary/10 bg-white p-6 sm:p-8">
                    <p class="text-eyebrow text-primary/55">x-waggies.contact-shop-actions</p>
                    <h3 class="mt-2 font-serif text-xl font-bold text-primary-dark">Contact state recovery</h3>
                    <p class="mt-2 text-sm text-primary-dark/60">The repeated shop/back action group for empty contact states.</p>
                    <x-waggies.contact-shop-actions class="mt-5" />
                </div>
            </div>

            <div class="rounded-2xl bg-primary-dark py-2">
                <p class="px-6 pt-5 text-center text-eyebrow text-secondary">x-waggies.tool-cta · general</p>
                <x-waggies.tool-cta />
            </div>

            <div class="rounded-2xl bg-primary-dark py-2">
                <p class="px-6 pt-5 text-center text-eyebrow text-secondary">x-waggies.tool-cta · medical</p>
                <x-waggies.tool-cta medical />
            </div>
        </div>
    </section>

    <section class="border-y border-primary/10 bg-white py-12 sm:py-16">
        <div class="page-container space-y-8">
            <div>
                <p class="text-eyebrow text-primary/60">06 · RECOVERY CTA</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">Error-page action hierarchy</h2>
            </div>
            <div class="overflow-hidden rounded-2xl border border-primary/10">
                <p class="bg-surface px-6 pt-6 text-eyebrow text-primary/55">x-waggies.error-page</p>
                <x-waggies.error-page code="404" title="That page wandered off" description="This is the reusable recovery composition with a strong return action and a quieter support path." />
            </div>
        </div>
    </section>

    <section class="bg-surface py-12 sm:py-16">
        <div class="page-container space-y-8">
            <div>
                <p class="text-eyebrow text-primary/60">07 · EMBEDDED CTA OWNERS</p>
                <h2 class="mt-2 font-serif text-3xl font-bold text-primary-dark">CTAs inside larger modules</h2>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-primary-dark/65">These are shown for context. Their CTA markup belongs to the parent module because its layout and copy are inseparable from the surrounding content.</p>
            </div>

            <div class="overflow-hidden rounded-2xl">
                <x-waggies.feature-band :band="$featureBand" />
            </div>

        </div>
    </section>
@endsection
