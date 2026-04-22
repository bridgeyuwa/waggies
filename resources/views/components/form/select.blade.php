{{--
    Select input with custom chevron overlay.

    Props:
      $label    — visible label text
      $name     — select name + id
      $options  — array of ['value' => '', 'label' => ''] or flat ['value' => 'label']
      $selected — currently selected value
      $error    — validation error string
      $required — boolean
      $placeholder — disabled first option text
--}}
@props([
    'label'       => '',
    'name'        => '',
    'options'     => [],
    'selected'    => null,
    'error'       => null,
    'required'    => false,
    'placeholder' => 'Select an option',
])

<div class="flex flex-col gap-1.5">

    @if($label)
        <label for="{{ $name }}"
               class="text-sm font-semibold text-primary-dark {{ $required ? 'after:content-[\'*\'] after:text-error after:ml-0.5' : '' }}">
            {{ $label }}
        </label>
    @endif

    <div class="relative">
        <select
            id="{{ $name }}"
            name="{{ $name }}"
            @if($required) required aria-required="true" @endif
            @if($error) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
            {{ $attributes->class([
                'w-full h-14 px-5 rounded-2xl border bg-surface/50 outline-none font-medium text-primary-dark appearance-none cursor-pointer',
                'focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary transition',
                'border-primary/30' => !$error,
                'border-error bg-error-light' => $error,
            ]) }}
        >
            <option value="" disabled {{ old($name, $selected) === null ? 'selected' : '' }}>
                {{ $placeholder }}
            </option>
            @foreach($options as $value => $label)
                @php
                    [$optValue, $optLabel] = is_array($label)
                        ? [$label['value'], $label['label']]
                        : [$value, $label];
                @endphp
                <option value="{{ $optValue }}" {{ old($name, $selected) == $optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-primary pointer-events-none"
              aria-hidden="true">expand_more</span>
    </div>

    @if($error)
        <div id="{{ $name }}-error"
             class="flex items-center gap-2 text-error text-xs font-medium"
             role="alert">
            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
            {{ $error }}
        </div>
    @endif

</div>
