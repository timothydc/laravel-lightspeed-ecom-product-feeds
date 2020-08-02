<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class RemoveProductFeedCommand extends Command
{
    protected $signature = 'ecom-feed:remove {id}';

    protected $description = 'Remove a product feed';

    public function handle(): int
    {
        // get product feed
        $feedId = $this->argument('id');
        $feed = ProductFeed::find($feedId);

        if (! $feed) {
            $this->error(sprintf('Product feed with ID %d not found.', $feedId));

            return 1;
        }

        // remove existing feed
        Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->delete($feed->uuid . '.xml');

        // remove feed
        $feed->delete();

        return 0;
    }
}
