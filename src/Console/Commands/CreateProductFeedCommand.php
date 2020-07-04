<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Cron\CronExpression;
use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\Actions\GenerateProductPayloadAction;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use TimothyDC\LightspeedEcomProductFeed\Traits\AskUntilValidTrait;
use WebshopappApiException;

class CreateProductFeedCommand extends Command
{
    use AskUntilValidTrait;

    protected $signature = 'ecom-feed:create';

    protected $description = 'Create a new product feed definition';

    private LightspeedEcomApi $lightspeedEcomApi;

    public function __construct(LightspeedEcomApi $lightspeedEcomApi)
    {
        parent::__construct();

        $this->lightspeedEcomApi = $lightspeedEcomApi;
    }

    public function handle(): int
    {
        $mappingClass = $this->askWithValidation('Enter your custom mapping class', 'mapping_class', [
            'bail',
            fn ($attribute, $value, $fail) => ! class_exists($value)
                ? $fail('Class "' . $value . '" not found.')
                : null,
            fn ($attribute, $value, $fail) => ! (new $value()) instanceof ProductPayloadMappingInterface
                ? $fail($value . ' must implement ' . ProductPayloadMappingInterface::class)
                : null,
        ], GenerateProductPayloadAction::class);

        $cronExpression = $this->anticipateWithValidation('Enter your cron interval',
            fn ($input) => ['* * * * *', '*/30 * * * *', '0 */2 * * *', '0 */4 * * *', '0 0 * * *'],
            'cron_expression', [
                fn ($attribute, $value, $fail) => ! CronExpression::isValidExpression($value)
                    ? $fail('"' . $value . '" is not a valid cron expression.')
                    : null,
            ], '30 */4 * * *');

        $apiKey = $this->askWithValidation('Enter your webshop API key', 'API key', ['required']);
        $apiSecret = $this->askWithValidation('Enter your webshop API secret', 'API secret', ['required']);

        $productFeed = new ProductFeed();
        $productFeed->uuid = Uuid::uuid4();
        $productFeed->api_key = $apiKey;
        $productFeed->api_secret = $apiSecret;
        $productFeed->cron_expression = $cronExpression;
        $productFeed->mapping_class = $mappingClass === GenerateProductPayloadAction::class ? null : $mappingClass; // set null when using the default

        try {
            // set freshly entered credentials
            $this->lightspeedEcomApi->setCredentials($productFeed->api_key, $productFeed->api_secret);

            // get languages from Lightspeed
            $lsLanguages = array_column($this->lightspeedEcomApi->api()->languages->get(), 'code');

            // get user prefered language
            $language = $this->choice('What language should your feed be in?', $lsLanguages, reset($lsLanguages));

            // set language
            $productFeed->language = $language;

            // generate base URL and add language if the shop has multiple languages
            $shop = $this->lightspeedEcomApi->api()->shop->get();
            $baseUrl = 'https://' . $shop['mainDomain'] . '/' . (count($lsLanguages) > 1 ? $language . '/' : '');

        } catch (WebshopappApiException $e) {
            $this->error('Lightspeed eCom Error: ' . $e->getMessage());

            return 1;
        }

        $productFeed->base_url = $baseUrl;
        $productFeed->save();

        $this->info('New product feed created with ID ' . $productFeed->id);

        return 0;
    }
}
