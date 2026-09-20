@extends('layouts.app')

@section('content')
<x-waggies.breadcrumb-strip :items="[['label' => 'Services', 'route' => 'services.index'], ['label' => 'Relocation', 'route' => 'services.relocation'], ['label' => 'Pet Import', 'route' => 'relocation.import']]" class="border-b border-primary/5 bg-white" />
<x-waggies.cover-hero :hero="$page['hero']" content-position="bottom" />
<section class="bg-white py-20"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><x-waggies.section-heading :eyebrow="$page['sectionHeading']['eyebrow']" :title="$page['sectionHeading']['title']" :subtitle="$page['sectionHeading']['subtitle']" /><x-waggies.service-feature-grid :features="$page['features']" /></div></section>
<section class="border-t border-primary/5 bg-surface py-20"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><x-waggies.section-heading :eyebrow="$page['processHeading']['eyebrow']" :title="$page['processHeading']['title']" :subtitle="$page['processHeading']['subtitle']" /><x-waggies.process-steps :steps="$page['processSteps']" class="mt-10" /></div></section>
<section class="w-full py-20"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><x-waggies.cta-primary :cta="$page['cta']" /></div></section>
<section class="border-t border-primary/5 bg-surface py-16"><div class="mx-auto max-w-7xl px-4 md:px-10 lg:px-12"><x-waggies.section-heading eyebrow="FAQs" title="Frequently Asked Questions" spacing="mb-8" /><x-waggies.faq-accordion :faqs="$faqs" /><p class="mt-6 text-center text-sm text-primary-dark/60">More questions? <a href="{{ route('faq') }}" class="font-bold text-primary hover:underline">View all FAQs</a> or <a href="{{ route('contact') }}" class="font-bold text-primary hover:underline">contact us</a>.</p></div></section>
<x-waggies.share-row title="Pet Import - Waggies" description="Full-service pet import to Nigeria - permits, health certificates, microchip verification, and airport collection in Abuja." />
@endsection
