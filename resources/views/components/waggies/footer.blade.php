@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag();
    $footerColumns = [
        'Services' => [['Boarding', route('services.boarding')], ['Grooming', route('services.grooming')], ['Veterinary Care', route('services.vet-care')], ['Training', route('services.training')], ['Relocation', route('services.relocation')]],
        'Company' => [['About', route('about')], ['Testimonials', route('about.testimonials')], ['Gallery', route('about.gallery')], ['Careers', route('about.careers')], ['Partnerships', route('about.partnerships')]],
        'Resources' => [['FAQ', route('faq')], ['Guides', route('guides.index')], ['Knowledge Base', route('knowledge-base.index')], ['Tools', route('tools.index')]],
        'Support' => [['Contact', route('contact')], ['Book Appointment', route('contact', ['intent' => 'booking'])], ['Phone', 'tel:+2349080811902'], ['WhatsApp', 'https://wa.me/2349080811902']],
        'Legal' => [['Privacy Policy', route('privacy-policy')], ['Terms of Service', route('terms-of-service')], ['Cookies Policy', route('cookies-policy')]],
    ];
    $socials = [['Instagram', 'instagram'], ['Facebook', 'facebook'], ['X (Twitter)', 'x'], ['LinkedIn', 'linkedin'], ['TikTok', 'tiktok'], ['YouTube', 'youtube']];
@endphp

<footer class="mt-auto border-t border-white/10 bg-primary-dark">
    <div class="mx-auto max-w-7xl px-4 pb-8 pt-12 sm:px-6 sm:pt-16 lg:px-8 lg:pt-20">
        <div class="mb-12 rounded-2xl border border-white/10 bg-white/[0.04] p-6 sm:p-8 lg:mb-14">
            <div class="grid items-center gap-6 lg:grid-cols-2 lg:gap-10">
                <div><h3 class="font-serif text-2xl font-bold text-white text-balance sm:text-[1.75rem]">Pet care tips, <span class="text-secondary italic">monthly.</span></h3><p class="mt-2 max-w-md text-sm text-white/65 sm:text-base">Pet care tips and updates from Waggies. No spam, unsubscribe anytime.</p></div>
                <form action="{{ route('newsletter.store') }}" method="post" class="flex flex-col gap-3 sm:flex-row">
                    @csrf
                    <div class="relative flex-1">
                        <label class="sr-only" for="footer-email">Email address</label>
                        <x-waggies.icon name="email" size="20" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-white/60" />
                        <x-waggies.input id="footer-email" name="email" type="email" required placeholder="you@example.com" :value="old('email')" :error="$errors->first('email')" class="!h-11 !min-h-0 !rounded-lg !border-white/20 !bg-white/[0.08] !pl-10 !pr-3 !text-sm !text-white placeholder:!text-white/50 focus:!border-secondary/60 focus:!outline-none focus:!ring-2 focus:!ring-secondary/30" />
                    </div>
                    <x-waggies.button type="submit" class="h-11 w-full rounded-lg bg-secondary px-5 text-sm text-primary-dark shadow-sm hover:bg-secondary-hover sm:w-auto" aria-label="Subscribe to newsletter">
                        <span class="inline-flex items-center gap-2"><x-waggies.icon name="send" size="16" />Subscribe</span>
                    </x-waggies.button>
                </form>
                @if(session('newsletter_status'))
                    <p class="mt-3 flex items-center gap-1.5 text-sm font-medium text-secondary" role="status"><x-waggies.icon name="check-circle" size="16" variant="filled" />{{ session('newsletter_status') }}</p>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6"><a href="{{ route('home') }}" class="flex items-center gap-2"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary"><x-waggies.icon name="pets" size="20" class="text-white" /></span><span class="font-serif text-xl font-bold text-white">Waggies</span></a><p class="max-w-md text-sm text-white/70">Pet boarding, grooming, vet care, training, and relocation services in Abuja, Nigeria.</p></div>
            <div class="flex flex-wrap items-center gap-4"><span class="text-label whitespace-nowrap text-secondary">Follow Us</span><div class="flex flex-wrap gap-3">@foreach ($socials as [$label, $icon]) @php($socialHref = ['instagram' => 'https://instagram.com/waggies', 'facebook' => 'https://facebook.com/waggies', 'x' => 'https://x.com/waggies', 'linkedin' => 'https://linkedin.com/company/waggies', 'tiktok' => 'https://tiktok.com/@waggies', 'youtube' => 'https://youtube.com/@waggies'][$icon])<a href="{{ $socialHref }}" target="_blank" rel="noopener noreferrer" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white/70 transition-colors hover:border-primary hover:bg-primary hover:text-white" aria-label="{{ $label }}"><x-waggies.brand-icon name="{{ $icon }}" size="18" /></a>@endforeach</div></div>
        </div>

        <div class="my-10 border-t border-white/10 lg:my-12"></div>
        <div class="grid grid-cols-2 gap-8 md:grid-cols-3 lg:grid-cols-5 lg:gap-6">@foreach ($footerColumns as $heading => $links)<div><h3 class="mb-5 text-xs font-semibold uppercase tracking-[0.14em] text-secondary">{{ $heading }}</h3><ul class="space-y-3">@foreach ($links as [$label, $href])<li><a href="{{ $href }}" class="text-sm text-white/65 transition-colors hover:text-white" @if(str_starts_with($href, 'http')) target="_blank" rel="noopener noreferrer" @endif>{{ $label }}</a></li>@endforeach</ul>@if($heading === 'Support')<div class="mt-5 space-y-1.5 border-t border-white/10 pt-4 text-xs text-white/55"><p><span class="font-medium text-white/70">Mon-Fri:</span> 9:00 AM - 5:00 PM</p><p><span class="font-medium text-white/70">Sat-Sun:</span> 10:00 AM - 2:00 PM</p></div>@endif</div>@endforeach</div>
    </div>
    <div class="border-t border-white/10"><div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-5 sm:flex-row sm:px-6"><p class="text-xs text-white/60 sm:text-sm/6">© 2026 Waggies Pet Services. All rights reserved.</p><nav aria-label="Legal" class="flex items-center gap-4 sm:gap-5"><a href="{{ route('privacy-policy') }}" class="text-xs text-white/60 hover:text-white sm:text-sm/6">Privacy</a><a href="{{ route('terms-of-service') }}" class="text-xs text-white/60 hover:text-white sm:text-sm/6">Terms</a><a href="{{ route('cookies-policy') }}" class="text-xs text-white/60 hover:text-white sm:text-sm/6">Cookies</a></nav></div></div>
</footer>
