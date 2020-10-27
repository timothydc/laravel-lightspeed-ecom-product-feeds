<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Traits;

use TimothyDC\LightspeedEcomApi\LightspeedEcomApi;
use WebshopappApiException;

trait WebshopInteractionTrait
{
    protected array $webshopLanguageCodes = [];

    protected function getWebshopLanguageCodes(): array
    {
        if (! $this->webshopLanguageCodes) {
            // get languages from Lightspeed
            $this->webshopLanguageCodes = array_column(LightspeedEcomApi::languages()->get(), 'code');
        }

        return $this->webshopLanguageCodes;
    }

    /**
     * @throws WebshopappApiException
     */
    protected function getWebshopUrl(array $languages, string $userPreferedLanguage): string
    {
        // generate base URL and add language if the shop has multiple languages
        $shop = LightspeedEcomApi::shop()->get();

        return 'https://' . $shop['mainDomain'] . '/' . (count($languages) > 1 ? $userPreferedLanguage . '/' : '');
    }
}
