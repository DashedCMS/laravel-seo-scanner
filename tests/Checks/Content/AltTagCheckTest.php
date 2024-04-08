<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Content\AltTagCheck;

it('can perform the alt tag check with alt', function () {
    $check = new AltTagCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><img src="https://dashed.nl/images/logo.png" width="5" height="5" alt="Dashed logo"></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the alt tag check without alt', function () {
    $check = new AltTagCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><img src="https://dashed.nl/images/logo.png" width="5" height="5"></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the alt tag check with empty alt', function () {
    $check = new AltTagCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><img src="https://dashed.nl/images/logo.png" width="5" height="5" alt=""></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
