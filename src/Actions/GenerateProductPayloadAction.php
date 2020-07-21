<?php

declare(strict_types=1);

namespace TimothyDC\LightspeedEcomProductFeed\Actions;

use TimothyDC\LightspeedEcomProductFeed\Interfaces\ProductPayloadMappingInterface;
use Illuminate\Support\Carbon;
use Exception;

class GenerateProductPayloadAction implements ProductPayloadMappingInterface
{
    protected string $baseUrl;
    protected array $feed = [];

    public function execute(string $baseUrl, array $lightspeedData): array
    {
        $this->baseUrl = $baseUrl;

        if ($lightspeedData['isVisible'] === false) {
            return [];
        }

        // add product data
        $this->generateProductInfo($lightspeedData);

        // add image data
        $this->generateImageInfo($lightspeedData);

        // add category data
        $this->generateCategoryInfo($lightspeedData);

        // add variant data
        $this->generateVariantInfo($lightspeedData);

        // add filter data
        $this->generateFilterInfo($lightspeedData);

        // add specification data
        $this->generateSpecificationInfo($lightspeedData);

        // add custom mapping dummy method
        $this->generateCustomInfo($lightspeedData);

        return $this->feed;
    }

    protected function convertDate(string $date): string
    {
        return Carbon::createFromTimeString($date)->toDateTimeString();
    }

    protected function generateProductInfo(array $lightspeedData): void
    {
        $this->feed = [
            'product_id' => $lightspeedData['id'],
            'update_date' => $this->convertDate($lightspeedData['updatedAt']),
            'create_date' => $this->convertDate($lightspeedData['createdAt']),
            'is_featured' => $lightspeedData['isFeatured'],
            'hits' => $lightspeedData['hits'],
            'data01' => $lightspeedData['data01'],
            'data02' => $lightspeedData['data02'],
            'data03' => $lightspeedData['data03'],
            'url' => $this->baseUrl . $lightspeedData['url'] . '.html',
            'title' => ['_cdata' => $lightspeedData['title']],
            'fulltitle' => ['_cdata' => $lightspeedData['fulltitle']],
            'description' => ['_cdata' => $lightspeedData['description']],
            'content' => ['_cdata' => $lightspeedData['content']],
            'brand' => $lightspeedData['brand']['title'] ?? '',
            'supplier' => $lightspeedData['supplier']['title'] ?? '',
            'default_image_thumb' => $lightspeedData['image']['thumb'] ?? '',
            'default_image_src' => $lightspeedData['image']['src'] ?? '',
        ];
    }

    protected function generateImageInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['images'] as $image) {
            $this->feed['images']['image'][] = ['thumb' => $image['thumb'], 'src' => $image['src']];
        }
    }

    protected function generateCategoryInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['categories'] as $category) {
            $this->feed['categories']['category'][] = ['title' => $category['title'], 'url' => ($this->baseUrl . $category['url']), 'visible' => $category['isVisible']];
        }
    }

    protected function generateVariantInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['variants'] as $variant) {
            $this->feed['variants']['variant'][] = [
                'variant_id' => $variant['id'],
                'update_date' => $this->convertDate($variant['updatedAt']),
                'create_date' => $this->convertDate($variant['createdAt']),
                'url' => $this->baseUrl . $lightspeedData['url'] . '.html?id=' . $variant['id'],
                'sort_order' => $variant['sortOrder'],
                'title' => $variant['title'],
                'article_code' => $variant['articleCode'],
                'ean' => $variant['ean'],
                'sku' => $variant['sku'],
                'tax' => $variant['tax'],
                'price_excl' => $variant['priceExcl'],
                'price_incl' => $variant['priceIncl'],
                'old_price_excl' => $variant['oldPriceExcl'],
                'old_price_incl' => $variant['oldPriceIncl'],
                'stock_tracking' => $variant['stockTracking'],
                'stock_level' => $variant['stockLevel'],
                'stock_sold' => $variant['stockSold'],
                'stock_buy_minimum' => $variant['stockBuyMinimum'],
                'stock_buy_maximum' => $variant['stockBuyMaximum'],
            ];
        }
    }

    protected function generateFilterInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['filters'] as $filter) {
            $productFilter = ['title' => $filter['title']];

            foreach ($filter['values'] as $filterValue) {
                $productFilter['values']['value'][] = ['title' => $filterValue['title']];
            }

            $this->feed['filters']['filter'][] = $productFilter;
        }
    }

    protected function generateSpecificationInfo(array $lightspeedData): void
    {
        foreach ($lightspeedData['specifications'] as $specification) {
            $this->feed['specifications']['specification'][] = ['title' => $specification['name'], 'value' => $specification['value']];
        }
    }

    protected function generateCustomInfo(array $lightspeedData): void
    {
    }
}
