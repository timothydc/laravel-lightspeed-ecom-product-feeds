<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Tests\Feature\Commands;

use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Tests\TestCase;

class RemoveProductFeedCommandTest extends TestCase
{
    public function test_it_can_remove_a_feed(): void
    {
        $productFeed = factory(ProductFeed::class)->create();

        $this->artisan('ecom-feed:remove', ['id' => $productFeed->id])
            ->assertExitCode(0);
    }

    public function test_it_cannot_remove_a_feed(): void
    {
        $this->artisan('ecom-feed:remove', ['id' => 1])
            ->expectsOutput('Product feed with ID 1 not found.')
            ->assertExitCode(1);

        $this->artisan('ecom-feed:remove', ['id' => 1000])
            ->expectsOutput('Product feed with ID 1000 not found.')
            ->assertExitCode(1);
    }
}
