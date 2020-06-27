<?php
declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Interfaces;

interface ProductPayloadMappingInterface
{
    public function execute(string $baseUrl, array $product): array;
}
