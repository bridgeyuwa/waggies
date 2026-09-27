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

test('removed service pages no longer resolve or appear as active calls to action', function () {
    foreach (['/services/grooming', '/services/training', '/services/local-transport', '/services/boarding/exotic'] as $path) {
        $this->get($path)->assertNotFound();
    }

    $this->get(route('services.index'))
        ->assertOk()
        ->assertDontSeeText('Grooming')
        ->assertDontSeeText('Dog Training')
        ->assertDontSeeText('Local Transport')
        ->assertDontSeeText('Exotic Boarding');
});
