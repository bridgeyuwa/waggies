@props([
    'id' => null,
    'label' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'options' => [],
    'placeholder' => 'Select an option',
])

@php
    $controlId = $id ?: ($attributes->get('name') ? 'field-'.$attributes->get('name') : null);
    $helpId = $help && $controlId ? $controlId.'-help' : null;
    $errorId = $error && $controlId ? $controlId.'-error' : null;
    $describedBy = collect([$helpId, $errorId])->filter()->join(' ');
    $nativeControlId = $controlId ? $controlId.'-native' : null;
@endphp

<x-waggies.field :id="$controlId" :label="$label" :help="$help" :error="$error" :required="$required">
    <div x-data="waggiesSearchableSelect" data-placeholder="{{ $placeholder }}" class="relative w-full">
        <select
            @if($nativeControlId) id="{{ $nativeControlId }}" @endif
            x-ref="native"
            hidden
            aria-hidden="true"
            tabindex="-1"
            @if($required) required @endif
            @if($error) aria-invalid="true" @endif
            @if($describedBy) aria-describedby="{{ $describedBy }}" @endif
            {{ $attributes->class(['contact-input', 'sr-only']) }}
        >
            <option value="">{{ $placeholder }}</option>
            @foreach($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}">{{ $optionLabel }}</option>
            @endforeach
        </select>

        <div wire:ignore class="relative w-full">
            <button
                @if($controlId) id="{{ $controlId }}" aria-controls="{{ $controlId }}-listbox" @endif
                type="button"
                x-ref="trigger"
                role="combobox"
                aria-haspopup="listbox"
                aria-autocomplete="list"
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
                @keydown.escape.prevent="closeAndFocus()"
                class="waggies-select-trigger flex h-[44px] min-h-[44px] w-full items-center justify-between gap-2 rounded-2xl border border-primary/20 bg-white px-4 py-3 text-left text-sm font-medium whitespace-nowrap text-primary-dark shadow-none transition-[color,box-shadow] focus:outline-none focus-visible:border-primary focus-visible:ring-2 focus-visible:ring-primary/40 disabled:cursor-not-allowed disabled:opacity-50"
            >
                <span x-text="selectedLabel" :class="isPlaceholder ? 'text-primary-dark/45' : 'text-primary-dark'" class="min-w-0 flex-1 truncate"></span>
                <img src="{{ asset('icons/material-symbols/outlined/keyboard_arrow_down.svg') }}" alt="" class="h-4 w-4 shrink-0 opacity-50">
            </button>

            <template x-teleport="body">
                <div
                    x-ref="menu"
                    x-show="open"
                    x-cloak
                    x-on:click.outside="close()"
                    data-state="closed"
                    data-side="bottom"
                    class="waggies-select-options fixed z-layer-navigation flex min-w-56 flex-col overflow-hidden rounded-2xl border border-primary/15 bg-white p-1 shadow-lg outline-none"
                >
                    <div class="shrink-0 border-b border-primary/10 p-2">
                        <label class="sr-only" for="{{ $controlId ? $controlId.'-search' : '' }}">Search countries</label>
                        <input
                            id="{{ $controlId ? $controlId.'-search' : '' }}"
                            x-ref="search"
                            x-model="query"
                            @input="filterOptions($event.target.value)"
                            @keydown.arrow-down.prevent="moveHighlight(1)"
                            @keydown.arrow-up.prevent="moveHighlight(-1)"
                            @keydown.home.prevent="focusOption(0)"
                            @keydown.end.prevent="focusOption(filteredOptions.length - 1)"
                            @keydown.enter.prevent="chooseHighlighted()"
                            @keydown.escape.prevent="closeAndFocus()"
                            type="search"
                            autocomplete="off"
                            placeholder="Search countries…"
                            class="contact-input h-10 min-h-10 rounded-xl px-3 py-2 text-sm"
                        >
                    </div>
                    <div id="{{ $controlId ? $controlId.'-listbox' : '' }}" x-ref="listbox" role="listbox" tabindex="-1" class="min-h-0 overflow-y-auto p-1">
                        <template x-for="(option, index) in filteredOptions" :key="option.value">
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
                                @keydown="onOptionKeydown($event, index)"
                                class="relative flex min-h-[44px] w-full items-center gap-2 rounded-md px-3 py-3 text-left text-sm text-primary-dark outline-none select-none hover:bg-surface-purple focus:bg-surface-purple"
                            >
                                <span x-text="option.label"></span>
                                <img x-show="option.value === selectedValue" src="{{ asset('icons/material-symbols/outlined/check.svg') }}" alt="" class="pointer-events-none absolute right-3 h-4 w-4">
                            </button>
                        </template>
                        <p x-show="filteredOptions.length === 0" class="px-3 py-4 text-sm text-primary-dark/60">No countries found</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</x-waggies.field>
