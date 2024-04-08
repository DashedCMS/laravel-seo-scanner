<?php

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Dashed\Seo\Checks\Performance\CompressionCheck;

it('can perform a compression check on a compressed response', function () {
    $check = new CompressionCheck();

    $contentEncodings = ['gzip', 'deflate', 'br', 'compress'];

    foreach ($contentEncodings as $contentEncoding) {
        Http::fake([
            'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200, [
                'Content-Encoding' => $contentEncoding,
            ]),
        ]);

        $this->assertTrue($check->check(Http::get('dashed.nl'), new Crawler()));
    }
});

it('can perform a compression check on a non-compressed response', function () {
    $check = new CompressionCheck();

    Http::fake([
        'dashed.nl' => Http::response('<html><head></head><body></body></html>', 200),
    ]);

    $this->assertFalse($check->check(Http::get('dashed.nl'), new Crawler()));
});
