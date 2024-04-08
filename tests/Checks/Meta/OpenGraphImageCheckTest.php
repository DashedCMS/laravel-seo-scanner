<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Meta\OpenGraphImageCheck;

it('can perform open graph image check on a page with a broken open graph image', function () {
    $check = new OpenGraphImageCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><meta property="og:image" content="https://dashed.nl/images/og-image.png"></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform open graph image check on a page without an open graph image', function () {
    $check = new OpenGraphImageCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform open graph image check on a page with a working open graph image', function () {
    $check = new OpenGraphImageCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><meta property="og:image" content="https://source.unsplash.com/random"></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});
