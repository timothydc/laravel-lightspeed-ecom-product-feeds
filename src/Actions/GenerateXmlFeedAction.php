<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Actions;

use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use TimothyDC\LightspeedEcomProductFeed\Models\ProductFeed;

class GenerateXmlFeedAction
{
    private LightspeedEcomApi $lightspeedEcomApi;
    private ProductPayloadMappingInterface $generateProductPayloadAction;

    public string $rootElementName = 'products';
    public string $childElementName = 'product';
    public string $storageOptions = 'public';

    public function __construct(LightspeedEcomApi $lightspeedEcomApi, ProductPayloadMappingInterface $generateProductPayloadAction)
    {
        $this->lightspeedEcomApi = $lightspeedEcomApi;
        $this->generateProductPayloadAction = $generateProductPayloadAction;
    }

    public function execute(ProductFeed $productFeed): void
    {
        // set credentials
        $this->lightspeedEcomApi->setCredentials($productFeed->api_key, $productFeed->api_secret);

        // generate payload
        $payload = $this->generatePayload($productFeed);

        // convert array to XML
        $xmlString = $this->convertArrayToXml($payload);

        // save XML string to file
        Storage::disk(config('lightspeed-ecom-product-feed.feed_disk'))->put($productFeed->uuid . '.xml', $xmlString, $this->storageOptions);
    }

    protected function generatePayload(ProductFeed $productFeed): array
    {
        $params = ['limit' => 250, 'page' => 1];
        $payload = [];

        while ($products = $this->lightspeedEcomApi->api()->catalog->get(null, $params)) {

            foreach ($products as $product) {
                // generate product payload
                $productPayload = $this->generateProductPayloadAction->execute($productFeed->base_url, $product);

                if ($productPayload) {
                    $payload[$this->childElementName][] = $productPayload;
                }
            }

            ++$params['page'];
        }

        return $payload;
    }

    protected function convertArrayToXml(array $payload): string
    {
        $arrayToXml = new ArrayToXml($payload, $this->rootElementName);
        $arrayToXml->setDomProperties(['formatOutput' => true]);

        return $arrayToXml->toXml();
    }
}
