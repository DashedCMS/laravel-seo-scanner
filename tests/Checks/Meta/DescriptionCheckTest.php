<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Meta\DescriptionCheck;

it('can perform the description check on a page with multiple description tags', function () {
    $check = new DescriptionCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><meta name="description" content="Dashed is a web development agency based in Amsterdam."><meta property="og:description" content="Dashed is a web development agency based in Amsterdam."><meta name="twitter:description" content="Dashed is a web development agency based in Amsterdam."></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the description check on a page without any description tags', function () {
    $check = new DescriptionCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
