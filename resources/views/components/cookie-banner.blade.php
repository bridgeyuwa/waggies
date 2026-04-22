<div
    x-data="{
        show: false,
        init() {
            if (!localStorage.getItem('waggies-cookies')) {
                setTimeout(() => this.show = true, 50);
            }
        },
        accept(type) {
            localStorage.setItem('waggies-cookies', type);
            this.show = false;
        },
    }"
    x-show="show"
    x-transition:enter="transition duration-500 ease-out"
    x-transition:enter-start="translate-y-full"
    x-transition:enter-end="translate-y-0"
    x-transition:leave="transition duration-300 ease-in"
    x-transition:leave-start="translate-y-0"
    x-transition:leave-end="translate-y-full"
    class="fixed bottom-0 left-0 right-0 z-[9998] px-4 py-3 md:px-8 md:py-4"
    style="display:none"
>
    <div class="max-w-5xl mx-auto bg-primary-dark rounded-2xl shadow-2xl px-6 py-4
                flex flex-col sm:flex-row items-start sm:items-center gap-4">
        <div class="flex items-start gap-3 flex-1">
            <span class="material-symbols-outlined text-secondary text-2xl shrink-0 mt-0.5">cookie</span>
            <p class="text-white/80 text-sm leading-relaxed">
                We use cookies to personalise content, improve your experience, and analyse our traffic.
                <a href="{{ route('cookies') }}" class="text-secondary underline hover:text-secondary-hover transition-colors">Learn more</a>
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button @click="accept('essential')"
                    class="border border-white/30 text-white px-4 py-2 rounded-full text-xs font-semibold
                           hover:bg-white/10 transition-colors focus:outline-none focus:ring-2 focus:ring-white/40">
                Essential only
            </button>
            <button @click="accept('all')"
                    class="bg-secondary text-primary-dark px-4 py-2 rounded-full text-xs font-semibold
                           hover:bg-secondary-hover transition-colors focus:outline-none focus:ring-2 focus:ring-secondary/60">
                Accept All
            </button>
        </div>
    </div>
</div>
