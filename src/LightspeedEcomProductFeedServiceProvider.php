<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use TimothyDC\LightspeedEcomProductFeed\Console\Commands\{CreateProductFeedCommand, GenerateXmlFeedCommand, RemoveProductFeedCommand, ShowProductFeedListCommand};
use TimothyDC\LightspeedEcomProductFeed\Console\Kernel;
use TimothyDC\LightspeedEcomProductFeed\Services\ApiClient;

class LightspeedEcomProductFeedServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/lightspeed-ecom-product-feed.php' => config_path('lightspeed-ecom-product-feed.php'),
        ], ['lightspeed-ecom-product-feed', 'lightspeed-ecom-product-feed:config']);

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], ['lightspeed-ecom-product-feed', 'lightspeed-ecom-product-feed:migrations']);
    }

    public function register(): void
    {
        // register config
        $this->mergeConfigFrom(__DIR__ . '/../config/lightspeed-ecom-product-feed.php', 'lightspeed-ecom-product-feed');

        // register commands
        $this->app->bind('command.ecom-feed:list', ShowProductFeedListCommand::class);
        $this->app->bind('command.ecom-feed:create', CreateProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:remove', RemoveProductFeedCommand::class);
        $this->app->bind('command.ecom-feed:generate-xml', GenerateXmlFeedCommand::class);

        $this->commands([
            'command.ecom-feed:list',
            'command.ecom-feed:create',
            'command.ecom-feed:remove',
            'command.ecom-feed:generate-xml',
        ]);

        // register services
        $this->app->singleton(LightspeedEcomApi::class, static function ($app) {
            return new LightspeedEcomApi($app->make(ApiClient::class));
        });

        $this->app->alias(LightspeedEcomApi::class, 'lightspeed-ecom-api');

        $this->app->singleton('lightspeed-ecom-product-feed.console.kernel', static function ($app) {
            $dispatcher = $app->make(Dispatcher::class);
            return new Kernel($app, $dispatcher);
        });

        $this->app->make('lightspeed-ecom-product-feed.console.kernel');
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
}
