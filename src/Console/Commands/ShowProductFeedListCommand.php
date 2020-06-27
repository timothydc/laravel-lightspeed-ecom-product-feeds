<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class ShowProductFeedListCommand extends Command
{
    protected $signature = 'ecom-feed:list';

    protected $description = 'Show a list of all the created feeds';

    public function handle(): int
    {
        $feeds = ProductFeed::all()->toArray();
        $this->table(array_keys(reset($feeds)), $feeds);
        return 0;
    }
}
