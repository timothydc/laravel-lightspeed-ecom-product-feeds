<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\CreateProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\GenerateProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\ListProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\RemoveProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\ShowProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\UpdateProductFeedCommand;
use TimothyDC\LightspeedEcomProductFeed\Feeds\StandardFeed;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use TimothyDC\LightspeedEcomProductFeed\Jobs\ProcessProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Mixins\StrMixin;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Services\ApiClient;

class LightspeedEcomProductFeedServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // register config
        $this->mergeConfigFrom(__DIR__ . '/../config/lightspeed-ecom-product-feed.php', 'lightspeed-ecom-product-feed');

        // register commands
        $this->app->bind('command.ecom-feed:list', ListProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:create', CreateProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:update', UpdateProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:show', ShowProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:remove', RemoveProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:generate', GenerateProductFeedCommand::class);

        $this->commands([
            'command.ecom-feed:list',
            'command.ecom-feed:create',
            'command.ecom-feed:update',
            'command.ecom-feed:show',
            'command.ecom-feed:remove',
            'command.ecom-feed:generate',
        ]);

        // register services
        $this->app->singleton(LightspeedEcomApi::class, static function ($app) {
            return new LightspeedEcomApi($app->make(ApiClient::class));
        });

        $this->app->alias(LightspeedEcomApi::class, 'lightspeed-ecom-api');

        // register product mapping class
        $this->app->bind(ProductPayloadMappingInterface::class, StandardFeed::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->loadConfig()
                ->loadMigrations()
                ->loadScheduledTasks();
        }

        Str::mixin(new StrMixin);
    }

    protected function loadConfig(): self
    {
        $this->publishes([
            __DIR__ . '/../config/lightspeed-ecom-product-feed.php' => config_path('lightspeed-ecom-product-feed.php'),
        ], ['lightspeed-ecom-product-feed', 'lightspeed-ecom-product-feed:config']);

        return $this;
    }

    protected function loadMigrations(): self
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_product_feed_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_product_feed_table.php'),
        ], ['lightspeed-ecom-product-feed', 'lightspeed-ecom-product-feed:migrations']);

        return $this;
    }

    protected function loadScheduledTasks(): self
    {
        if (Schema::hasTable('product_feeds') === true) {
            $this->app->booted(fn () => $this->addScheduledTasks($this->app->make(Schedule::class)));
        }

        return $this;
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['lightspeed-ecom-product-feed'];
    }

    protected function addScheduledTasks(Schedule $schedule): void
    {
        if (config()->get('lightspeed-ecom-product-feed.scheduled_tasks.auto_run') === false) {
            return;
        }

        foreach (ProductFeed::all() as $productFeed) {
            if (config()->get('lightspeed-ecom-product-feed.scheduled_tasks.use_queue') === true) {
                // process via queue
                $schedule->job(new ProcessProductFeed($productFeed), config()->get('lightspeed-ecom-product-feed.scheduled_tasks.queue'))->cron($productFeed->cron_expression);
            } else {
                // process via direct command
                $schedule->command(GenerateProductFeedCommand::class, [$productFeed->id])->cron($productFeed->cron_expression);
            }
        }
    }
}
