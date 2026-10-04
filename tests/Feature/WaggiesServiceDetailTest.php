<?php

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('services overview shows four ordered published general FAQs from the database', function () {
    $faqs = collect(range(1, 5))->map(fn (int $sortOrder): Faq => Faq::factory()->create([
        'category' => 'general',
        'question' => "Overview question {$sortOrder}",
        'sort_order' => $sortOrder,
        'status' => Faq::STATUS_PUBLISHED,
    ]));
    $serviceFaq = Faq::factory()->create([
        'category' => 'boarding',
        'question' => 'Boarding-specific question',
        'sort_order' => 0,
        'status' => Faq::STATUS_PUBLISHED,
    ]);

    $content = $this->get(route('services.index'))
        ->assertOk()
        ->assertSeeText('A Few Questions Before You Choose')
        ->assertSeeText('View all FAQs')
        ->assertSeeText($faqs[0]->question)
        ->assertSeeText($faqs[1]->question)
        ->assertSeeText($faqs[2]->question)
        ->assertSeeText($faqs[3]->question)
        ->assertDontSeeText($faqs[4]->question)
        ->assertDontSeeText($serviceFaq->question)
        ->getContent();

    expect(strpos($content, $faqs[0]->question))
        ->toBeLessThan(strpos($content, $faqs[1]->question));
});

test('active service pages keep the existing public service composition', function () {
    $this->get(route('services.boarding.species', ['species' => 'dogs']))
        ->assertOk()
        ->assertSeeText('Individual enclosure')
        ->assertSeeText('Light Bath or Wash')
        ->assertSeeText('Request boarding')
        ->assertDontSeeText('Daily photos')
        ->assertDontSeeText('Structured play');

    $this->get(route('services.vet-care'))
        ->assertOk()
        ->assertSeeText('Wellness consultation')
        ->assertSeeText('Comprehensive examination')
        ->assertSeeText('Vaccination request')
        ->assertSeeText('Microchip implantation')
        ->assertSee('care_need=vaccination-request', false)
        ->assertSee('care_need=microchip', false);
});

test('cat boarding page shows the fixed nightly rate', function () {
    $this->get(route('services.boarding.species', ['species' => 'cats']))
        ->assertOk()
        ->assertSeeText('₦12,000 / night')
        ->assertSeeText('One Fixed Nightly Rate')
        ->assertSeeText('Per Pet / Night')
        ->assertDontSeeText('Quote required')
        ->assertDontSeeText('Rate Confirmed During Review');
});

test('removed service pages no longer resolve or appear as active calls to action', function () {
    foreach (['/services/grooming', '/services/training', '/services/local-transport', '/services/relocation/transport', '/services/boarding/exotic', '/services/pricing', '/tools/cost-calculator'] as $path) {
        $this->get($path)->assertNotFound();
    }

    $this->get(route('services.index'))
        ->assertOk()
        ->assertDontSeeText('Grooming')
        ->assertDontSeeText('Dog Training')
        ->assertDontSeeText('Local Transport')
        ->assertDontSeeText('Exotic Boarding')
        ->assertDontSeeText('Do you have experience with reptiles?')
        ->assertDontSeeText('Can you handle door-to-door domestic relocation?');

    $this->get(route('services.relocation'))
        ->assertOk()
        ->assertDontSeeText('Do you help with moving pets to other Nigerian cities?')
        ->assertDontSeeText('Can you handle door-to-door domestic relocation?');
});
