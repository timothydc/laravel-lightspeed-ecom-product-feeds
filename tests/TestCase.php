<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests;

use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomProductFeedServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getPackageProviders($app): array
    {
        return [
            LightspeedEcomProductFeedServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        Schema::dropAllTables();

        include_once __DIR__.'/../database/migrations/create_product_feed_table.php.stub';
        (new \CreateProductFeedTable())->up();
    }
}
