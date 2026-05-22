@props(['variant' => 'light'])

@php
    $isDark       = $variant === 'dark';

    $footerBg     = $isDark ? 'bg-primary-dark border-t border-white/10'   : 'bg-surface-purple border-t border-primary/10';
    $brandName    = $isDark ? 'text-white'                                  : 'text-primary-dark text-xl';
    $tagline      = $isDark ? 'text-white/70'                               : 'text-primary-dark/60';
    $socialBtn    = $isDark ? 'bg-white/10 border border-white/10 text-white/70 hover:bg-primary hover:text-white hover:border-primary transition-colors'
                            : 'bg-white border border-primary/10 text-primary hover:bg-primary hover:text-white transition-colors shadow-sm';
    $heading      = $isDark ? 'text-secondary'                              : 'text-primary-dark';
    $linkText     = $isDark ? 'text-white/60 hover:text-white'              : 'text-primary-dark/60 hover:text-primary';
    $bullet       = $isDark ? 'bg-primary-light/80'                         : 'bg-primary/40';
    $legalLink    = $isDark ? 'text-white/60 hover:text-white'              : 'text-primary-dark/60 hover:text-primary';
    $bottomBorder = $isDark ? 'border-white/10'                             : 'border-primary/10';
    $copyright    = $isDark ? 'text-white/50'                               : 'text-primary-dark/60';
@endphp

<footer class="{{ $footerBg }}">
  <div class="mx-auto max-w-7xl px-6 pt-16 pb-8 sm:pt-24 lg:px-8 lg:pt-32">

    <div class="xl:grid xl:grid-cols-3 xl:gap-8">

      {{-- Brand --}}
      <div class="space-y-8">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
          <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center shadow-glow">
            <span class="material-symbols-outlined text-white icon-filled">pets</span>
          </div>
          <span class="font-serif font-bold {{ $brandName }} text-xl">Waggies</span>
        </a>

        <p class="text-sm {{ $tagline }} max-w-xs">
          Abuja's most trusted luxury pet care and relocation specialists. Where every pet is family.
        </p>

        <div class="flex gap-x-4">

          {{-- Instagram --}}
          <a href="#" class="w-10 h-10 rounded-full {{ $socialBtn }} flex items-center justify-center">
            <span class="sr-only">Instagram</span>
            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
            </svg>
          </a>

          {{-- Facebook --}}
          {{-- Note: the original light and dark variants differ by one SVG coordinate (-1.63 vs -1.62). Preserved verbatim. --}}
          <a href="#" class="w-10 h-10 rounded-full {{ $socialBtn }} flex items-center justify-center">
            <span class="sr-only">Facebook</span>
            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              @if ($isDark)
                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.62 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
              @else
                <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
              @endif
            </svg>
          </a>

          {{-- TikTok --}}
          <a href="#" class="w-10 h-10 rounded-full {{ $socialBtn }} flex items-center justify-center">
            <span class="sr-only">TikTok</span>
            <svg class="size-5" fill="currentColor" viewBox="0 0 32 32" aria-hidden="true">
              <path d="M16.656 1.029c1.637-0.025 3.262-0.012 4.886-0.025 0.054 2.031 0.878 3.859 2.189 5.213l-0.002-0.002c1.411 1.271 3.247 2.095 5.271 2.235l0.028 0.002v5.036c-1.912-0.048-3.71-0.489-5.331-1.247l0.082 0.034c-0.784-0.377-1.447-0.764-2.077-1.196l0.052 0.034c-0.012 3.649 0.012 7.298-0.025 10.934-0.103 1.853-0.719 3.543-1.707 4.954l0.020-0.031c-1.652 2.366-4.328 3.919-7.371 4.011l-0.014 0c-0.123 0.006-0.268 0.009-0.414 0.009-1.73 0-3.347-0.482-4.725-1.319l0.040 0.023c-2.508-1.509-4.238-4.091-4.558-7.094l-0.004-0.041c-0.025-0.625-0.037-1.25-0.012-1.862 0.49-4.779 4.494-8.476 9.361-8.476 0.547 0 1.083 0.047 1.604 0.136l-0.056-0.008c0.025 1.849-0.050 3.699-0.050 5.548-0.423-0.153-0.911-0.242-1.42-0.242-1.868 0-3.457 1.194-4.045 2.861l-0.009 0.030c-0.133 0.427-0.21 0.918-0.21 1.426 0 0.206 0.013 0.41 0.037 0.61l-0.002-0.024c0.332 2.046 2.086 3.59 4.201 3.59 0.061 0 0.121-0.001 0.181-0.004l-0.009 0c1.463-0.044 2.733-0.831 3.451-1.994l0.010-0.018c0.267-0.372 0.45-0.822 0.511-1.311l0.001-0.014c0.125-2.237 0.075-4.461 0.087-6.698 0.012-5.036-0.012-10.060 0.025-15.083z" />
            </svg>
          </a>

          {{-- X --}}
          <a href="#" class="w-10 h-10 rounded-full {{ $socialBtn }} flex items-center justify-center">
            <span class="sr-only">X</span>
            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path d="M13.6823 10.6218L20.2391 3H18.6854L12.9921 9.61788L8.44486 3H3.2002L10.0765 13.0074L3.2002 21H4.75404L10.7663 14.0113L15.5685 21H20.8131L13.6819 10.6218H13.6823ZM11.5541 13.0956L10.8574 12.0991L5.31391 4.16971H7.70053L12.1742 10.5689L12.8709 11.5655L18.6861 19.8835H16.2995L11.5541 13.096V13.0956Z" />
            </svg>
          </a>

          {{-- YouTube --}}
          <a href="#" class="w-10 h-10 rounded-full {{ $socialBtn }} flex items-center justify-center">
            <span class="sr-only">YouTube</span>
            <svg class="size-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
            </svg>
          </a>

        </div>
      </div>

      {{-- Links --}}
      <div class="mt-16 grid grid-cols-2 gap-8 xl:col-span-2 xl:mt-0">

        <div class="md:grid md:grid-cols-2 md:gap-8">

          {{-- Services --}}
          <div>
            <h3 class="font-bold {{ $heading }} text-sm uppercase tracking-widest mb-5">Services</h3>
            <ul class="mt-6 space-y-4">
              @foreach([
                ['label' => 'Pet Boarding',   'route' => 'services.boarding.index'],
                ['label' => 'Pet Relocation', 'route' => 'relocation.index'],
                ['label' => 'Vet Services',   'route' => 'services.vet-care'],
                ['label' => 'Grooming Spa',   'route' => 'services.grooming'],
                ['label' => 'Dog Training',   'route' => 'services.training'],
                ['label' => 'Pricing / Estimate Tool',  'route' => 'services.pricing'],
              ] as $link)
                <li>
                  <a href="{{ route($link['route']) }}" class="flex items-center gap-1.5 text-sm {{ $linkText }} transition-colors">
                    <span class="w-1.5 h-1.5 {{ $bullet }} rounded-full"></span>
                    {{ $link['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

          {{-- Company --}}
          <div class="mt-10 md:mt-0">
            <h3 class="font-bold {{ $heading }} text-sm uppercase tracking-widest mb-5">Company</h3>
            <ul class="mt-6 space-y-4">
              @foreach([
                ['label' => 'About Us',      'route' => 'about.index'],
                ['label' => 'Partnerships',  'route' => 'about.partnerships'],
                ['label' => 'Gallery',       'route' => 'about.gallery'],
                ['label' => 'Blog',          'route' => 'blog.index'],
                ['label' => 'Guides',        'route' => 'guides.index'],
                ['label' => 'Careers',       'route' => 'about.careers'],
              ] as $link)
                <li>
                  <a href="{{ route($link['route']) }}" class="flex items-center gap-1.5 text-sm {{ $linkText }} transition-colors">
                    <span class="w-1.5 h-1.5 {{ $bullet }} rounded-full"></span>
                    {{ $link['label'] }}
                  </a>
                </li>
              @endforeach
            </ul>
          </div>

        </div>

        <div class="md:grid md:grid-cols-2 md:gap-8">

          {{-- Support --}}
          <div>
            <h3 class="font-bold {{ $heading }} text-sm uppercase tracking-widest mb-5">Support</h3>
            <ul class="mt-6 space-y-4">
              <li>
                <a href="{{ route('faq') }}" class="flex items-center gap-1.5 text-sm {{ $linkText }} transition-colors">
                  <span class="w-1.5 h-1.5 {{ $bullet }} rounded-full"></span>
                  FAQ
                </a>
              </li>
              <li>
                <a href="{{ route('contact') }}" class="flex items-center gap-1.5 text-sm {{ $linkText }} transition-colors">
                  <span class="w-1.5 h-1.5 {{ $bullet }} rounded-full"></span>
                  Contact Us
                </a>
              </li>
            </ul>
          </div>

          {{-- Legal --}}
          <div class="mt-10 md:mt-0">
            <h3 class="font-bold {{ $heading }} text-sm uppercase tracking-widest mb-5">Legal</h3>
            <ul class="mt-6 space-y-4">
              <li>
                <a href="{{ route('privacy') }}" class="text-sm {{ $legalLink }} transition-colors">Privacy Policy</a>
              </li>
              <li>
                <a href="{{ route('terms') }}" class="text-sm {{ $legalLink }} transition-colors">Terms of Service</a>
              </li>
              <li>
                <a href="{{ route('cookies') }}" class="text-sm {{ $legalLink }} transition-colors">Cookies</a>
              </li>
            </ul>
          </div>

        </div>
      </div>

    </div>
  </div>

  {{-- Bottom bar --}}
  <div class="border-t {{ $bottomBorder }}">
    <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
      <p class="text-sm/6 {{ $copyright }}">&copy; 2026 Waggies Pet Services. All rights reserved.</p>
    </div>
  </div>
</footer>