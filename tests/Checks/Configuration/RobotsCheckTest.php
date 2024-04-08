<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Configuration\RobotsCheck;

it('can perform the robots check', function () {
    $check = new RobotsCheck();

    Http::fake([
        'dashed.nl/robots.txt' => Http::response('User-agent: Googlebot
            Disallow: /admin', 200),
    ]);

    $this->assertTrue($check->check(Http::get('dashed.nl'), new Crawler()));
});
