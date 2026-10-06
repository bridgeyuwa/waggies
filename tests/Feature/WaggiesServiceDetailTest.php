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
    $dogBoardingPage = $this->get(route('services.boarding.species', ['species' => 'dogs']))
        ->assertOk()
        ->assertSeeText('Individual enclosure')
        ->assertSeeText('Built for Safe, Structured Stays')
        ->assertSeeText('Routine Boarding Care')
        ->assertSeeText("Choose your dog's size")
        ->assertSeeText('₦8,000–₦12,000')
        ->assertSeeText('Request dog boarding')
        ->assertSeeText('See boarding requirements')
        ->assertDontSeeText("What's Included")
        ->assertDontSeeText('Water & Cleaning')
        ->assertDontSeeText('100%')
        ->assertDontSeeText('Daily photos')
        ->assertDontSeeText('Structured play');

    $content = $dogBoardingPage->getContent();
    expect(strpos($content, 'Built for Safe, Structured Stays'))
        ->toBeLessThan(strpos($content, 'Routine Boarding Care'))
        ->and(strpos($content, 'Routine Boarding Care'))
        ->toBeLessThan(strpos($content, "Choose your dog's size"));

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
    $content = $this->get(route('services.boarding.species', ['species' => 'cats']))
        ->assertOk()
        ->assertSeeText('₦12,000')
        ->assertSeeText('Per cat / night')
        ->assertSeeText('One Fixed Nightly Rate')
        ->assertSeeText('Per Pet / Night')
        ->assertSeeText('Built for Feline Instincts')
        ->assertSeeText('A Day in the Life for Cats')
        ->assertSeeText('Request cat boarding')
        ->assertDontSeeText('A Quiet, Predictable Stay for Your Cat')
        ->assertDontSeeText('Cat Boarding Includes')
        ->assertDontSeeText('A Calm Environment for Cats')
        ->assertDontSeeText('Submit Booking Request')
        ->assertDontSeeText('Quote required')
        ->assertDontSeeText('Rate Confirmed During Review')
        ->getContent();

    expect(strpos($content, 'Built for Feline Instincts'))
        ->toBeLessThan(strpos($content, 'A Day in the Life for Cats'))
        ->and(strpos($content, 'A Day in the Life for Cats'))
        ->toBeLessThan(strpos($content, 'One Fixed Nightly Rate'));
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
