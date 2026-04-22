{{--
    Radio card group (B20). CSS-only checked state via sibling selector.
    Used in the relocation doc checklist generator.

    Props:
      $name    — radio input group name
      $options — array of ['value', 'label', 'sublabel', 'icon'] where icon is a Material Symbol name
      $selected — pre-selected value
--}}
@props([
    'name'     => 'radio-group',
    'options'  => [],
    'selected' => null,
])

<div class="flex flex-wrap gap-3" role="radiogroup">
    @foreach($options as $option)
        <label class="cursor-pointer">
            <input type="radio"
                   name="{{ $name }}"
                   value="{{ $option['value'] }}"
                   class="radio-card-input sr-only"
                   {{ old($name, $selected) === $option['value'] ? 'checked' : '' }} />
            <div class="radio-card border border-primary/30 rounded-2xl p-4 w-36 transition-colors relative
                        hover:border-primary/50 hover:bg-surface-purple/50">
                <span class="check-icon absolute top-2 right-2 text-primary material-symbols-outlined icon-filled
                             text-sm opacity-0 scale-0 transition-all duration-200"
                      aria-hidden="true">check_circle</span>
                <span class="material-symbols-outlined text-primary text-2xl mb-2 block" aria-hidden="true">
                    {{ $option['icon'] ?? 'radio_button_unchecked' }}
                </span>
                <p class="text-sm font-semibold text-primary-dark">{{ $option['label'] }}</p>
                @if(!empty($option['sublabel']))
                    <p class="text-xs text-primary-dark/50 mt-0.5">{{ $option['sublabel'] }}</p>
                @endif
            </div>
        </label>
    @endforeach
</div>
