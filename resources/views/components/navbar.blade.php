@props(['navSection' => ''])

@php
    $servicesActive = in_array($navSection, ['services', 'relocation'], true);
@endphp

<div
    x-data="{
        mobileOpen: false,
        servicesOpen: false,
        aboutOpen: false,
        resourcesOpen: false,
        activeMenu: null,
        pinnedMenu: null,
        hoverTimer: null,
        scheduleOpen(menu) {
            clearTimeout(this.hoverTimer);
            this.hoverTimer = setTimeout(() => {
                if (! this.pinnedMenu || this.pinnedMenu === menu) {
                    this.activeMenu = menu;
                }
            }, 150);
        },
        scheduleClose(menu) {
            clearTimeout(this.hoverTimer);
            this.hoverTimer = setTimeout(() => {
                if (this.pinnedMenu === menu) {
                    return;
                }
                if (this.activeMenu === menu) {
                    this.activeMenu = null;
                }
            }, 150);
        },
        togglePin(menu) {
            clearTimeout(this.hoverTimer);
            if (this.pinnedMenu === menu) {
                this.pinnedMenu = null;
                this.activeMenu = null;
                return;
            }
            this.pinnedMenu = menu;
            this.activeMenu = menu;
        },
        closeDesktopMenus() {
            clearTimeout(this.hoverTimer);
            this.pinnedMenu = null;
            this.activeMenu = null;
        },
        focusFirstMenuItem(menu) {
            this.activeMenu = menu;
            this.$nextTick(() => {
                this.$refs[menu + 'Panel']?.querySelector('[role=menuitem]')?.focus();
            });
        },
        focusMenuItem(direction) {
            const items = [...this.$refs.servicesPanel.querySelectorAll('[role=menuitem]')];
            if (! items.length) {
                return;
            }
            const idx = items.indexOf(document.activeElement);
            let next = idx + direction;
            if (next < 0) {
                next = items.length - 1;
            }
            if (next >= items.length) {
                next = 0;
            }
            items[next].focus();
        },
    }"
    x-effect="document.body.classList.toggle('overflow-hidden', mobileOpen)"
    @keydown.escape.window="if (mobileOpen) { mobileOpen = false } else { closeDesktopMenus() }"
    class="contents"
>

{{-- ── Desktop header (sticky; wrapper uses display:contents so parent is not height-limited) --}}
<header class="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-surface-purple shadow-sm overflow-visible supports-[backdrop-filter]:bg-white/80">
    <nav class="max-w-7xl mx-auto px-4 md:px-10 lg:px-12 h-16 flex items-center justify-between overflow-visible"
         aria-label="Main navigation">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0" aria-label="Waggies — home">
            <div class="w-8 h-8 rounded-xl bg-primary flex items-center justify-center shadow-glow">
                <span class="material-symbols-outlined text-white text-lg icon-filled">pets</span>
            </div>
            <span class="font-serif text-xl font-bold text-primary-dark tracking-tight">Waggies</span>
        </a>

        {{-- Desktop nav --}}
        <ul class="hidden lg:flex items-center gap-1 overflow-visible"
            @click.outside="closeDesktopMenus()"
            role="list">

            {{-- Services mega-menu (includes Relocation) --}}
            <li class="relative overflow-visible"
                @mouseenter="scheduleOpen('services')"
                @mouseleave="scheduleClose('services')">
                <button type="button"
                        x-ref="servicesTrigger"
                        aria-haspopup="true"
                        :aria-expanded="(activeMenu === 'services').toString()"
                        @click.prevent="togglePin('services')"
                        @keydown.arrow-down.prevent="focusFirstMenuItem('services')"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ $servicesActive ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if($servicesActive) aria-current="page" @endif>
                    Services
                    <span class="material-symbols-outlined text-sm transition-transform duration-200"
                          :class="{ 'rotate-180': activeMenu === 'services' }">expand_more</span>
                </button>
                <div x-ref="servicesPanel"
                     role="menu"
                     aria-label="Services menu"
                     x-show="activeMenu === 'services'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     @keydown.arrow-down.prevent="focusMenuItem(1)"
                     @keydown.arrow-up.prevent="focusMenuItem(-1)"
                     @keydown.escape.prevent="closeDesktopMenus(); $refs.servicesTrigger?.focus()"
                     class="nav-dropdown nav-dropdown--services absolute top-full mt-1 left-1/2 -translate-x-1/2"
                     style="display: none">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-5 py-2.5">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Our Services</p>
                    </div>
                    <div class="nav-dropdown__columns nav-dropdown__columns--services">
                        <div role="group" aria-label="Boarding">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">Boarding</p>
                            <div class="flex flex-col gap-0.5">
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.boarding.index') }}" icon="apartment"    title="Luxury Boarding"  subtitle="Premium overnight stays" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.boarding.dogs') }}"  icon="cruelty_free" title="Dog Boarding"     subtitle="Tailored for your dog" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.boarding.cats') }}"  icon="emoticon"     title="Cat Boarding"     subtitle="Calm feline retreat" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.boarding.exotic') }}" icon="bug_report"  title="Exotic Pets"      subtitle="Specialist care" />
                            </div>
                        </div>
                        <div role="group" aria-label="Wellness">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">Wellness</p>
                            <div class="flex flex-col gap-0.5">
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.grooming') }}" icon="spa"              title="Grooming Spa"  subtitle="Breed-specific treatments" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.vet-care') }}" icon="medical_services" title="Vet Care"      subtitle="On-site veterinary support" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.training') }}" icon="school"           title="Dog Training"  subtitle="Positive-reinforcement methods" />
                            </div>
                        </div>
                        <div role="group" aria-label="Relocation">
                            <p class="text-xs font-bold uppercase tracking-widest text-primary/60 mb-2 px-2">Relocation</p>
                            <div class="flex flex-col gap-0.5">
                                <x-navbar.mega-link :in-menu="true" href="{{ route('relocation.index') }}"    icon="public"         title="Relocation"          subtitle="International pet moves" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('relocation.import') }}"   icon="flight_land"    title="Pet Import"          subtitle="Bringing pets into Nigeria" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('relocation.export') }}"   icon="flight_takeoff" title="Pet Export"           subtitle="Moving pets abroad" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('relocation.checklist') }}" icon="checklist"     title="Doc Checklist"        subtitle="Generate your document list" />
                                <x-navbar.mega-link :in-menu="true" href="{{ route('services.transport') }}" icon="local_shipping" title="Local Transport"   subtitle="Door-to-door pickup" />
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            {{-- About --}}
            <li class="relative overflow-visible"
                @mouseenter="scheduleOpen('about')"
                @mouseleave="scheduleClose('about')">
                <button type="button"
                        :aria-expanded="(activeMenu === 'about').toString()"
                        aria-haspopup="true"
                        @click.prevent="togglePin('about')"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ $navSection === 'about' ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if($navSection === 'about') aria-current="page" @endif>
                    About
                    <span class="material-symbols-outlined text-sm transition-transform duration-200"
                          :class="{ 'rotate-180': activeMenu === 'about' }">expand_more</span>
                </button>
                <div x-ref="aboutPanel"
                     x-show="activeMenu === 'about'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="nav-dropdown nav-dropdown--stacked absolute top-full mt-1 left-1/2 -translate-x-1/2"
                     role="menu"
                     aria-label="About menu"
                     style="display: none">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-5 py-2.5">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">About Waggies</p>
                    </div>
                    <div class="p-3 flex flex-col gap-0.5">
                        <x-navbar.mega-link :in-menu="true" href="{{ route('about.index') }}"        icon="info"          title="About Us"       subtitle="Our story and mission" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('about.testimonials') }}" icon="reviews"       title="Testimonials"   subtitle="What pet owners say" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('about.gallery') }}"      icon="photo_library" title="Gallery"        subtitle="Moments at Waggies" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('about.careers') }}"      icon="work"          title="Careers"        subtitle="Join our team" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('about.partnerships') }}" icon="handshake"     title="Partnerships"   subtitle="Grow with us" />
                    </div>
                </div>
            </li>

            {{-- Resources --}}
            <li class="relative overflow-visible"
                @mouseenter="scheduleOpen('resources')"
                @mouseleave="scheduleClose('resources')">
                <button type="button"
                        :aria-expanded="(activeMenu === 'resources').toString()"
                        aria-haspopup="true"
                        @click.prevent="togglePin('resources')"
                        class="flex items-center gap-1 px-4 py-2 text-sm font-medium uppercase tracking-wide rounded-lg transition-colors
                               {{ in_array($navSection, ['blog','guides','kb']) ? 'text-primary font-semibold bg-surface-purple' : 'text-primary-dark/60 hover:text-primary hover:bg-surface-purple' }}"
                        @if(in_array($navSection, ['blog','guides','kb'])) aria-current="page" @endif>
                    Resources
                    <span class="material-symbols-outlined text-sm transition-transform duration-200"
                          :class="{ 'rotate-180': activeMenu === 'resources' }">expand_more</span>
                </button>
                <div x-ref="resourcesPanel"
                     x-show="activeMenu === 'resources'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="nav-dropdown nav-dropdown--stacked absolute top-full right-0 mt-1"
                     role="menu"
                     aria-label="Resources menu"
                     style="display: none">
                    <div class="bg-gradient-to-r from-primary-dark to-primary px-5 py-2.5">
                        <p class="text-white/80 text-xs font-bold uppercase tracking-widest">Resources</p>
                    </div>
                    <div class="p-3 flex flex-col gap-0.5">
                        <x-navbar.mega-link :in-menu="true" href="{{ route('blog.index') }}"   icon="article"     title="Blog"           subtitle="Tips, news &amp; stories" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('guides.index') }}" icon="menu_book"   title="Guides"         subtitle="In-depth how-to guides" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('kb.index') }}"     icon="help_center" title="Knowledge Base"  subtitle="Answers to common questions" />
                        <x-navbar.mega-link :in-menu="true" href="{{ route('faq') }}"          icon="quiz"        title="FAQ"            subtitle="Quick answers" />
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
                <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
            </a>

            <button type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    :aria-label="mobileOpen ? 'Close navigation menu' : 'Open navigation menu'"
                    aria-controls="mobile-menu"
                    class="lg:hidden flex flex-col gap-[5px] p-2 rounded-lg hover:bg-surface-purple transition-colors">
                <span :class="{ 'translate-y-[7px] rotate-45': mobileOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300 origin-center"
                      aria-hidden="true"></span>
                <span :class="{ 'opacity-0': mobileOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300"
                      aria-hidden="true"></span>
                <span :class="{ '-translate-y-[7px] -rotate-45': mobileOpen }"
                      class="block w-5 h-0.5 bg-primary-dark rounded-full transition-all duration-300 origin-center"
                      aria-hidden="true"></span>
            </button>
        </div>

    </nav>
</header>

{{-- Mobile overlay --}}
<div class="fixed inset-0 bg-primary-dark/40 backdrop-blur-sm z-40 lg:hidden"
     x-show="mobileOpen"
     x-transition:enter="transition duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileOpen = false"
     aria-hidden="true"
     style="display: none">
</div>

{{-- Mobile menu --}}
<aside id="mobile-menu"
       role="navigation"
       aria-label="Main navigation"
       x-trap="mobileOpen"
       @click.outside="mobileOpen = false"
       class="fixed top-0 right-0 h-full w-80 max-w-[90vw] bg-white z-50 shadow-2xl flex flex-col lg:hidden"
       x-show="mobileOpen"
       x-transition:enter="transition-transform duration-300 ease-out"
       x-transition:enter-start="translate-x-full"
       x-transition:enter-end="translate-x-0"
       x-transition:leave="transition-transform duration-200 ease-in"
       x-transition:leave-start="translate-x-0"
       x-transition:leave-end="translate-x-full"
       :aria-hidden="(!mobileOpen).toString()"
       style="display: none">

    <div class="flex items-center justify-between px-5 py-4 border-b border-surface-purple">
        <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-lg bg-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-white text-base icon-filled" aria-hidden="true">pets</span>
            </div>
            <span class="font-serif text-lg font-bold text-primary-dark">Waggies</span>
        </div>
        <button @click="mobileOpen = false"
                type="button"
                aria-label="Close navigation menu"
                class="p-2 rounded-lg hover:bg-surface-purple transition-colors">
            <span class="material-symbols-outlined text-primary-dark" aria-hidden="true">close</span>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 py-4 flex flex-col gap-1" aria-label="Mobile navigation links">

        {{-- Services (includes Relocation) --}}
        <div>
            <button type="button"
                    @click="servicesOpen = !servicesOpen"
                    :aria-expanded="servicesOpen.toString()"
                    aria-controls="mobile-services-submenu"
                    class="w-full flex items-center justify-between px-3 py-3 text-sm font-semibold
                           text-primary-dark hover:bg-surface-purple rounded-xl transition-colors">
                Services
                <span :class="{ 'rotate-180': servicesOpen }"
                      class="material-symbols-outlined text-sm transition-transform duration-200"
                      aria-hidden="true">expand_more</span>
            </button>
            <div id="mobile-services-submenu"
                 x-show="servicesOpen"
                 x-transition:enter="transition duration-200 ease-out"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition duration-150 ease-in"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-3 pb-1"
                 style="display: none">
                <x-navbar.mobile-group heading="Boarding">
                    <x-navbar.mobile-link href="{{ route('services.boarding.index') }}" label="Luxury Boarding" />
                    <x-navbar.mobile-link href="{{ route('services.boarding.dogs') }}"  label="Dog Boarding" />
                    <x-navbar.mobile-link href="{{ route('services.boarding.cats') }}"  label="Cat Boarding" />
                    <x-navbar.mobile-link href="{{ route('services.boarding.exotic') }}" label="Exotic Pet Boarding" />
                </x-navbar.mobile-group>
                <x-navbar.mobile-group heading="Wellness">
                    <x-navbar.mobile-link href="{{ route('services.grooming') }}" label="Grooming Spa" />
                    <x-navbar.mobile-link href="{{ route('services.vet-care') }}" label="Vet Care" />
                    <x-navbar.mobile-link href="{{ route('services.training') }}" label="Dog Training" />
                </x-navbar.mobile-group>
                <x-navbar.mobile-group heading="Relocation">
                    <x-navbar.mobile-link href="{{ route('relocation.index') }}"     label="Relocation" />
                    <x-navbar.mobile-link href="{{ route('relocation.import') }}"    label="Pet Import" />
                    <x-navbar.mobile-link href="{{ route('relocation.export') }}"    label="Pet Export" />
                    <x-navbar.mobile-link href="{{ route('relocation.checklist') }}" label="Doc Checklist Generator" />
                    <x-navbar.mobile-link href="{{ route('services.transport') }}" label="Local Transport" />
                </x-navbar.mobile-group>
            </div>
        </div>

        {{-- About --}}
        <div>
            <button type="button"
                    @click="aboutOpen = !aboutOpen"
                    :aria-expanded="aboutOpen.toString()"
                    aria-controls="mobile-about-submenu"
                    class="w-full flex items-center justify-between px-3 py-3 text-sm font-semibold
                           text-primary-dark hover:bg-surface-purple rounded-xl transition-colors">
                About
                <span :class="{ 'rotate-180': aboutOpen }"
                      class="material-symbols-outlined text-sm transition-transform duration-200"
                      aria-hidden="true">expand_more</span>
            </button>
            <div id="mobile-about-submenu"
                 x-show="aboutOpen"
                 x-transition:enter="transition duration-200 ease-out"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition duration-150 ease-in"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-3 pb-1"
                 style="display: none">
                <x-navbar.mobile-group heading="About Waggies">
                    <x-navbar.mobile-link href="{{ route('about.index') }}"        label="About Us" />
                    <x-navbar.mobile-link href="{{ route('about.testimonials') }}" label="Testimonials" />
                    <x-navbar.mobile-link href="{{ route('about.gallery') }}"      label="Gallery" />
                    <x-navbar.mobile-link href="{{ route('about.careers') }}"      label="Careers" />
                    <x-navbar.mobile-link href="{{ route('about.partnerships') }}" label="Partnerships" />
                </x-navbar.mobile-group>
            </div>
        </div>

        {{-- Resources --}}
        <div>
            <button type="button"
                    @click="resourcesOpen = !resourcesOpen"
                    :aria-expanded="resourcesOpen.toString()"
                    aria-controls="mobile-resources-submenu"
                    class="w-full flex items-center justify-between px-3 py-3 text-sm font-semibold
                           text-primary-dark hover:bg-surface-purple rounded-xl transition-colors">
                Resources
                <span :class="{ 'rotate-180': resourcesOpen }"
                      class="material-symbols-outlined text-sm transition-transform duration-200"
                      aria-hidden="true">expand_more</span>
            </button>
            <div id="mobile-resources-submenu"
                 x-show="resourcesOpen"
                 x-transition:enter="transition duration-200 ease-out"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition duration-150 ease-in"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="pl-3"
                 style="display: none">
                <div class="flex flex-col gap-0.5 py-1">
                    @foreach([
                        ['href' => 'blog.index',  'label' => 'Blog'],
                        ['href' => 'guides.index','label' => 'Guides'],
                        ['href' => 'kb.index',    'label' => 'Knowledge Base'],
                        ['href' => 'faq',         'label' => 'FAQ'],
                    ] as $link)
                        <x-navbar.mobile-link href="{{ route($link['href']) }}" label="{{ $link['label'] }}" />
                    @endforeach
                </div>
            </div>
        </div>

        <a href="{{ route('shop.index') }}"
           @click="mobileOpen = false"
           class="px-3 py-3 text-sm font-semibold text-primary-dark hover:bg-surface-purple rounded-xl transition-colors block">
            Shop
        </a>

    </nav>

    <div class="px-4 py-4 border-t border-surface-purple">
        <a href="{{ route('contact') }}"
           @click="mobileOpen = false"
           class="w-full flex items-center justify-center gap-2 bg-primary hover:bg-primary-dark
                  text-white py-3 rounded-full font-semibold transition shadow-glow
                  focus:outline-none focus:ring-2 focus:ring-primary/60 focus:ring-offset-2">
            Get a Quote
            <span class="material-symbols-outlined text-base" aria-hidden="true">arrow_forward</span>
        </a>
    </div>

</aside>

</div>
