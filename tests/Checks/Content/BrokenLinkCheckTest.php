<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Content\BrokenLinkCheck;

it('can perform the broken link check on broken links', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl/404">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the broken link check on working links', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can perform the broken link check on content where no links are used', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can run the broken link check on a relative url', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="/404">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('can bypass DNS layers using DNS resolving', function () {
    $this->markTestSkipped('This test is skipped because we cannot fake DNS resolving.');

    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    config(['seo.resolve' => [
        'dashed.nl' => '240.0.0.0',
    ]]);

    $this->assertFalse($check->check(Http::get('dashed.nl'), $crawler));
});

it('cannot bypass DNS layers using a fake IP when DNS resolving', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    config(['seo.resolve' => [
        'dashed.nl' => '8.8.8.8',
    ]]);

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});

it('can check if link is broken by checking on configured status codes', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    config(['seo.broken_link_check.status_codes' => ['403']]);

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl/404">Dashed</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl/admin/dashboard'), $crawler));
});

it('can exclude certain paths from the broken link check', function () {
    $check = new BrokenLinkCheck();
    $crawler = new Crawler();

    config(['seo.broken_link_check.exclude_links' => ['https://dashed.nl/excluded']]);

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body><a href="https://dashed.nl/excluded">Excluded Link</a></body></html>', 200),
    ]);

    $crawler->addHtmlContent(Http::get('dashed.nl')->body());

    $this->assertTrue($check->check(Http::get('dashed.nl'), $crawler));
});
