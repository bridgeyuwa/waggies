<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

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
        ->assertSeeText('Microchip implantation');
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
