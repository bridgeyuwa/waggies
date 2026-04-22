<x-layouts.minimal title="Page Expired">

    <div class="text-center py-8">
        <p class="font-serif text-8xl font-bold text-primary/15 leading-none mb-6">419</p>
        <h1 class="font-serif text-3xl font-bold text-primary-dark mb-4">Page Expired</h1>
        <p class="text-primary-dark/60 leading-relaxed mb-8">
            Your session has expired — this usually happens after a period of inactivity.
            Please go back and try again.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
            <button onclick="history.back()"
                    class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition cursor-pointer">
                <span class="material-symbols-outlined text-base">arrow_back</span> Go Back
            </button>
            <a href="{{ url('/') }}"
               class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">
                Home
            </a>
        </div>
    </div>

</x-layouts.minimal>
