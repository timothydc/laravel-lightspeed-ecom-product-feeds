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

    protected array $reservedWords = ['product']; // in lowercase

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
        if ($this->useVariantAsBaseProduct) {
            $this->feed = $this->generateProductInfoFromVariant($lightspeedData);
        } else {
            $this->feed = $this->generateProductInfoFromProduct($lightspeedData);
        }
    }

    protected function generateProductInfoFromProduct(array $lightspeedData): array
    {
        // get the default variant
        $variant = collect($lightspeedData['variants'])->filter(fn ($variant) => $variant['isDefault'])->first();

        return [
            'variant_id' => $variant['id'],
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
            'article_code' => $variant['articleCode'],
            'ean' => $variant['ean'],
            'sku' => $variant['sku'],
            'tax' => $variant['tax'],
            'price_incl' => $variant['priceIncl'],
            'old_price_incl' => $variant['oldPriceIncl'],
            'stock_level' => $variant['stockLevel'],
        ];
    }

    protected function generateProductInfoFromVariant(array $lightspeedData): array
    {
        $variant = $lightspeedData['variant'];

        return [
            'variant_id' => $variant['id'],
            'product_id' => $lightspeedData['id'],
            'update_date' => $this->convertDate($lightspeedData['updatedAt']),
            'create_date' => $this->convertDate($lightspeedData['createdAt']),
            'is_featured' => $lightspeedData['isFeatured'] ? 1 : 0,
            'hits' => $lightspeedData['hits'],
            'data01' => $lightspeedData['data01'],
            'data02' => $lightspeedData['data02'],
            'data03' => $lightspeedData['data03'],
            'title' => ['_cdata' => $lightspeedData['title']],
            'fulltitle' => ['_cdata' => $lightspeedData['fulltitle']],
            'description' => ['_cdata' => $lightspeedData['description']],
            'content' => ['_cdata' => $lightspeedData['content']],
            'brand' => ['_cdata' => $lightspeedData['brand']['title'] ?? ''],
            'supplier' => ['_cdata' => $lightspeedData['supplier']['title'] ?? ''],
            'default_image_thumb' => $lightspeedData['image']['thumb'] ?? '',
            'default_image_src' => $lightspeedData['image']['src'] ?? '',
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
            'article_code' => $variant['articleCode'],
            'ean' => $variant['ean'],
            'sku' => $variant['sku'],
            'tax' => $variant['tax'],
            'price_incl' => $variant['priceIncl'],
            'old_price_incl' => $variant['oldPriceIncl'],
            'stock_level' => $variant['stockLevel'],
        ];
    }

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toDateTimeString();
    }

    public function setReservedWords(array $reservedWords): self
    {
        $this->reservedWords = $reservedWords;
        return $this;
    }
}
