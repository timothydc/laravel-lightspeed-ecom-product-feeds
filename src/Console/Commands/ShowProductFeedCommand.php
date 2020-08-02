<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class ShowProductFeedCommand extends Command
{
    protected $signature = 'ecom-feed:show {id}';

    protected $description = 'Show more information on a certain feed';

    public function handle(): int
    {
        // get product feed
        $feedId = $this->argument('id');
        $feed = ProductFeed::find($feedId);

        if (! $feed) {
            $this->error(sprintf('Product feed with ID %d not found.', $feedId));

            return 1;
        }

        $this->table(['label', 'value'], [
            ['id', $feed->id],
            ['uuid', $feed->uuid],
            ['language', $feed->language],
            ['cron_expression', $feed->cron_expression],
            ['base_url', $feed->base_url],
            ['api_key', $feed->api_key],
            ['api_secret', $feed->api_secret],
            ['mapping_class', $feed->mapping_class],
            ['last_updated_at', $feed->last_updated_at],
            ['local storage path', Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->path($feed->uuid . '.xml')],
            ['public storage path', Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->url($feed->uuid . '.xml')],
            ['updated_at', $feed->updated_at],
            ['created_at', $feed->created_at],
        ]);

        return 0;
    }
}
