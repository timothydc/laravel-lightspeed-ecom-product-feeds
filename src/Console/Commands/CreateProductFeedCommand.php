<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use TimothyDC\LightspeedEcomProductFeed\Actions\GenerateProductPayloadAction;
use TimothyDC\LightspeedEcomProductFeed\Actions\SaveProductFeedAction;
use TimothyDC\LightspeedEcomProductFeed\Exceptions\LightspeedEcomApiException;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Traits\AskFeedQuestionsTrait;
use WebshopappApiException;

class CreateProductFeedCommand extends Command
{
    use AskFeedQuestionsTrait;

    protected $signature = 'ecom-feed:create';

    protected $description = 'Create a new product feed definition';

    public function handle(LightspeedEcomApi $lightspeedEcomApi, SaveProductFeedAction $saveProductFeedAction): int
    {
        $this->lightspeedEcomApi = $lightspeedEcomApi;

        $mappingClass = $this->askMappingClass();
        $cronExpression = $this->askCronExpression();
        $apiKey = $this->askApiKey();
        $apiSecret = $this->askApiSecret();

        // set freshly entered credentials
        $this->lightspeedEcomApi->setCredentials($apiKey, $apiSecret);

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
            'mapping_class' => $mappingClass === GenerateProductPayloadAction::class ? null : $mappingClass, // set null when using the default
        ]);

        $this->info('New product feed created with ID ' . $feed->id);

        return 0;
    }
}
