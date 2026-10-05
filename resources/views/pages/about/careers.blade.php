@extends('layouts.app')

@section('content')
    <x-waggies.breadcrumb-strip :items="[['label' => 'About', 'route' => 'about'], ['label' => 'Careers']]" class="border-b border-primary/5 bg-white" />
    <x-waggies.cover-hero :hero="$hero" />

    <section class="border-b border-primary/10 bg-white" aria-labelledby="careers-intro-title">
        <div class="page-container py-16 md:py-20">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_20rem] lg:items-end lg:gap-20">
                <div class="max-w-3xl">
                    <span class="text-eyebrow mb-4 block">Open roles</span>
                    <h2 id="careers-intro-title" class="text-h2 text-balance text-primary-dark">Current opportunities at Waggies</h2>
                    <p class="mt-5 max-w-2xl text-body text-pretty">Explore the roles currently available with the Waggies pet care team in Abuja. Select a role below to read the full description and requirements.</p>
                </div>

                <div class="border-t border-primary/12 pt-5 lg:border-l lg:border-t-0 lg:pl-8 lg:pt-0">
                    <p class="text-sm leading-relaxed text-primary-dark/60">Have a question about careers at Waggies?</p>
                    <a href="{{ route('contact', ['intent' => 'careers', 'source' => 'careers-page']) }}" class="mt-3 inline-flex min-h-11 items-center gap-2 font-semibold text-primary transition-colors hover:text-primary-dark focus-visible:underline">
                        Talk to Waggies
                        <x-waggies.icon name="arrow-forward" size="17" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="open-roles" class="section-pad bg-surface" aria-labelledby="open-roles-title">
        <div class="page-container">
            <x-waggies.section-heading
                eyebrow="Open roles"
                title="Current opportunities"
                subtitle="Read the role details and requirements, then use the careers contact flow to start a conversation."
                align="left"
                spacing="mb-10"
            />

            <div class="grid gap-6 xl:grid-cols-2">
                @forelse($openRoles as $role)
                    @php
                        $dialogId = 'job-role-dialog-'.$role->getKey();
                        $titleId = $dialogId.'-title';
                        $descriptionId = $dialogId.'-description';
                        $metadata = [
                            'Department' => $role->department,
                            'Employment type' => $role->employment_type,
                            'Location' => $role->location,
                        ];
                        $metadataLine = collect($metadata)->filter()->implode(' · ');
                    @endphp

                    <div x-data="waggiesJobDetails" class="h-full">
                        <article class="group flex h-full flex-col overflow-hidden rounded-xl border border-primary/12 bg-white shadow-sm transition-[border-color,box-shadow,transform] duration-200 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-soft">
                            <div class="flex flex-1 flex-col p-6 sm:p-7">
                                <div class="flex items-start gap-4">
                                    <span class="flex size-12 shrink-0 items-center justify-center rounded-xl bg-surface-purple text-primary" aria-hidden="true">
                                        <x-waggies.icon name="career" size="24" />
                                    </span>
                                    <div class="min-w-0">
                                        <h3 class="font-serif text-2xl font-bold leading-tight text-primary-dark">{{ $role->title }}</h3>
                                        @if($metadataLine)
                                            <p class="mt-2 text-sm leading-relaxed text-primary-dark/55">{{ $metadataLine }}</p>
                                        @endif
                                    </div>
                                </div>

                                @if(collect($metadata)->filter()->isNotEmpty())
                                    <dl class="mt-6 grid gap-4 border-y border-primary/10 py-4 sm:grid-cols-3 sm:gap-5">
                                        @foreach($metadata as $label => $value)
                                            @if(filled($value))
                                                <div>
                                                    <dt class="text-[0.68rem] font-bold uppercase tracking-[0.14em] text-primary-dark/45">{{ $label }}</dt>
                                                    <dd class="mt-1 text-sm font-semibold leading-snug text-primary-dark">{{ $value }}</dd>
                                                </div>
                                            @endif
                                        @endforeach
                                    </dl>
                                @endif

                                @if($role->summary)
                                    <p class="mt-6 max-w-2xl text-sm leading-7 text-primary-dark/65">{{ $role->summary }}</p>
                                @endif

                                <div class="mt-8 flex flex-wrap items-center justify-between gap-4 border-t border-primary/10 pt-5">
                                    <span class="text-sm font-semibold text-primary-dark/50">Role details</span>
                                    <x-waggies.button
                                        type="button"
                                        variant="secondary"
                                        class="w-full justify-between sm:w-auto"
                                        aria-controls="{{ $dialogId }}"
                                        aria-expanded="false"
                                        @click="openDialog($event.currentTarget)"
                                    >
                                        View role details
                                        <x-waggies.icon name="arrow-forward" size="17" />
                                    </x-waggies.button>
                                </div>
                            </div>
                        </article>

                        <div
                            x-ref="dialog"
                            x-cloak
                            x-show="open"
                            x-transition.opacity
                            id="{{ $dialogId }}"
                            role="dialog"
                            aria-modal="true"
                            aria-labelledby="{{ $titleId }}"
                            @if(filled($role->description)) aria-describedby="{{ $descriptionId }}" @endif
                            :aria-hidden="(!open).toString()"
                            tabindex="-1"
                            @click.self="close()"
                            @keydown="handleDialogKeydown($event)"
                            class="fixed inset-0 z-layer-lightbox overflow-y-auto bg-primary-dark/60 p-4 sm:p-8"
                        >
                            <div @click.self="close()" class="flex min-h-full items-start justify-center sm:items-center">
                                <article @click.stop x-show="open" x-transition class="my-0 flex max-h-[calc(100dvh-2rem)] w-full max-w-3xl flex-col overflow-hidden rounded-2xl border border-primary/10 bg-white shadow-modal sm:my-8 sm:max-h-[calc(100dvh-4rem)]">
                                    <header class="flex shrink-0 items-start justify-between gap-5 border-b border-primary/10 bg-surface px-5 py-5 sm:px-8 sm:py-7">
                                        <div class="min-w-0">
                                            <p class="text-eyebrow mb-3">Role details</p>
                                            <h2 id="{{ $titleId }}" class="font-serif text-3xl font-bold leading-tight text-primary-dark sm:text-4xl">{{ $role->title }}</h2>
                                            @if($metadataLine)
                                                <p class="mt-3 text-sm leading-relaxed text-primary-dark/60">{{ $metadataLine }}</p>
                                            @endif
                                        </div>

                                        <button x-ref="closeButton" type="button" @click="close()" aria-label="Close role details" class="flex size-11 shrink-0 items-center justify-center rounded-lg text-primary-dark/60 transition-colors hover:bg-surface-purple hover:text-primary-dark">
                                            <x-waggies.icon name="close" size="20" />
                                        </button>
                                    </header>

                                    <div class="min-h-0 flex-1 overflow-y-auto px-5 py-6 sm:px-8 sm:py-8">
                                        <div class="flex flex-col gap-8">
                                            @if($role->description)
                                                <section aria-labelledby="{{ $dialogId }}-about-title">
                                                    <h3 id="{{ $dialogId }}-about-title" class="font-serif text-2xl font-bold text-primary-dark">About the role</h3>
                                                    <p id="{{ $descriptionId }}" class="mt-3 whitespace-pre-line text-body leading-7">{{ $role->description }}</p>
                                                </section>
                                            @endif

                                            @if($role->requirements)
                                                <section aria-labelledby="{{ $dialogId }}-requirements-title">
                                                    <h3 id="{{ $dialogId }}-requirements-title" class="font-serif text-2xl font-bold text-primary-dark">Requirements</h3>
                                                    <p class="mt-3 whitespace-pre-line text-body leading-7">{{ $role->requirements }}</p>
                                                </section>
                                            @endif
                                        </div>
                                    </div>

                                    <footer class="flex shrink-0 flex-col gap-4 border-t border-primary/10 bg-surface px-5 py-5 sm:flex-row sm:items-center sm:justify-between sm:px-8">
                                        <p class="text-sm leading-relaxed text-primary-dark/60">Have a question about this role?</p>
                                        <x-waggies.button href="{{ route('contact', ['intent' => 'careers', 'source' => 'job-opening']) }}" class="w-full sm:w-auto">
                                            Ask about this role
                                            <x-waggies.icon name="arrow-forward" size="17" />
                                        </x-waggies.button>
                                    </footer>
                                </article>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="rounded-xl border border-dashed border-primary/20 bg-white px-6 py-10 text-center sm:px-10 sm:py-12 xl:col-span-2">
                        <x-waggies.icon name="career" size="32" class="mx-auto text-primary/50" />
                        <h3 class="mt-4 font-serif text-2xl font-bold text-primary-dark">No current openings</h3>
                        <p class="mx-auto mt-3 max-w-xl text-sm leading-7 text-primary-dark/60">There are no active roles to share right now. Check back later or contact Waggies to continue the conversation.</p>
                        <x-waggies.button href="{{ route('contact', ['intent' => 'careers', 'source' => 'careers-page']) }}" variant="secondary" class="mt-6">
                            Contact Waggies
                            <x-waggies.icon name="arrow-forward" size="17" />
                        </x-waggies.button>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
@endsection
