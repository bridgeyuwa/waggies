@props(['navSection' => ''])

<div
    x-data="{
        drawerOpen: false,
        activeAcc: null,
        openDrawer()  { this.drawerOpen = true; },
        closeDrawer() { this.drawerOpen = false; this.activeAcc = null; },
    }"
    x-init="$watch('drawerOpen', v => document.body.style.overflow = v ? 'hidden' : '')"
    @keydown.escape.window="if (drawerOpen) closeDrawer()"
>

{{-- ── Desktop header ──────────────────────────────────────────── --}}
<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-surface-purple shadow-sm">
    <nav class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 h-16 flex items-center justify-between"
         aria-label="Main navigation">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" aria-label="Waggies — home">
            <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center shadow-glow">
                <span class="material-symbols-outlined text-white text-lg icon-filled">pets</span>
            </div>
            <span class="font-serif text-xl font-bold text-primary-dark tracking-tight">Waggies</span>
        </a>

        {{-- Desktop nav --}}
        <ul class="hidden lg:flex items-center gap-1" role="list">

            {{-- Services --}}
            <li class="nav-item relative"
                x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false"
                @focusin="open = true"   @focusout="open = false">
                <button type="button"
                        :aria-expanded="open.toString()"
                        aria-haspopup="true"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ $navSection === 'services' ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if($navSection === 'services') aria-current="page" @endif>
                    Services
                    <span class="material-symbols-outlined text-sm chevron">expand_more</span>
                </button>
                <div class="mega-panel mega-panel--wide absolute top-full mt-1 bg-white rounded-2xl shadow-soft border border-surface-purple overflow-hidden"
                     role="region" aria-label="Services menu">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-3">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Our Services</p>
                    </div>
                    <div class="p-4 grid grid-cols-3 gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">Boarding</p>
                            <x-navbar.mega-link href="{{ route('services.boarding.index') }}" icon="apartment"    title="Luxury Boarding"  subtitle="Premium overnight stays" />
                            <x-navbar.mega-link href="{{ route('services.boarding.dogs') }}"  icon="cruelty_free" title="Dog Boarding"     subtitle="Tailored for your dog" />
                            <x-navbar.mega-link href="{{ route('services.boarding.cats') }}"  icon="emoticon"     title="Cat Boarding"     subtitle="Calm feline retreat" />
                            <x-navbar.mega-link href="{{ route('services.boarding.exotic') }}" icon="bug_report"  title="Exotic Pets"      subtitle="Specialist care" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">Wellness</p>
                            <x-navbar.mega-link href="{{ route('services.grooming') }}" icon="spa"              title="Grooming Spa"  subtitle="Breed-specific treatments" />
                            <x-navbar.mega-link href="{{ route('services.vet-care') }}" icon="medical_services" title="Vet Care"      subtitle="On-site veterinary support" />
                            <x-navbar.mega-link href="{{ route('services.training') }}" icon="school"           title="Dog Training"  subtitle="Positive-reinforcement methods" />
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">More</p>
                            <x-navbar.mega-link href="{{ route('services.transport') }}" icon="local_shipping" title="Local Transport"   subtitle="Door-to-door pickup" />
                            <x-navbar.mega-link href="{{ route('services.pricing') }}"   icon="payments"       title="Pricing"           subtitle="Transparent, honest rates" />
                            <x-navbar.mega-link href="{{ route('loyalty') }}"             icon="loyalty"        title="Loyalty Programme" subtitle="Rewards for regulars" />
                        </div>
                    </div>
                </div>
            </li>

            {{-- About --}}
            <li class="nav-item relative"
                x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false"
                @focusin="open = true"   @focusout="open = false">
                <button type="button"
                        :aria-expanded="open.toString()"
                        aria-haspopup="true"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ $navSection === 'about' ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if($navSection === 'about') aria-current="page" @endif>
                    About
                    <span class="material-symbols-outlined text-sm chevron">expand_more</span>
                </button>
                <div class="mega-panel mega-panel--center absolute top-full mt-1 bg-white rounded-2xl shadow-soft border border-surface-purple overflow-hidden"
                     role="region" aria-label="About menu">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-3">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">About Waggies</p>
                    </div>
                    <div class="p-4 flex flex-col gap-1">
                        <x-navbar.mega-link href="{{ route('about.index') }}"        icon="info"          title="About Us"       subtitle="Our story and mission" />
                        <x-navbar.mega-link href="{{ route('about.testimonials') }}" icon="reviews"       title="Testimonials"   subtitle="What pet owners say" />
                        <x-navbar.mega-link href="{{ route('about.gallery') }}"      icon="photo_library" title="Gallery"        subtitle="Moments at Waggies" />
                        <x-navbar.mega-link href="{{ route('about.careers') }}"      icon="work"          title="Careers"        subtitle="Join our team" />
                        <x-navbar.mega-link href="{{ route('about.partnerships') }}" icon="handshake"     title="Partnerships"   subtitle="Grow with us" />
                    </div>
                </div>
            </li>

            {{-- Relocation --}}
            <li class="nav-item relative"
                x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false"
                @focusin="open = true"   @focusout="open = false">
                <button type="button"
                        :aria-expanded="open.toString()"
                        aria-haspopup="true"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ $navSection === 'relocation' ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if($navSection === 'relocation') aria-current="page" @endif>
                    Relocation
                    <span class="material-symbols-outlined text-sm chevron">expand_more</span>
                </button>
                <div class="mega-panel mega-panel--center absolute top-full mt-1 bg-white rounded-2xl shadow-soft border border-surface-purple overflow-hidden"
                     role="region" aria-label="Relocation menu">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-3">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Pet Relocation</p>
                    </div>
                    <div class="p-4 flex flex-col gap-1">
                        <x-navbar.mega-link href="{{ route('relocation.index') }}"    icon="public"         title="Relocation Overview" subtitle="International pet moves" />
                        <x-navbar.mega-link href="{{ route('relocation.import') }}"   icon="flight_land"    title="Pet Import"          subtitle="Bringing pets into Nigeria" />
                        <x-navbar.mega-link href="{{ route('relocation.export') }}"   icon="flight_takeoff" title="Pet Export"           subtitle="Moving pets abroad" />
                        <x-navbar.mega-link href="{{ route('relocation.checklist') }}" icon="checklist"     title="Doc Checklist"        subtitle="Generate your document list" />
                    </div>
                </div>
            </li>

            {{-- Resources --}}
            <li class="nav-item relative"
                x-data="{ open: false }"
                @mouseenter="open = true" @mouseleave="open = false"
                @focusin="open = true"   @focusout="open = false">
                <button type="button"
                        :aria-expanded="open.toString()"
                        aria-haspopup="true"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ in_array($navSection, ['blog','guides','kb']) ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if(in_array($navSection, ['blog','guides','kb'])) aria-current="page" @endif>
                    Resources
                    <span class="material-symbols-outlined text-sm chevron">expand_more</span>
                </button>
                <div class="mega-panel mega-panel--right absolute top-full mt-1 bg-white rounded-2xl shadow-soft border border-surface-purple overflow-hidden"
                     role="region" aria-label="Resources menu">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-6 py-3">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Resources</p>
                    </div>
                    <div class="p-4 flex flex-col gap-1">
                        <x-navbar.mega-link href="{{ route('blog.index') }}"   icon="article"     title="Blog"           subtitle="Tips, news &amp; stories" />
                        <x-navbar.mega-link href="{{ route('guides.index') }}" icon="menu_book"   title="Guides"         subtitle="In-depth how-to guides" />
                        <x-navbar.mega-link href="{{ route('kb.index') }}"     icon="help_center" title="Knowledge Base"  subtitle="Answers to common questions" />
                        <x-navbar.mega-link href="{{ route('faq') }}"          icon="quiz"        title="FAQ"            subtitle="Quick answers" />
                    </div>
                </div>
            </li>

            {{-- Shop --}}
            <li>
                <a href="{{ route('shop.index') }}"
                   class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                          {{ $navSection === 'shop' ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                   @if($navSection === 'shop') aria-current="page" @endif>
                    Shop
                </a>
            </li>

        </ul>

        {{-- Desktop CTA + Hamburger --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('contact') }}"
               class="hidden lg:flex items-center gap-2 bg-primary hover:bg-primary-dark text-white
                      px-6 py-2.5 rounded-full text-sm font-semibold transition shadow-glow hover:-translate-y-1
                      focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
                Get a Quote
                <span class="material-symbols-outlined text-base">arrow_forward</span>
            </a>

            <button type="button"
                    @click="drawerOpen ? closeDrawer() : openDrawer()"
                    :aria-expanded="drawerOpen.toString()"
                    aria-controls="mobile-drawer"
                    aria-label="Open navigation menu"
                    class="lg:hidden flex flex-col gap-[5px] p-2 rounded-lg hover:bg-surface-purple transition-colors">
                <span :class="{ 'translate-y-[7px] rotate-45': drawerOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300 origin-center"></span>
                <span :class="{ 'opacity-0': drawerOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300"></span>
                <span :class="{ '-translate-y-[7px] -rotate-45': drawerOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300 origin-center"></span>
            </button>
        </div>

    </nav>
</header>

{{-- Mobile overlay --}}
<div class="fixed inset-0 bg-primary-dark/40 backdrop-blur-sm z-40"
     x-show="drawerOpen"
     x-transition:enter="transition duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="closeDrawer()"
     aria-hidden="true"
     style="display:none">
</div>

{{-- Mobile drawer --}}
<aside id="mobile-drawer"
       class="fixed top-0 right-0 h-full w-80 max-w-[90vw] bg-white z-50 shadow-2xl flex flex-col"
       x-show="drawerOpen"
       x-transition:enter="transition-transform duration-300 ease-out"
       x-transition:enter-start="translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition-transform duration-200 ease-in"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="translate-x-full"
       aria-label="Mobile navigation"
       :aria-hidden="(!drawerOpen).toString()"
       style="display:none">

    <div class="flex items-center justify-between px-5 py-4 border-b border-surface-purple">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-base icon-filled">pets</span>
            </div>
            <span class="font-serif text-lg font-bold text-primary-dark">Waggies</span>
        </div>
        <button @click="closeDrawer()" type="button" aria-label="Close navigation menu"
                class="p-2 rounded-lg hover:bg-surface-purple transition-colors">
            <span class="material-symbols-outlined text-primary-dark">close</span>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-1">

        @foreach([
            ['id' => 'services',   'label' => 'Services', 'links' => [
                ['href' => 'services.boarding.index', 'label' => 'Luxury Boarding'],
                ['href' => 'services.boarding.dogs',  'label' => 'Dog Boarding'],
                ['href' => 'services.boarding.cats',  'label' => 'Cat Boarding'],
                ['href' => 'services.boarding.exotic','label' => 'Exotic Pet Boarding'],
                ['href' => 'services.grooming',       'label' => 'Grooming Spa'],
                ['href' => 'services.vet-care',       'label' => 'Vet Care'],
                ['href' => 'services.training',       'label' => 'Dog Training'],
                ['href' => 'services.transport',      'label' => 'Local Transport'],
                ['href' => 'services.pricing',        'label' => 'Pricing'],
                ['href' => 'loyalty',                 'label' => 'Loyalty Programme'],
            ]],
            ['id' => 'about', 'label' => 'About', 'links' => [
                ['href' => 'about.index',        'label' => 'About Us'],
                ['href' => 'about.testimonials', 'label' => 'Testimonials'],
                ['href' => 'about.gallery',      'label' => 'Gallery'],
                ['href' => 'about.careers',      'label' => 'Careers'],
                ['href' => 'about.partnerships', 'label' => 'Partnerships'],
            ]],
            ['id' => 'relocation', 'label' => 'Relocation', 'links' => [
                ['href' => 'relocation.index',    'label' => 'Overview'],
                ['href' => 'relocation.import',   'label' => 'Pet Import'],
                ['href' => 'relocation.export',   'label' => 'Pet Export'],
                ['href' => 'relocation.checklist','label' => 'Doc Checklist Generator'],
            ]],
            ['id' => 'resources', 'label' => 'Resources', 'links' => [
                ['href' => 'blog.index',  'label' => 'Blog'],
                ['href' => 'guides.index','label' => 'Guides'],
                ['href' => 'kb.index',    'label' => 'Knowledge Base'],
                ['href' => 'faq',         'label' => 'FAQ'],
            ]],
        ] as $group)
            <div>
                <button type="button"
                        @click="activeAcc = activeAcc === '{{ $group['id'] }}' ? null : '{{ $group['id'] }}'"
                        :aria-expanded="(activeAcc === '{{ $group['id'] }}').toString()"
                        class="w-full flex items-center justify-between px-3 py-3 text-sm font-semibold
                               text-primary-dark hover:bg-surface-purple rounded-xl transition-colors">
                    {{ $group['label'] }}
                    <span :class="{ 'rotate-180': activeAcc === '{{ $group['id'] }}' }"
                          class="material-symbols-outlined text-sm transition-transform duration-200">expand_more</span>
                </button>
                <div x-show="activeAcc === '{{ $group['id'] }}'"
                     x-transition:enter="transition duration-200 ease-out"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition duration-150 ease-in"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="pl-3"
                     style="display:none">
                    <div class="flex flex-col gap-0.5 py-1">
                        @foreach($group['links'] as $link)
                            <x-navbar.mobile-link href="{{ route($link['href']) }}" label="{{ $link['label'] }}" />
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <a href="{{ route('shop.index') }}"
           class="px-3 py-3 text-sm font-semibold text-primary-dark hover:bg-surface-purple rounded-xl transition-colors block">
            Shop
        </a>

    </nav>

    <div class="px-4 py-4 border-t border-surface-purple">
        <a href="{{ route('contact') }}"
           class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark
                  text-white py-3 rounded-full font-semibold transition shadow-glow
                  focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
            Get a Quote
            <span class="material-symbols-outlined text-base">arrow_forward</span>
        </a>
    </div>

</aside>

</div>
