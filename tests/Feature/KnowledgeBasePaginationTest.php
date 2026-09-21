<?php

test('knowledge base pagination keeps one Alpine root for each numbered page iteration', function (): void {
    $response = $this->get(route('knowledge-base.index', ['page' => 2]));

    $response
        ->assertOk()
        ->assertSee('aria-current="page"', false)
        ->assertSee('rel="prev"', false)
        ->assertSee('rel="next"', false);

    $document = new DOMDocument;
    @$document->loadHTML($response->getContent() ?: '');

    $templates = (new DOMXPath($document))->query('//template[@x-for="number in pageNumbers()"]');
    $rootElements = [];

    foreach ($templates as $template) {
        foreach ($template->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $rootElements[] = $child;
            }
        }
    }

    $this->assertCount(1, $templates);
    $this->assertCount(1, $rootElements);
});
