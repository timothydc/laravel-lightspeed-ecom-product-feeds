<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use TimothyDC\LightspeedEcomApi\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Actions\SaveProductFeedAction;
use TimothyDC\LightspeedEcomProductFeed\Exceptions\LightspeedEcomApiException;
use TimothyDC\LightspeedEcomProductFeed\Feeds\StandardFeed;
use TimothyDC\LightspeedEcomProductFeed\Traits\AskFeedQuestionsTrait;
use WebshopappApiException;

class CreateProductFeedCommand extends Command
{
    use AskFeedQuestionsTrait;

    protected $signature = 'ecom-feed:create';

    protected $description = 'Create a new product feed definition';

    public function handle(SaveProductFeedAction $saveProductFeedAction): int
    {
        $mappingClass = $this->askMappingClass();
        $cronExpression = $this->askCronExpression();
        $apiKey = $this->askApiKey();
        $apiSecret = $this->askApiSecret();

        // set freshly entered credentials
        LightspeedEcomApi::setCredentials($apiKey, $apiSecret);

        try {
            $language = $this->askLanguage();

            // generate base URL and add language if the shop has multiple languages
            $baseUrl = $this->getWebshopUrl($this->getWebshopLanguageCodes(), $language);
        } catch (WebshopappApiException | LightspeedEcomApiException $e) {
            $this->error('Lightspeed eCom Error: ' . $e->getMessage());

            return 1;
        }

        $feed = $saveProductFeedAction->execute([
            'language' => $language,
            'cron_expression' => $cronExpression,
            'base_url' => $baseUrl,
            'api_key' => $apiKey,
            'api_secret' => $apiSecret,
            'mapping_class' => $mappingClass === StandardFeed::class ? null : $mappingClass, // set null when using the default
        ]);

        $this->info('New product feed created with ID ' . $feed->id);

        return 0;
    }
}
