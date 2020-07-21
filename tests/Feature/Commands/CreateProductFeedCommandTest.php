<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Commands;

use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class CreateProductFeedCommandTest extends TestCase
{
    public function test_it_can_create_a_new_product_feed(): void
    {
        $this->markTestIncomplete('wip');

        $this->mock(LightspeedEcomApi::class, static function ($mock) {
            $mock->shouldReceive('setCredentials')->once();
            $mock->shouldReceive('api')->once();
        });

        $this->artisan('ecom-feed:create')
            ->expectsQuestion('Enter your custom mapping class', '')
            ->expectsQuestion('Enter your cron interval', '30 */4 * * *')
            ->expectsQuestion('Enter your webshop API key', 'hidden_api_key')
            ->expectsQuestion('Enter your webshop API secret', 'hidden_api_secret')
            ->expectsChoice('What language should your feed be in?', 'nl', ['nl', 'en'])
            ->assertExitCode(0);

        $this->assertEquals(1, ProductFeed::all()->count());
    }
}
