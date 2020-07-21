<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Commands;

use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class ListProductFeedCommandTest extends TestCase
{
    public function test_it_cannot_list_feeds(): void
    {
        $this->artisan('ecom-feed:list')
            ->expectsOutput('No product feeds found.')
            ->assertExitCode(0);
    }

    public function test_it_can_list_existing_feeds(): void
    {
        factory(ProductFeed::class)->create();

        $this->artisan('ecom-feed:list')->assertExitCode(0);
    }
}
