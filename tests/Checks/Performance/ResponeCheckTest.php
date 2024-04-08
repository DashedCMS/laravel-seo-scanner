<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Performance\ResponseCheck;

it('can perform response check on a page with a 200 status code', function () {
    $check = new ResponseCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform response check on a page with a 404 status code', function () {
    $check = new ResponseCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 404),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
