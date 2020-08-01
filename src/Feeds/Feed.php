<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Feeds;

use Illuminate\Support\Carbon;
use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;

abstract class Feed implements ProductPayloadMappingInterface
{
    public bool $useVariantAsBaseProduct = false;

    protected string $baseUrl;
    protected array $feed = [];

    public function execute(string $baseUrl, array $lightspeedData): array
    {
        $this->baseUrl = $baseUrl;

        if ($this->shouldSkip($lightspeedData)) {
            return [];
        }

        // add product data
        $this->generateProductInfo($lightspeedData);

        // add image data
        if (method_exists($this, 'generateImageInfo')) {
            $this->generateImageInfo($lightspeedData);
        }

        // add category data
        if (method_exists($this, 'generateCategoryInfo')) {
            $this->generateCategoryInfo($lightspeedData);
        }

        // add variant data
        if (method_exists($this, 'generateVariantInfo')) {
            $this->generateVariantInfo($lightspeedData);
        }

        // add filter data
        if (method_exists($this, 'generateFilterInfo')) {
            $this->generateFilterInfo($lightspeedData);
        }

        // add specification data
        if (method_exists($this, 'generateSpecificationInfo')) {
            $this->generateSpecificationInfo($lightspeedData);
        }

        return $this->feed;
    }

    protected function shouldSkip(array $lightspeedData): bool
    {
        return $lightspeedData['isVisible'] === false;
    }

    protected function generateProductInfo(array $lightspeedData): void
    {
        $this->feed = [
            'product_id' => $lightspeedData['id'],
            'update_date' => $this->convertDate($lightspeedData['updatedAt']),
            'create_date' => $this->convertDate($lightspeedData['createdAt']),
            'is_featured' => $lightspeedData['isFeatured'] ? 1 : 0,
            'hits' => $lightspeedData['hits'],
            'data01' => $lightspeedData['data01'],
            'data02' => $lightspeedData['data02'],
            'data03' => $lightspeedData['data03'],
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html',
            'title' => ['_cdata' => $lightspeedData['title']],
            'fulltitle' => ['_cdata' => $lightspeedData['fulltitle']],
            'description' => ['_cdata' => $lightspeedData['description']],
            'content' => ['_cdata' => $lightspeedData['content']],
            'brand' => ['_cdata' => $lightspeedData['brand']['title'] ?? ''],
            'supplier' => ['_cdata' => $lightspeedData['supplier']['title'] ?? ''],
            'default_image_thumb' => $lightspeedData['image']['thumb'] ?? '',
            'default_image_src' => $lightspeedData['image']['src'] ?? '',
        ];
    }

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toDateTimeString();
    }
}
