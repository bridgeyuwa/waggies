@props([
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'plain' => false,
])

@php
    $controlId = $id ?: ($attributes->get('name') ? 'field-'.$attributes->get('name') : null);
    $helpId = $help && $controlId ? $controlId.'-help' : null;
    $errorId = $error && $controlId ? $controlId.'-error' : null;
    $describedBy = collect([$helpId, $errorId])->filter()->join(' ');
    $isLivewireSelect = $attributes->whereStartsWith('wire:')->isNotEmpty();
    $useEnhancedLivewireSelect = $isLivewireSelect && ! $plain;
    $nativeControlId = $useEnhancedLivewireSelect && $controlId ? $controlId.'-native' : $controlId;
    $selectClasses = ['contact-input'];

    if ($useEnhancedLivewireSelect) {
        $selectClasses[] = 'sr-only';
    }
@endphp

<x-waggies.field :id="$controlId" :label="$label" :help="$help" :error="$error" :required="$required">
    <div
        @if($useEnhancedLivewireSelect) x-data="waggiesLivewireSelect" data-waggies-livewire-select @endif
        class="relative w-full"
    >
        <select
            @if($nativeControlId) id="{{ $nativeControlId }}" @endif
            @if($useEnhancedLivewireSelect) x-ref="native" aria-hidden="true" tabindex="-1" @endif
            @if($required) required @endif
            @if($error) aria-invalid="true" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            {{ $attributes->class($selectClasses) }}
        >
            {{ $slot }}
        </select>

        @if($useEnhancedLivewireSelect)
            <div wire:ignore class="relative w-full">
                <button
                    @if($controlId) id="{{ $controlId }}" aria-controls="{{ $controlId }}-listbox" @endif
                    type="button"
                    x-ref="trigger"
                    role="combobox"
                    aria-haspopup="listbox"
                    aria-autocomplete="none"
                    @if($required) aria-required="true" @endif
                    @if($error) aria-invalid="true" @endif
                    @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
                    :aria-expanded="open"
                    :disabled="isDisabled"
                    @click="toggle()"
                    @keydown.arrow-down.prevent="openMenu(1)"
                    @keydown.arrow-up.prevent="openMenu(-1)"
                    @keydown.enter.prevent="toggle()"
                    @keydown.space.prevent="toggle()"
                    @keydown.escape.prevent="close()"
                    class="waggies-select-trigger flex h-[44px] min-h-[44px] w-full items-center justify-between gap-2 rounded-md border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-primary-dark shadow-none transition-[color,box-shadow] focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <span x-text="selectedLabel" :class="isPlaceholder ? 'text-primary-dark/45' : 'text-primary-dark'" class="min-w-0 flex-1 truncate"></span>
                    <img src="{{ asset('icons/material-symbols/outlined/keyboard_arrow_down.svg') }}" alt="" class="h-4 w-4 shrink-0 opacity-50">
                </button>

                <template x-teleport="body">
                    <div
                        id="{{ $controlId ? $controlId.'-listbox' : '' }}"
                        x-ref="listbox"
                        x-show="open"
                        x-cloak
                        role="listbox"
                        tabindex="-1"
                        data-state="closed"
                        data-side="bottom"
                        @keydown="onListboxKeydown($event)"
                        class="waggies-select-options fixed z-layer-navigation min-w-32 overflow-x-hidden overflow-y-auto rounded-lg border border-primary/15 bg-white p-1 shadow-lg outline-none"
                    >
                        <template x-for="(option, index) in options" :key="option.value">
                            <button
                                type="button"
                                role="option"
                                :aria-selected="option.value === selectedValue"
                                :aria-disabled="option.disabled ? 'true' : 'false'"
                                :tabindex="option.disabled ? -1 : 0"
                                :data-state="option.value === selectedValue ? 'checked' : 'unchecked'"
                                :class="{ 'bg-surface-purple': highlightedIndex === index, 'pointer-events-none cursor-not-allowed opacity-45': option.disabled }"
                                @click="choose(option.value)"
                                @mouseenter="highlightedIndex = index"
                                class="relative flex min-h-[44px] w-full cursor-default items-center gap-2 rounded-md py-3 pl-3 pr-9 text-left text-sm text-primary-dark outline-none select-none hover:bg-surface-purple focus:bg-surface-purple"
                            >
                                <span x-text="option.label"></span>
                                <img x-show="option.value === selectedValue" src="{{ asset('icons/material-symbols/outlined/check.svg') }}" alt="" class="pointer-events-none absolute right-3 h-4 w-4">
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        @endif
    </div>
</x-waggies.field>
