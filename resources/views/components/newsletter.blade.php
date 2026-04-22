{{--
    Newsletter subscribe widget.

    Props:
      $title       — heading text
      $subtitle    — subheading text
      $placeholder — email input placeholder
      $buttonLabel — submit button text
--}}
@props([
    'title'       => 'Join the Waggies Pack',
    'subtitle'    => 'Get pet care tips, exclusive offers and Waggies news delivered weekly.',
    'placeholder' => 'your@email.com',
    'buttonLabel' => 'Subscribe',
])

<div x-data="{
    email: '',
    clientError: '',
    subscribed: {{ session('newsletter_success') ? 'true' : 'false' }},
    isValidEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) },
    validate() {
        if (!this.email)                    { this.clientError = 'Please enter your email address.'; return false; }
        if (!this.isValidEmail(this.email)) { this.clientError = 'Please enter a valid email address.'; return false; }
        this.clientError = '';
        return true;
    },
}" class="flex flex-col gap-3">

    @if($title)
        <p class="font-serif text-lg font-bold text-primary-dark">{{ $title }}</p>
    @endif
    @if($subtitle)
        <p class="text-sm text-primary-dark/50">{{ $subtitle }}</p>
    @endif

    <div x-show="!subscribed">
        <form method="POST" action="{{ route('newsletter.subscribe') }}" @submit.prevent="validate() && $el.submit()">
            @csrf
            <div class="flex flex-col gap-3">
                <input type="email"
                       name="email"
                       x-model="email"
                       :class="{ 'border-red-400': clientError || {{ $errors->has('email') ? 'true' : 'false' }} }"
                       placeholder="{{ $placeholder }}"
                       class="w-full h-12 px-5 rounded-2xl border border-primary/30 bg-white/50
                              font-medium text-primary-dark placeholder:text-primary-dark/50
                              focus:outline-none focus:ring-2 focus:ring-primary/60 focus:border-primary transition"
                       aria-label="Email address" />

                <p x-show="clientError" x-text="clientError"
                   class="text-red-500 text-xs font-medium" role="alert"></p>

                @error('email')
                    <p class="text-red-500 text-xs font-medium">{{ $message }}</p>
                @enderror

                <button type="submit"
                        class="w-full bg-primary hover:bg-primary-dark text-white py-3 rounded-full
                               font-semibold transition shadow-glow hover:-translate-y-0.5
                               focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                    {{ $buttonLabel }}
                </button>
            </div>
        </form>
    </div>

    <div x-show="subscribed"
         class="flex items-center gap-2 text-green-600 text-sm font-semibold py-1"
         style="{{ session('newsletter_success') ? '' : 'display:none' }}">
        <span class="material-symbols-outlined text-base icon-filled" aria-hidden="true">check_circle</span>
        <span>You're subscribed — welcome to the pack!</span>
    </div>

</div>
