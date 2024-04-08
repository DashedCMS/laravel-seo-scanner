<?php

namespace Dashed\Seo\Traits;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Dashed\Seo\Facades\Seo;
use Dashed\Seo\Models\SeoScore as SeoScoreModel;
use Dashed\Seo\SeoScore;

trait HasSeoScore
{
    public function seoScore(): SeoScore
    {
        return Seo::check(url: method_exists('getUrl') ? $this->getUrl() : $this->url);
    }

    public function seoScores(): MorphMany
    {
        return $this->morphMany(SeoScoreModel::class, 'model');
    }

    public function scopeWithSeoScores(Builder $query): Builder
    {
        return $query->whereHas('seoScores')->with('seoScores');
    }

    public function getCurrentScore(): int
    {
        return $this->seoScore()->getScore();
    }

    public function getCurrentScoreDetails(): array
    {
        return $this->seoScore()->getScoreDetails();
    }
}
