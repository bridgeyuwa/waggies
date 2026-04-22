<x-layouts.minimal title="Down for Maintenance">

    <div class="text-center py-8">
        <p class="font-serif text-8xl font-bold text-primary/15 leading-none mb-6">503</p>
        <h1 class="font-serif text-3xl font-bold text-primary-dark mb-4">Down for Maintenance</h1>
        <p class="text-primary-dark/60 leading-relaxed mb-4">
            We're performing scheduled maintenance to improve your experience.
            We'll be back very shortly.
        </p>
        @if(isset($exception) && $exception->getMessage())
            <p class="text-sm text-primary-dark/40 mb-8">{{ $exception->getMessage() }}</p>
        @else
            <p class="text-sm text-primary-dark/40 mb-8">Thank you for your patience.</p>
        @endif
        <div class="inline-flex items-center gap-2 bg-surface-purple text-primary-dark/60 px-6 py-3 rounded-full text-sm font-medium">
            <span class="material-symbols-outlined text-base text-primary">schedule</span>
            Please check back soon
        </div>
    </div>

</x-layouts.minimal>
