<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class ListProductFeedCommand extends Command
{
    protected $signature = 'ecom-feed:list';

    protected $description = 'Show a list of all the created feeds';

    public function handle(): int
    {
        $feeds = ProductFeed::all([
            'id',
            'uuid',
            'language',
            'cron_expression',
            'base_url',
            'api_key',
            'api_secret',
            'mapping_class',
            'last_updated_at',
        ])->toArray();

        if (! $feeds) {
            $this->info('No product feeds found.');

            return 0;
        }

        $this->table(array_keys(reset($feeds)), $feeds);

        return 0;
    }
}
