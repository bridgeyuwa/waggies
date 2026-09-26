@props([
    'step',
    'labels',
])

<nav class="mb-8" aria-label="Request progress">
    <div class="mb-3 flex items-center justify-between gap-4">
        <p class="text-xs leading-relaxed text-primary-dark/55">
            {{ $step > 1 ? 'Completed steps can be edited.' : 'You can revisit completed steps before sending your request.' }}
        </p>
        <span class="hidden text-xs font-semibold text-primary-dark/45 sm:inline">Step {{ $step }} of {{ count($labels) }}</span>
    </div>
    <ol class="grid grid-cols-5 gap-1.5 sm:gap-3">
        @foreach($labels as $progressIndex => $label)
            @php $progressStep = $progressIndex + 1; @endphp
            <li class="min-w-0">
                <div class="flex items-center gap-1.5 sm:gap-2">
                    @if($step > $progressStep)
                        <button type="button" wire:click="goToStep({{ $progressStep }})" aria-label="Edit {{ $label }} step" class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary text-white transition-colors hover:bg-primary-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                            <x-waggies.icon name="check" size="16" />
                        </button>
                    @elseif($step === $progressStep)
                        <span aria-current="step" class="flex size-9 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-secondary text-sm font-bold text-primary-dark">{{ $progressStep }}</span>
                    @else
                        <span aria-disabled="true" class="flex size-9 shrink-0 items-center justify-center rounded-full border border-primary/20 bg-white text-sm font-semibold text-primary-dark/45">{{ $progressStep }}</span>
                    @endif
                    @if($progressStep < count($labels))
                        <span class="h-px min-w-1 flex-1 bg-primary/15" aria-hidden="true"></span>
                    @endif
                </div>
                @if($step > $progressStep)
                    <button type="button" wire:click="goToStep({{ $progressStep }})" class="mt-2 block max-w-full truncate text-left text-[0.68rem] font-semibold leading-tight text-primary-dark underline decoration-primary/30 underline-offset-2 hover:text-primary focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary">
                        {{ $label }} <span class="font-normal text-primary/70">· Edit</span>
                    </button>
                @else
                    <span class="mt-2 block truncate text-[0.68rem] font-semibold leading-tight {{ $step === $progressStep ? 'text-primary-dark' : 'text-primary-dark/45' }}">{{ $label }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
