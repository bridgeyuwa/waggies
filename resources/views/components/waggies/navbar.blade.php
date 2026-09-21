@props(['navSection' => ''])

@php
    $navigation = config('waggies.navigation');
    $currentPath = '/'.trim(request()->path(), '/');
    $destination = static function (array $link): string {
        return isset($link['route']) ? route($link['route'], $link['params'] ?? []) : url($link['href']);
    };
    $pathFor = static function (array $link): string {
        return parse_url(isset($link['route']) ? route($link['route'], $link['params'] ?? []) : $link['href'], PHP_URL_PATH) ?: '/';
    };
    $isExact = static function (array $link) use ($currentPath, $pathFor): bool {
        $path = $pathFor($link);
        return rtrim($path, '/') === rtrim($currentPath, '/') || ($path === '/' && $currentPath === '/');
    };
    $isDescendant = static function (array $link) use ($currentPath, $pathFor): bool {
        $path = rtrim($pathFor($link), '/');
        return $path === '' ? $currentPath === '/' : $currentPath === $path || str_starts_with($currentPath, $path.'/');
    };
@endphp

<header x-data="waggiesNavbar" class="sticky top-0 z-layer-navigation w-full overflow-visible border-b border-primary/10 bg-white transition-[background-color,box-shadow,border-color] duration-200" @click.outside="closeDesktop()" @keydown.escape.window="escape()">
    <nav x-ref="nav" class="page-container flex min-h-[4.5rem] items-center justify-between overflow-visible" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="-my-2 flex shrink-0 items-center gap-2.5 py-2" aria-label="Waggies - home">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary shadow-sm"><x-waggies.icon name="pets" size="20" class="text-white" /></span>
            <span class="font-serif text-[1.375rem] font-bold tracking-tight text-primary-dark">Waggies</span>
        </a>

        <ul class="hidden items-center gap-1 overflow-visible lg:flex" role="list">
            @foreach ($navigation as $key => $menu)
                @php($current = $navSection === $key || ($key === 'services' && $navSection === 'relocation'))
                <li class="relative overflow-visible" @mouseenter="hoverOpen('{{ $key }}')" @mouseleave="hoverClose('{{ $key }}')">
                    <button type="button" data-nav-trigger="{{ $key }}" class="flex min-h-[44px] items-center gap-1 rounded-full px-4 py-2 text-[0.8125rem] font-medium uppercase tracking-[0.08em] transition-colors {{ $current ? 'bg-surface-purple text-primary font-semibold' : 'text-primary-dark/60 hover:bg-surface-purple hover:text-primary' }}" aria-haspopup="true" aria-expanded="false" :aria-expanded="desktopMenu === '{{ $key }}'" @click.stop="toggle('{{ $key }}')" @keydown.arrow-down.prevent="openAndFocus('{{ $key }}')">
                        {{ $menu['label'] }}
                        <x-waggies.icon name="chevron-down" size="14" class="transition-transform duration-200" />
                    </button>

                    @if (($menu['kind'] ?? 'stacked') === 'mega')
                        <div id="desktop-menu-{{ $key }}" x-cloak x-show="desktopMenu === '{{ $key }}'" x-effect="if (desktopMenu === '{{ $key }}') updateMegaOffset('{{ $key }}')" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-out duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1" class="nav-dropdown nav-dropdown--mega absolute top-full mt-1" role="menu" aria-label="{{ $menu['label'] }} menu" @mouseenter="cancelClose()" @mouseleave="hoverClose('{{ $key }}')">
                            <div class="nav-dropdown__eyebrow"><span class="nav-dropdown__eyebrow-text">{{ $menu['flyoutLabel'] ?? ($key === 'services' ? 'Our Services' : $menu['label']) }}</span></div>
                            <div class="nav-dropdown__hub">
                                <a href="{{ $destination($menu) }}" class="nav-dropdown__hub-link group" role="menuitem" tabindex="-1" @click="closeDesktop()" @if($isExact($menu)) aria-current="page" @endif>
                                    <span class="nav-dropdown__hub-label">{{ $menu['hubLabel'] }}</span><x-waggies.icon name="arrow-forward" size="16" class="nav-dropdown__hub-arrow" />
                                </a>
                            </div>
                            <div class="nav-dropdown__columns nav-dropdown__columns--mega-3col">
                                @foreach ($menu['columns'] as $column)
                                    <div role="group" aria-label="{{ $column['label'] }}">
                                        <p class="nav-dropdown__group-heading">{{ $column['label'] }}</p>
                                        <div class="flex flex-col gap-0.5">
                                            @foreach ($column['items'] as $item)
                                                @php($itemCurrent = $isExact($item))
                                                @php($itemContainsCurrent = ($item['variant'] ?? '') === 'overview' && $isDescendant($item) && ! $itemCurrent)
                                                <a href="{{ $destination($item) }}" role="menuitem" tabindex="-1" class="nav-mega-link group {{ ($item['variant'] ?? '') === 'overview' ? 'nav-mega-link--overview' : '' }} {{ ($item['variant'] ?? '') === 'child' ? 'nav-mega-link--child' : '' }} {{ $itemContainsCurrent ? 'nav-mega-link--contains-current' : '' }}" @click="closeDesktop()" @if($itemCurrent) aria-current="page" @endif>
                                                    <span class="nav-mega-link__icon"><x-waggies.icon name="{{ $item['icon'] }}" size="18" /></span>
                                                    <span class="min-w-0"><span class="nav-mega-link__title">{{ $item['label'] }}</span><span class="nav-mega-link__subtitle">{{ $item['description'] }}</span></span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div id="desktop-menu-{{ $key }}" x-cloak x-show="desktopMenu === '{{ $key }}'" x-transition class="nav-dropdown nav-dropdown--stacked absolute right-0 top-full mt-1" role="menu" aria-label="{{ $menu['label'] }} menu" @mouseenter="cancelClose()" @mouseleave="hoverClose('{{ $key }}')">
                            <div class="nav-dropdown__eyebrow"><span class="nav-dropdown__eyebrow-text">{{ $menu['flyoutLabel'] }}</span></div>
                            <div class="nav-dropdown__stacked-links">
                                @foreach ($menu['items'] as $item)
                                    <a href="{{ $destination($item) }}" role="menuitem" tabindex="-1" class="nav-mega-link group" @click="closeDesktop()" @if($isExact($item)) aria-current="page" @endif>
                                        <span class="nav-mega-link__icon"><x-waggies.icon name="{{ $item['icon'] }}" size="18" /></span>
                                        <span class="min-w-0"><span class="nav-mega-link__title">{{ $item['label'] }}</span><span class="nav-mega-link__subtitle">{{ $item['description'] }}</span></span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
            @php($shopCurrent = $navSection === 'shop' || str_starts_with($currentPath, '/shop/'))
            <li><a href="{{ route('shop.index') }}" class="flex min-h-[44px] items-center rounded-full px-4 py-2 text-[0.8125rem] font-medium uppercase tracking-[0.08em] transition-colors hover:bg-surface-purple hover:text-primary {{ $shopCurrent ? 'bg-surface-purple font-semibold text-primary' : 'text-primary-dark/60' }}" @if($shopCurrent) aria-current="page" @endif>Shop</a></li>
        </ul>

        <div class="flex items-center gap-3">
            <div class="hidden items-center gap-2 lg:flex">
                <button x-data="waggiesCartIndicator" type="button" aria-controls="global-cart-dialog" aria-expanded="false" class="relative flex h-11 w-11 items-center justify-center rounded-full transition-colors hover:bg-surface-purple focus:outline-none focus:ring-2 focus:ring-primary/60" aria-label="Open cart" :aria-label="`Open cart${count > 0 ? `, ${count} item${count === 1 ? '' : 's'}` : ''}`" @click="$dispatch('waggies:open-cart', { trigger: $event.currentTarget })"><x-waggies.icon name="shopping-cart" size="20" class="text-primary-dark" /><span x-show="count > 0" x-cloak class="absolute -right-1 -top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-primary px-1 text-[10px] font-bold leading-none text-white" x-text="count > 99 ? '99+' : count"></span></button>
                <button type="button" aria-controls="global-search-dialog" aria-expanded="false" class="group relative flex h-11 w-11 items-center justify-center rounded-full transition-colors hover:bg-surface-purple focus:outline-none focus:ring-2 focus:ring-primary/60" aria-label="Search the site" title="Search the site (⌘K / Ctrl+K)" @click="$dispatch('waggies:open-search', { trigger: $event.currentTarget })"><x-waggies.icon name="search" size="20" class="text-primary-dark transition-transform group-hover:scale-110" /></button>
            </div>
            <x-waggies.button href="{{ route('contact', ['intent' => 'booking']) }}" class="hidden !gap-1.5 px-6 py-3 text-sm lg:block">Book Now</x-waggies.button>
            <button x-ref="mobileToggle" type="button" class="flex h-11 w-11 flex-col items-center justify-center gap-[5px] rounded-lg p-3 transition-colors hover:bg-surface-purple lg:hidden" aria-expanded="false" :aria-expanded="mobileOpen" :aria-label="mobileOpen ? 'Close navigation menu' : 'Open navigation menu'" aria-controls="mobile-menu" @click="mobileOpen ? closeMobile() : openMobile()">
                <span class="block h-0.5 w-5 rounded-full bg-primary-dark transition-[transform,opacity] duration-300" :class="mobileOpen && 'translate-y-[7px] rotate-45'" aria-hidden="true"></span>
                <span class="block h-0.5 w-5 rounded-full bg-primary-dark transition-[opacity] duration-300" :class="mobileOpen && 'opacity-0'" aria-hidden="true"></span>
                <span class="block h-0.5 w-5 rounded-full bg-primary-dark transition-[transform,opacity] duration-300" :class="mobileOpen && '-translate-y-[7px] -rotate-45'" aria-hidden="true"></span>
            </button>
        </div>
    </nav>

    <div x-cloak x-show="mobileOpen" x-transition.opacity class="fixed inset-0 z-layer-sticky bg-primary-dark/40 lg:hidden" aria-hidden="true" @click="closeMobile()"></div>
    <aside id="mobile-menu" x-cloak x-show="mobileOpen" x-transition x-ref="drawer" class="fixed right-0 top-0 z-layer-navigation flex h-full w-80 max-w-[90vw] flex-col bg-white shadow-2xl lg:hidden" role="navigation" aria-label="Main navigation" tabindex="-1" :aria-hidden="!mobileOpen" @click.stop>
        <div class="flex items-center justify-between border-b border-surface-purple px-5 py-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2" @click="closeMobile()"><span class="flex h-7 w-7 items-center justify-center rounded-lg bg-primary"><x-waggies.icon name="pets" size="16" class="text-white" /></span><span class="font-serif text-lg font-bold text-primary-dark">Waggies</span></a>
            <button type="button" class="rounded-lg p-3 transition-colors hover:bg-surface-purple" aria-label="Close navigation menu" @click="closeMobile()"><x-waggies.icon name="close" size="20" /></button>
        </div>

        <nav class="flex flex-1 flex-col gap-1 overflow-y-auto px-4 py-4" aria-label="Mobile navigation links">
            <button type="button" aria-controls="global-search-dialog" aria-expanded="false" class="mb-2 flex min-h-[44px] w-full items-center gap-3 rounded-xl px-3 py-3 text-sm font-semibold text-primary-dark transition-colors hover:bg-surface-purple" aria-label="Search the site" @click="$dispatch('waggies:open-search', { trigger: $event.currentTarget, fallback: $refs.mobileToggle }); closeMobile()"><x-waggies.icon name="search" size="16" class="text-primary-dark/70" /><span class="flex-1 text-left">Search the site</span><kbd class="hidden items-center rounded-md border border-surface-purple bg-white px-2 py-0.5 text-[10px] font-medium uppercase tracking-wide text-primary-dark/60 sm:inline-flex" aria-hidden="true">⌘K</kbd></button>

            <div x-data="{ open: false }">
                <button type="button" class="flex min-h-[44px] w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold transition-colors {{ $navSection === 'services' ? 'bg-surface-purple text-primary' : 'text-primary-dark hover:bg-surface-purple' }}" :aria-expanded="open" aria-controls="mobile-services-submenu" @click="open = !open">Services <x-waggies.icon name="chevron-down" size="16" class="transition-transform duration-200" x-bind:class="open && 'rotate-180'" /></button>
                <div id="mobile-services-submenu" x-cloak x-show="open" x-transition class="overflow-hidden pl-2" :aria-hidden="!open">
                    <div class="flex flex-col gap-0.5 py-1">
                        <a href="{{ route('services.index') }}" class="mobile-nav-link {{ $isExact(['route' => 'services.index']) ? 'bg-surface-purple text-primary' : '' }}" @click="closeMobile()"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-primary"></span>View All Services</a>
                        @foreach ($navigation['services']['columns'] as $column)
                            @if (in_array($column['label'], ['Boarding', 'Relocation'], true))
                                @php($overview = $column['items'][0])
                                <div x-data="{ expanded: false }" role="group" aria-label="{{ $column['label'] }}">
                                    <div class="flex items-stretch overflow-hidden rounded-xl {{ $isDescendant($overview) ? 'bg-surface-purple' : '' }}">
                                        <a href="{{ $destination($overview) }}" class="flex min-h-[44px] flex-1 items-center gap-2 px-3 py-2.5 text-sm font-semibold text-primary-dark hover:bg-surface-purple" @click="closeMobile()">{{ $column['label'] }}</a>
                                        <button type="button" class="flex min-h-[44px] w-11 items-center justify-center border-l border-primary/5 text-primary/60 hover:bg-surface-purple" :aria-expanded="expanded" aria-label="Expand {{ $column['label'] }} services" aria-controls="mobile-{{ strtolower($column['label']) }}-submenu" @click="expanded = !expanded"><x-waggies.icon name="chevron-right" size="16" class="transition-transform" x-bind:class="expanded && 'rotate-90'" /></button>
                                    </div>
                                    <div id="mobile-{{ strtolower($column['label']) }}-submenu" x-cloak x-show="expanded" x-transition class="ml-3 overflow-hidden border-l-2 border-surface-purple pl-4" :aria-hidden="!expanded">
                                        @foreach (array_slice($column['items'], 1) as $item)<a href="{{ $destination($item) }}" class="mobile-nav-sublink {{ $isExact($item) ? 'bg-surface-purple text-primary' : 'text-primary-dark/65' }}" @click="closeMobile()">{{ $item['label'] }}</a>@endforeach
                                    </div>
                                </div>
                            @else
                                @foreach ($column['items'] as $item)<a href="{{ $destination($item) }}" class="mobile-nav-link {{ $isExact($item) ? 'bg-surface-purple text-primary' : '' }}" @click="closeMobile()"><span class="h-1 w-1 shrink-0 rounded-full bg-primary/40"></span>{{ $item['label'] }}</a>@endforeach
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            @foreach (['about', 'tools', 'resources'] as $key)
                @php($menu = $navigation[$key])
                <div x-data="{ open: false }">
                    <button type="button" class="flex min-h-[44px] w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-semibold transition-colors {{ $navSection === $key ? 'bg-surface-purple text-primary' : 'text-primary-dark hover:bg-surface-purple' }}" :aria-expanded="open" aria-controls="mobile-{{ $key }}-submenu" @click="open = !open">{{ $menu['label'] }} <x-waggies.icon name="chevron-down" size="16" class="transition-transform duration-200" x-bind:class="open && 'rotate-180'" /></button>
                    <div id="mobile-{{ $key }}-submenu" x-cloak x-show="open" x-transition class="overflow-hidden pl-2" :aria-hidden="!open">
                        <div class="flex flex-col gap-0.5 py-1">
                            @if ($key === 'tools')
                                <a href="{{ $destination($menu) }}" class="mobile-nav-link font-semibold" @click="closeMobile()"><span class="h-1.5 w-1.5 shrink-0 rounded-full bg-primary"></span>{{ $menu['hubLabel'] }}</a>
                                @foreach ($menu['columns'] as $column)
                                    <div x-data="{ expanded: false }">
                                        <button type="button" class="flex min-h-[44px] w-full items-center justify-between rounded-xl px-3 py-3 text-left text-sm font-medium text-primary-dark/70 hover:bg-surface-purple" :aria-expanded="expanded" aria-controls="mobile-tools-{{ strtolower(str_replace(' ', '-', $column['label'])) }}" @click="expanded = !expanded">{{ $column['label'] }} <x-waggies.icon name="chevron-right" size="16" class="text-primary/60 transition-transform" x-bind:class="expanded && 'rotate-90'" /></button>
                                        <div id="mobile-tools-{{ strtolower(str_replace(' ', '-', $column['label'])) }}" x-cloak x-show="expanded" x-transition class="ml-3 overflow-hidden border-l-2 border-surface-purple pl-4" :aria-hidden="!expanded">@foreach ($column['items'] as $item)<a href="{{ $destination($item) }}" class="mobile-nav-sublink text-primary-dark/65" @click="closeMobile()">{{ $item['label'] }}</a>@endforeach</div>
                                    </div>
                                @endforeach
                            @else
                                @foreach ($menu['items'] as $item)<a href="{{ $destination($item) }}" class="mobile-nav-link {{ $isExact($item) ? 'bg-surface-purple text-primary' : '' }}" @click="closeMobile()"><span class="h-1 w-1 shrink-0 rounded-full bg-primary/40"></span>{{ $item['label'] }}</a>@endforeach
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <a href="{{ route('shop.index') }}" class="mobile-nav-link {{ $navSection === 'shop' || str_starts_with($currentPath, '/shop/') ? 'bg-surface-purple text-primary' : '' }}" @click="closeMobile()" @if($navSection === 'shop' || str_starts_with($currentPath, '/shop/')) aria-current="page" @endif>Shop</a>
            <a href="{{ route('contact') }}" class="mobile-nav-link" @click="closeMobile()">Contact</a>
        </nav>
        <div class="space-y-3 border-t border-surface-purple px-4 py-4"><x-waggies.button href="{{ route('contact', ['intent' => 'booking']) }}" class="w-full !gap-1.5 px-6 py-3 text-sm" @click="closeMobile()">Book Now</x-waggies.button></div>
    </aside>
</header>
