<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="--font-dm-sans: 'DM Sans'; --font-cormorant-garamond: 'Cormorant Garamond';">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @head($headStatus ?? null)
        @stack('head')
        {{-- Livewire's bundled ESM export provides Alpine for the public bundle; this guard prevents its automatic Livewire boot. --}}
        <script>window.livewireScriptConfig = window.livewireScriptConfig ?? {};</script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-surface text-primary-dark antialiased" data-contact-url="{{ route('contact') }}" data-contact-enquiry-url="{{ route('contact-enquiries.store') }}" data-search-url="{{ route('search') }}" data-assistant-url="{{ route('assistant.store') }}" data-boarding-url="{{ route('services.boarding') }}" data-grooming-url="{{ route('services.grooming') }}" data-pricing-url="{{ route('services.pricing') }}" data-vet-care-url="{{ route('services.vet-care') }}" data-training-url="{{ route('services.training') }}" data-relocation-hub="{{ route('services.relocation') }}" data-relocation-transport="{{ route('relocation.transport') }}">
        <div data-navigation-progress hidden class="navigation-progress" role="status" aria-label="Loading page"></div>
        <x-waggies.skip-link />
        <x-waggies.navbar :nav-section="$navSection ?? ''" />

        <main id="main-content" class="min-h-[calc(100vh-4rem)] pb-16 md:pb-0">
            @yield('content')
        </main>

        <x-waggies.footer />
        <x-waggies.floating-actions />
        <x-waggies.mobile-bottom-nav />

        <div id="global-search-dialog" x-data="waggiesSearch" x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-layer-search bg-primary-dark/40 p-4 sm:p-8" role="dialog" aria-modal="true" aria-labelledby="search-title" :aria-hidden="!open" @click.self="close()" @keydown="handleDialogKeydown($event)">
            <div class="mx-auto mt-12 max-w-2xl overflow-hidden rounded-2xl border border-primary/10 bg-white shadow-2xl" @click.stop>
                <div class="relative flex items-center gap-3 border-b border-surface-purple p-4"><x-waggies.icon name="search" size="20"/><label id="search-title" class="sr-only" for="global-search">Search query</label><input id="global-search" x-ref="input" x-model="query" @input.debounce.150ms="fetchResults()" aria-autocomplete="list" aria-controls="search-results-list" autocomplete="off" class="min-w-0 flex-1 border-0 text-lg focus:outline-none" placeholder="Search services, articles, products, FAQs…"><button type="button" @click="close()" aria-label="Close search" class="flex h-11 w-11 items-center justify-center rounded-full hover:bg-surface-purple"><x-waggies.icon name="close" size="20"/></button></div>
                <div x-show="!query && !loading" class="p-4"><p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-primary-dark/50">Popular</p><div class="flex flex-wrap gap-2"><template x-for="term in ['Grooming', 'Vet Care', 'Boarding prices', 'Dog training', 'Pet relocation', 'Vaccination']" :key="term"><button type="button" class="rounded-xl bg-surface-purple px-3 py-2 text-sm font-medium text-primary-dark hover:bg-primary hover:text-white" x-text="term" @click="query = term; fetchResults()"></button></template></div></div>
                <div x-show="loading" class="p-8 text-center text-sm text-primary-dark/60">Searching…</div>
                <ul id="search-results-list" x-show="!loading && results.length" class="max-h-[60vh] space-y-1 overflow-y-auto p-2" role="listbox" aria-label="Search results"><template x-for="result in results" :key="result.id"><li role="option"><button type="button" class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left hover:bg-surface-purple" @click="navigate(result.href)"><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-primary/15 text-primary"><x-waggies.icon name="search" size="18"/></span><span class="min-w-0 flex-1"><span class="block truncate font-medium text-primary-dark" x-text="result.title"></span><span class="block line-clamp-1 text-sm text-primary-dark/60" x-text="result.description"></span></span><span class="shrink-0 rounded-full bg-primary/10 px-2 py-0.5 text-[10px] font-bold uppercase text-primary" x-text="result.category"></span></button></li></template></ul>
                <div x-show="query && !loading && !results.length" class="px-6 py-12 text-center"><div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-surface-purple"><x-waggies.icon name="search" size="24" class="text-primary-dark/60"/></div><p class="font-medium text-primary-dark">No results for “<span x-text="query"></span>”</p><p class="mt-1 text-sm text-primary-dark/60">Try a different keyword, or jump to a popular page.</p></div>
            </div>
        </div>

        <div id="global-cart-dialog" x-data="waggiesCart" x-cloak x-show="open" x-transition.opacity class="fixed inset-0 z-layer-cart bg-primary-dark/40" role="dialog" aria-modal="true" aria-labelledby="cart-title" :aria-hidden="!open" @click.self="close()" @keydown="handleDialogKeydown($event)">
            <aside class="absolute right-0 top-0 flex h-full w-full max-w-md flex-col bg-white shadow-2xl">
                <div class="border-b border-primary/5 px-6 pb-4 pt-6">
                    <div class="flex items-center justify-between"><h2 id="cart-title" class="font-serif text-xl font-bold text-primary-dark">Saved for enquiry</h2><button type="button" x-ref="closeButton" @click="close()" aria-label="Close saved list" class="flex h-11 w-11 items-center justify-center rounded-lg hover:bg-surface-purple"><x-waggies.icon name="close" size="20"/></button></div>
                    <p class="mt-1 text-sm text-primary-dark/50" x-text="items.length === 0 ? 'Your saved list is empty' : `${items.length} item${items.length === 1 ? '' : 's'} saved for enquiry`"></p>
                </div>
                <div x-show="items.length === 0" class="flex flex-1 flex-col items-center justify-center gap-4 px-6">
                    <x-waggies.icon name="shopping-cart" size="48" class="text-primary-dark/15"/><p class="text-center text-sm text-primary-dark/60">No products saved yet. Browse the catalogue to find something for your pet.</p><a href="{{ route('shop.index') }}" @click="close()" class="mt-2 inline-flex items-center gap-1.5 text-sm font-semibold text-primary hover:text-primary-dark"><x-waggies.icon name="arrow-forward" size="16"/>Browse catalogue</a>
                </div>
                <template x-if="items.length > 0">
                    <div class="flex min-h-0 flex-1 flex-col">
                        <div class="flex flex-1 flex-col gap-4 overflow-y-auto px-6 py-4">
                            <template x-for="item in items" :key="item.productId">
                                <div class="flex gap-4 rounded-xl bg-surface p-3">
                                    <img :src="item.imageSrc" :alt="item.name" class="h-16 w-16 shrink-0 rounded-lg object-cover" />
                                    <div class="flex min-w-0 flex-1 flex-col gap-2">
                                        <p class="line-clamp-2 text-sm font-semibold leading-snug text-primary-dark" x-text="item.name"></p>
                                        <p class="text-sm font-bold text-primary-dark" x-text="formatPrice(item.price)"></p>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center overflow-hidden rounded-full border border-primary/10">
                                                <button type="button" @click="updateQuantity(item.productId, item.quantity - 1)" aria-label="Decrease quantity" class="flex h-11 w-11 items-center justify-center text-primary-dark transition-colors hover:bg-surface-purple focus:outline-none"><x-waggies.icon name="remove" size="14"/></button>
                                                <span class="w-8 select-none text-center text-sm font-semibold text-primary-dark" x-text="item.quantity"></span>
                                                <button type="button" @click="updateQuantity(item.productId, item.quantity + 1)" aria-label="Increase quantity" class="flex h-11 w-11 items-center justify-center text-primary-dark transition-colors hover:bg-surface-purple focus:outline-none"><x-waggies.icon name="add" size="14"/></button>
                                            </div>
                            <button type="button" @click="removeItem(item.productId)" :aria-label="`Remove ${item.name} from saved list`" class="rounded-lg p-3 text-primary-dark/60 transition-colors hover:text-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/60"><x-waggies.icon name="delete" size="16"/></button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div class="flex flex-col gap-4 border-t border-primary/5 px-6 py-5">
                            <div class="flex items-center justify-between"><span class="text-sm font-medium text-primary-dark/60">Subtotal</span><span class="text-lg font-bold text-primary-dark" x-text="formatPrice(subtotal())"></span></div>
                            <p class="text-xs text-primary-dark/60">Availability and final pricing confirmed by Waggies.</p>
                            <x-waggies.button href="{{ route('contact', ['intent' => 'cart-order']) }}" @click="close()" class="w-full">Ask about these products</x-waggies.button>
                            <button type="button" @click="clearCart()" class="w-full py-1 text-center text-xs font-medium text-primary-dark/60 transition-colors hover:text-primary-dark/70">Clear saved list</button>
                        </div>
                    </div>
                </template>
            </aside>
        </div>

        <div x-data="waggiesToasts" class="fixed right-4 top-20 z-layer-toast space-y-2" aria-live="polite"><template x-for="toast in items" :key="toast.id"><div class="rounded-xl bg-primary-dark px-4 py-3 text-sm text-white shadow-lg" x-text="toast.message"></div></template></div>
        @stack('scripts')
    </body>
</html>
