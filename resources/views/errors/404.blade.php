<x-layouts.app title="Page Not Found">

    <div class="min-h-[60vh] flex items-center justify-center px-4 py-20">
        <div class="text-center max-w-lg">
            <p class="font-serif text-8xl md:text-9xl font-bold text-primary/15 leading-none mb-6">404</p>
            <h1 class="font-serif text-3xl md:text-4xl font-bold text-primary-dark mb-4">Page Not Found</h1>
            <p class="text-primary-dark/60 leading-relaxed mb-8">
                The page you're looking for doesn't exist or may have been moved.
                Let us help you find what you need.
            </p>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-2 bg-primary hover:bg-primary-dark text-white px-6 py-3 rounded-full font-bold transition shadow-glow hover:-translate-y-1">
                    <span class="material-symbols-outlined text-base">home</span> Back to Home
                </a>
                <a href="{{ url('/services') }}"
                   class="inline-flex items-center gap-2 border-2 border-primary text-primary px-6 py-3 rounded-full font-semibold hover:bg-primary hover:text-white transition">
                    Our Services
                </a>
                <a href="{{ url('/contact') }}"
                   class="inline-flex items-center gap-2 border-2 border-primary/30 text-primary-dark/60 px-6 py-3 rounded-full font-semibold hover:border-primary hover:text-primary transition">
                    Contact Us
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
