<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use TimothyDC\LightspeedEcomProductFeed\Actions\GenerateXmlFeedAction;
use TimothyDC\LightspeedEcomProductFeed\Exceptions\InvalidFeedMapperException;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class GenerateProductFeedCommand extends Command
{
    protected $signature = 'ecom-feed:generate {id}';

    protected $description = 'Generate XML feed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(GenerateXmlFeedAction $generateXmlFeedAction): int
    {
        // get product feed
        $feedId = $this->argument('id');

        $this->comment('Loading product feed: ' . $feedId . ' ...');

        $feed = ProductFeed::find($feedId);

        if (! $feed) {
            $this->error(sprintf('Product feed with ID %d not found.', $feedId));

            return 1;
        }

        if ($feed->mapping_class) {
            $this->comment('Loading mapping class: ' . $feed->mapping_class . ' ...');

            $mapper = resolve($feed->mapping_class);

            if (! $mapper instanceof ProductPayloadMappingInterface) {
                throw new InvalidFeedMapperException($feed->mapping_class . ' must implement ' . ProductPayloadMappingInterface::class);
            }

            $generateXmlFeedAction->generateProductPayloadAction = $mapper;
        }

        // generate feed
        $generateXmlFeedAction->execute($feed);

        $this->comment('Feed successfully created!');

        $this->info('Local URL: ' . Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->path($feed->uuid . '.xml'));
        $this->info('Public URL: ' . Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->url($feed->uuid . '.xml'));

        return 0;
    }
}
