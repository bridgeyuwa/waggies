{{--
    Text / email / tel / password input.

    Props:
      $label       — visible label text
      $name        — input name + id
      $type        — text | email | tel | password | number. Default: text
      $placeholder — placeholder text
      $value       — pre-filled value (old() is merged automatically)
      $error       — validation error string
      $required    — boolean
      $hint        — optional helper text below the field
--}}
@props([
    'label'       => '',
    'name'        => '',
    'type'        => 'text',
    'placeholder' => '',
    'value'       => null,
    'error'       => null,
    'required'    => false,
    'hint'        => null,
])

<div class="flex flex-col gap-1.5">

    @if($label)
        <label for="{{ $name }}"
               class="text-sm font-semibold text-primary-dark {{ $required ? 'after:content-[\'*\'] after:text-error after:ml-0.5' : '' }}">
            {{ $label }}
        </label>
    @endif

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        @if($required) required aria-required="true" @endif
        @if($error) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->class([
            'w-full h-14 px-5 rounded-2xl border bg-surface/50 outline-none font-medium text-primary-dark',
            'placeholder:text-primary-dark/50',
            'focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary transition',
            'border-primary/30' => !$error,
            'border-error bg-error-light' => $error,
        ]) }}
    />

    @if($error)
        <div id="{{ $name }}-error"
             class="flex items-center gap-2 text-error text-xs font-medium"
             role="alert">
            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
            {{ $error }}
        </div>
    @elseif($hint)
        <p class="text-xs text-primary-dark/50">{{ $hint }}</p>
    @endif

</div>
