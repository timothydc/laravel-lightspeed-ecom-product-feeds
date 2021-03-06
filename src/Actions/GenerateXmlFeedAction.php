<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Actions;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;
use TimothyDC\LightspeedEcomApi\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class GenerateXmlFeedAction
{
    public ProductPayloadMappingInterface $generateProductPayloadAction;

    public string $rootElementName = 'products';
    public string $childElementName = 'product';
    public string $storageOptions = 'public';

    protected array $feed = [];

    public function __construct(ProductPayloadMappingInterface $generateProductPayloadAction)
    {
        $this->generateProductPayloadAction = $generateProductPayloadAction;
    }

    public function execute(ProductFeed $productFeed): void
    {
        // set language
        LightspeedEcomApi::setLanguage($productFeed->language);

        // set credentials
        LightspeedEcomApi::setCredentials($productFeed->api_key, $productFeed->api_secret);

        // generate payload
        $payload = $this->generatePayload($productFeed);

        // convert array to XML
        $xmlString = $this->convertArrayToXml($payload);

        // save XML string to file
        Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->put($productFeed->uuid . '.xml', $xmlString, $this->storageOptions);

        // bump last updated timestamp
        $productFeed->last_updated_at = now();
        $productFeed->save();
    }

    protected function generatePayload(ProductFeed $productFeed): array
    {
        $params = ['limit' => 250, 'page' => 1];

        while ($products = LightspeedEcomApi::catalog()->get(null, $params)) {
            if (property_exists(get_class($this->generateProductPayloadAction), 'useVariantAsBaseProduct')
                && $this->generateProductPayloadAction->useVariantAsBaseProduct === true) {
                foreach ($products as $product) {
                    foreach ($product['variants'] as $variant) {
                        $this->appendToFeed(
                            $this->generateProductPayloadAction->execute($productFeed->base_url, $product + ['variant' => $variant])
                        );
                    }
                }
            } else {
                foreach ($products as $product) {
                    // generate product payload
                    $this->appendToFeed(
                        $this->generateProductPayloadAction->execute($productFeed->base_url, $product)
                    );
                }
            }

            $params['page']++;
        }

        return $this->feed;
    }

    protected function appendToFeed(array $payload): void
    {
        if ($payload) {
            $this->feed[$this->childElementName][] = $payload;
        }
    }

    protected function convertArrayToXml(array $payload): string
    {
        $arrayToXml = new ArrayToXml($payload, $this->rootElementName);
        $arrayToXml->setDomProperties(['formatOutput' => App::environment('production') === false]);

        return $arrayToXml->toXml();
    }
}
