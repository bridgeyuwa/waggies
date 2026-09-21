@props(['variant' => 'overview'])

@php
    $overview = [
        ['serviceName' => 'Boarding & Care', 'serviceHref' => route('services.boarding'), 'quote' => 'Waggies is the only place I would trust with my dog. The daily updates give me real peace of mind.', 'initial' => 'A', 'name' => 'Adaeze O.', 'subtitle' => 'Dog owner, Maitama'],
        ['serviceName' => 'Professional Grooming', 'serviceHref' => route('services.grooming'), 'quote' => 'The grooming team transformed my Persian cat. She looked like she had come straight from a pet show.', 'initial' => 'E', 'name' => 'Emeka N.', 'subtitle' => 'Cat owner, Wuse II'],
        ['serviceName' => 'Pet Relocation', 'serviceHref' => route('services.relocation'), 'quote' => 'Our relocation from London was well-organised. Every document was sorted, zero stress on our end.', 'initial' => 'F', 'name' => 'Fatima M.', 'subtitle' => 'Relocation client, Asokoro'],
    ];
    $relocation = [
        ['serviceName' => 'Pet Import', 'serviceHref' => route('relocation.import'), 'quote' => 'Our relocation from London was seamless. Every document was sorted  -  zero stress on our end.', 'initial' => 'F', 'name' => 'Fatima M.', 'subtitle' => 'Relocation client · Asokoro'],
        ['serviceName' => 'Pet Export', 'serviceHref' => route('relocation.export'), 'quote' => 'Relocating to Toronto with my two dogs felt overwhelming until Waggies took over the export paperwork, vaccinations and flight crate. They handled every detail end to end.', 'initial' => 'I', 'name' => 'Ifeoma D.', 'subtitle' => 'Relocation client · Asokoro'],
        ['serviceName' => 'Local Transport', 'serviceHref' => route('relocation.transport'), 'quote' => 'Transport service is brilliant  -  on time, air-conditioned and my dogs arrive calm every single time.', 'initial' => 'Y', 'name' => 'Yemi F.', 'subtitle' => 'Dog owner · Wuse'],
    ];
    $boarding = [
        ['serviceName' => 'Dog Boarding', 'serviceHref' => route('services.boarding.species', ['species' => 'dogs']), 'quote' => "Waggies is the only place I'd trust with my dog. The daily photo updates gave me real peace of mind while I was travelling.", 'initial' => 'A', 'name' => 'Adaeze O.', 'subtitle' => 'Dog owner · Maitama'],
        ['serviceName' => 'Cat Boarding', 'serviceHref' => route('services.boarding.species', ['species' => 'cats']), 'quote' => 'Leaving my cat somewhere new always feels risky, but the cattery suites are calm, quiet and spotless. Mimi came home more relaxed than when she left.', 'initial' => 'Z', 'name' => 'Zainab A.', 'subtitle' => 'Cat owner · Life Camp'],
        ['serviceName' => 'Exotic Pet Boarding', 'serviceHref' => route('services.boarding.species', ['species' => 'exotic']), 'quote' => "Finding somewhere in Abuja that genuinely understands exotic pets is hard. The team knew exactly how to handle my African grey's diet and enrichment — proper specialists.", 'initial' => 'T', 'name' => 'Tunde O.', 'subtitle' => 'Exotic pet owner · Maitama'],
    ];
    $boardingSpecies = [
        'boarding-dogs' => [$boarding[0]],
        'boarding-cats' => [$boarding[1]],
        'boarding-exotic' => [$boarding[2]],
    ];
    $testimonials = $variant === 'relocation' ? $relocation : ($variant === 'boarding' ? $boarding : ($boardingSpecies[$variant] ?? $overview));
@endphp

<section class="border-y border-primary/5 bg-surface-purple/30 py-20">
    <div class="page-container">
        <div class="mx-auto mb-14 max-w-2xl text-center"><span class="text-eyebrow mb-2 block">PROVEN CARE OUTCOMES</span><h2 class="text-h2 mb-3">What Pet Owners Experience</h2><p class="text-body-sm text-primary-dark/70">Real feedback from pet owners across Abuja who count on Waggies for boarding, grooming, and relocation.</p></div>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <x-waggies.card hover class="flex flex-col justify-between border border-primary/10 bg-white p-7">
                    <div><div class="mb-4 flex items-center justify-between gap-2 border-b border-primary/5 pb-3"><span class="text-xs font-bold uppercase tracking-wider text-primary">{{ $testimonial['serviceName'] }}</span><div class="flex gap-0.5" aria-label="5 out of 5 stars">@for($i = 0; $i < 5; $i++)<x-waggies.icon name="star" size="16" variant="filled" class="text-gold" />@endfor</div></div><blockquote class="mb-6 text-sm font-medium leading-relaxed text-primary-dark/85">“{{ $testimonial['quote'] }}”</blockquote></div>
                    <div><div class="mb-4 flex items-center gap-3 border-t border-primary/5 pt-3"><div class="flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/10 font-serif text-xs font-bold text-primary" aria-hidden="true">{{ $testimonial['initial'] }}</div><div><cite class="not-italic block text-sm font-bold leading-tight text-primary-dark">{{ $testimonial['name'] }}</cite><span class="block text-xs text-primary-dark/50">{{ $testimonial['subtitle'] }}</span></div></div><a href="{{ $testimonial['serviceHref'] }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary transition-colors hover:text-primary-dark">Explore {{ $testimonial['serviceName'] }} <x-waggies.icon name="arrow-forward" size="14" /></a></div>
                </x-waggies.card>
            @endforeach
        </div>
    </div>
</section>
