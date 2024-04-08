<?php

namespace Dashed\Seo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Dashed\Seo\Seo
 */
class Seo extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Dashed\Seo\Seo::class;
    }
}
