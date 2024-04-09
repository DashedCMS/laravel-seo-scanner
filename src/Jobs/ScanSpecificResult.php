<?php

namespace Dashed\Seo\Jobs;

use Dashed\Seo\Commands\SeoScan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ScanSpecificResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 60 * 60 * 3;

    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function handle(): void
    {
        $seo = $this->model->seoScore();

        if (config('seo.database.save')) {
            (new \Dashed\Seo\Commands\SeoScan)->saveScoreToDatabase(seo: $seo, url: $this->model->url, model: $this->model, true);
        }
    }
}
