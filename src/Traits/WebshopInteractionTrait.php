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
            // get active languages from Lightspeed
            $availableLanguages = collect(LightspeedEcomApi::languages()->get())->where('isActive', true)->toArray();

            $this->webshopLanguageCodes = array_column($availableLanguages, 'code');
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
