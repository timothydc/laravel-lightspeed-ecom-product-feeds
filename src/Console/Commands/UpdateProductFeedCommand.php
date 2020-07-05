<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use TimothyDC\LightspeedEcomProductFeed\Actions\GenerateProductPayloadAction;
use TimothyDC\LightspeedEcomProductFeed\Actions\SaveProductFeedAction;
use TimothyDC\LightspeedEcomProductFeed\Exceptions\LightspeedEcomApiException;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Traits\AskFeedQuestionsTrait;
use WebshopappApiException;

class UpdateProductFeedCommand extends Command
{
    use AskFeedQuestionsTrait;

    protected $signature = 'ecom-feed:update {productFeedId}';

    protected $description = 'Update a product feed';

    public function handle(LightspeedEcomApi $lightspeedEcomApi, SaveProductFeedAction $saveProductFeedAction): int
    {
        // get product feed
        $feedId = $this->argument('productFeedId');
        $feed = ProductFeed::find($feedId);

        if (! $feed) {
            $this->error(sprintf('Product feed with ID %d not found.', $feedId));

            return 1;
        }

        $this->lightspeedEcomApi = $lightspeedEcomApi;

        $mappingClass = $this->askMappingClass($feed);
        $cronExpression = $this->askCronExpression($feed);
        $apiKey = $this->askApiKey($feed);
        $apiSecret = $this->askApiSecret($feed);

        // set credentials
        $this->lightspeedEcomApi->setCredentials($apiKey, $apiSecret);

        try {
            $language = $this->askLanguage($feed);

            // generate base URL and add language if the shop has multiple languages
            $baseUrl = $this->getWebshopUrl($this->getWebshopLanguageCodes(), $language);
        } catch (WebshopappApiException|LightspeedEcomApiException $e) {
            $this->error('Lightspeed eCom Error: ' . $e->getMessage());

            return 1;
        }

        $feed = $saveProductFeedAction->execute([
            'language' => $language,
            'cron_expression' => $cronExpression,
            'base_url' => $baseUrl,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'mapping_class' => $mappingClass === GenerateProductPayloadAction::class ? null : $mappingClass, // set null when using the default
        ], $feed);

        $this->info('Product feed with ID ' . $feed->id . ' has been updated.');

        return 0;
    }
}
