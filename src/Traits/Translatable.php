<?php

namespace Dashed\Seo\Traits;

trait Translatable
{
    public function getTranslatedDescription(): string
    {
        return __($this->description);
    }
}
