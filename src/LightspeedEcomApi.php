<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed;

use TimothyDC\LightspeedEcomProductFeed\Exceptions\LightspeedEcomApiException;
use TimothyDC\LightspeedEcomProductFeed\Services\ApiClient;
use WebshopappApiClient;

class LightspeedEcomApi
{
    protected string $key;
    protected string $secret;

    private ApiClient $apiClient;

    public function __construct(ApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function api(): WebshopappApiClient
    {
        if (! $this->key || ! $this->secret) {
            throw new LightspeedEcomApiException(['No API credentials set via "setCredentials($key, $secret)"-method']);
        }

        return $this->apiClient->withCredentials($this->key, $this->secret);
    }

    public function setCredentials(string $key, string $secret): self
    {
        $this->key = $key;
        $this->secret = $secret;

        return $this;
    }
}
