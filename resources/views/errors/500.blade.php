<x-layouts.minimal title="Server Error">

    <div class="text-center py-8">
        <p class="font-serif text-8xl font-bold text-primary/15 leading-none mb-6">500</p>
        <h1 class="font-serif text-3xl font-bold text-primary-dark mb-4">Something Went Wrong</h1>
        <p class="text-primary-dark/60 leading-relaxed mb-8">
            We've encountered an unexpected error. Our team has been notified and we're looking into it.
            Please try again in a few moments.
        </p>
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition">
            <span class="material-symbols-outlined text-base">home</span> Back to Home
        </a>
    </div>

</x-layouts.minimal>
