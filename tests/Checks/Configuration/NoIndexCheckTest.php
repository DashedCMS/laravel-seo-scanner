<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Configuration\NoIndexCheck;

it('can perform the noindex check with robots tag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('', 200, ['X-Robots-Tag' => 'noindex']),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the noindex check with robots metatag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><meta name="robots" content="noindex"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the noindex check with googlebot metatag', function () {
    $check = new NoIndexCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><meta name="googlebot" content="noindex"></head></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
