<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Console\Commands;

use Illuminate\Console\Command;
use Ramsey\Uuid\Uuid;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;
use WebshopappApiException;

class CreateProductFeedCommand extends Command
{
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
        $apiKey = $this->ask('Enter your webshop API key');
        $apiSecret = $this->ask('Enter your webshop API secret');
        $cronExpression = $this->anticipate('Enter your cron interval', fn($input) => ['* * * * *', '*/30 * * * *', '0 */2 * * *', '0 */4 * * *', '0 0 * * *']);

        $productFeed = new ProductFeed();
        $productFeed->uuid = Uuid::uuid4();
        $productFeed->api_key = $apiKey;
        $productFeed->api_secret = $apiSecret;
        $productFeed->cron_expression = $cronExpression;

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
