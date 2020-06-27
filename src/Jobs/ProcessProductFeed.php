<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\GenerateXmlFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class ProcessProductFeed implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProductFeed $productFeed;

    public function __construct(ProductFeed $productFeed)
    {
        $this->productFeed = $productFeed;
    }

    public function handle(): void
    {
        Artisan::call(GenerateXmlFeedCommand::class, ['productFeed' => $this->productFeed->id]);
    }
}
