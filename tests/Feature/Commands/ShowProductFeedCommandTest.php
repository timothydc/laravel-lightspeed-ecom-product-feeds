<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Commands;

use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class ShowProductFeedCommandTest extends TestCase
{
    public function test_it_can_show_feed(): void
    {
        $feed = factory(ProductFeed::class)->create();

        $this->artisan("ecom-feed:show {$feed->id}")
            ->assertExitCode(0);
    }
}
