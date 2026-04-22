{{--
    Textarea input.

    Props:
      $label    — visible label text
      $name     — textarea name + id
      $rows     — number of visible rows. Default: 4
      $placeholder
      $value    — pre-filled value
      $error    — validation error string
      $required — boolean
--}}
@props([
    'label'       => '',
    'name'        => '',
    'rows'        => 4,
    'placeholder' => '',
    'value'       => null,
    'error'       => null,
    'required'    => false,
])

<div class="flex flex-col gap-1.5">

    @if($label)
        <label for="{{ $name }}"
               class="text-sm font-semibold text-primary-dark {{ $required ? 'after:content-[\'*\'] after:text-error after:ml-0.5' : '' }}">
            {{ $label }}
        </label>
    @endif

    <textarea
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if($required) required aria-required="true" @endif
        @if($error) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->class([
            'w-full px-5 py-4 rounded-2xl border bg-surface/50 resize-none outline-none font-medium text-primary-dark',
            'placeholder:text-primary-dark/50',
            'focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary transition',
            'border-primary/30' => !$error,
            'border-error bg-error-light' => $error,
        ]) }}
    >{{ old($name, $value) }}</textarea>

    @if($error)
        <div id="{{ $name }}-error"
             class="flex items-center gap-2 text-error text-xs font-medium"
             role="alert">
            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
            {{ $error }}
        </div>
    @endif

</div>
