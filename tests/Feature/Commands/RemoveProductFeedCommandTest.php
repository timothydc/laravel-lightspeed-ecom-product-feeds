<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Commands;

use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class RemoveProductFeedCommandTest extends TestCase
{
    public function test_it_cannot_list_feeds(): void
    {
        $productFeed = factory(ProductFeed::class)->create();

        $this->artisan('ecom-feed:remove', ['productFeedId' => $productFeed->id])
            ->assertExitCode(0);
    }

    public function test_it_can_remove_a_feed(): void
    {
        $this->artisan('ecom-feed:remove', ['productFeedId' => 1])
            ->expectsOutput('Product feed with ID 1 not found.')
            ->assertExitCode(1);

        $this->artisan('ecom-feed:remove', ['productFeedId' => 1000])
            ->expectsOutput('Product feed with ID 1000 not found.')
            ->assertExitCode(1);
    }
}
