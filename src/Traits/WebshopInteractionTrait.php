<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits;

use TimothyDC\LightspeedEcomProductFeed\Exceptions\LightspeedEcomApiException;
use TimothyDC\LightspeedEcomProductFeed\LightspeedEcomApi;
use WebshopappApiException;

trait WebshopInteractionTrait
{
    protected LightspeedEcomApi $lightspeedEcomApi;

    protected array $webshopLanguageCodes = [];

    protected function getWebshopLanguageCodes(): array
    {
        if (! $this->webshopLanguageCodes) {
            // get languages from Lightspeed
            $this->webshopLanguageCodes = array_column($this->lightspeedEcomApi->api()->languages->get(), 'code');
        }

        return $this->webshopLanguageCodes;
    }

    /**
     * @throws WebshopappApiException|LightspeedEcomApiException
     */
    protected function getWebshopUrl(array $languages, string $userPreferedLanguage): string
    {
        // generate base URL and add language if the shop has multiple languages
        $shop = $this->lightspeedEcomApi->api()->shop->get();
        return 'https://' . $shop['mainDomain'] . '/' . (count($languages) > 1 ? $userPreferedLanguage . '/' : '');
    }
}