<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console;

use App\Console\Kernel as ConsoleKernel;
use Illuminate\Console\Scheduling\Schedule;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\GenerateXmlFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Jobs\ProcessProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        parent::schedule($schedule);

        $this->scheduleProductFeeds($schedule);
    }

    protected function scheduleProductFeeds(Schedule $schedule): void
    {
        if (config('lightspeed-ecom-product-feed.scheduled_tasks.auto_run') === false) {
            return;
        }

        foreach (ProductFeed::all() as $productFeed) {
            if (config('lightspeed-ecom-product-feed.scheduled_tasks.use_queue') === true) {
                // process via queue
                $schedule->job(new ProcessProductFeed($productFeed), config('lightspeed-ecom-product-feed.scheduled_tasks.queue'))->cron($productFeed->cron_expression);
            } else {
                // process via direct command
                $schedule->command(GenerateXmlFeedCommand::class, [$productFeed->id])->cron($productFeed->cron_expression);
            }
        }
    }
}
