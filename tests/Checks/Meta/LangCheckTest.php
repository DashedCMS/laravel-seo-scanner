<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Meta\LangCheck;

it('can perform the lang check on a page with a lang attribute', function () {
    $check = new LangCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html lang="nl"><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the lang check on a page without a lang attribute', function () {
    $check = new LangCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
