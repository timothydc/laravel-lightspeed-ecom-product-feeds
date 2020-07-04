<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Services;

use WebshopappApiClient;

class ApiClient
{
    protected string $key = '';
    protected string $secret = '';
    protected string $language = 'nl';

    protected WebshopappApiClient $client;

    public function withCredentials(string $key, string $secret): WebshopappApiClient
    {
        $this->key = $key;
        $this->secret = $secret;

        return new WebshopappApiClient('live', $this->key, $this->secret, $this->language);
    }
}
