<?php

namespace Dashed\Seo\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class ScanSpecificResult implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    public $timeout = 60 * 60 * 3;

    public function handle($model): void
    {
        $seo = $model->seoScore();

        if (config('seo.database.save')) {
            $this->saveScoreToDatabase(seo: $seo, url: $model->url, model: $model);
        }
    }
}
