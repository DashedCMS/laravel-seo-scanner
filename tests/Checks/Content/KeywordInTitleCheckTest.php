<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Content\KeywordInTitleCheck;

it('can perform the keyword in title check on a page with the keyword in the title', function () {
    $check = new KeywordInTitleCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><title>dashed</title><meta name="keywords" content="dashed, seo, laravel, package"></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the keyword in title check on a page without the keyword in the title', function () {
    $check = new KeywordInTitleCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head><title>dashed</title><meta name="keywords" content="seo, laravel, package"></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the keyword in title check on a page without keywords', function () {
    $check = new KeywordInTitleCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});
